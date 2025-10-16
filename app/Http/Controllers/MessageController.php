<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function thread(Booking $booking)
    {
        $userId = auth()->id();
        if ($booking->customer_id !== $userId && $booking->provider_id !== $userId && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $messages = Message::where('booking_id', $booking->id)
            ->with(['sender'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read for current user
        Message::where('booking_id', $booking->id)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('chat.thread', compact('booking', 'messages'));
    }

    /**
     * Show the authenticated customer's chat inbox (booking threads with messages)
     */
    public function inbox()
    {
        $user = auth()->user();

        // Only customers should access this page (providers will have their own list)
        if (!$user->isCustomer() && !$user->isAdmin()) {
            abort(403);
        }

        $query = Booking::query()->where('customer_id', $user->id)
            ->with(['service', 'provider', 'lastMessage'])
            ->where(function ($q) {
                $q->whereHas('messages')->orWhereIn('status', ['pending','confirmed']);
            });

        // search by service title or provider name
        $search = request()->query('q');
        if ($search) {
            $query->whereHas('service', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            })->orWhereHas('provider', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // unread messages count for this user
        $query = $query->withCount(['messages as unread_count' => function ($q) use ($user) {
            $q->where('receiver_id', $user->id)->whereNull('read_at');
        }]);

        // sorting
        $sort = request()->query('sort', 'recent'); // recent|unread_first
        if ($sort === 'unread') {
            // unread-first: order by unread_count desc then updated_at desc
            $query->orderByDesc('unread_count')->orderByDesc('updated_at');
        } else {
            $query->orderByDesc('updated_at');
        }

        $bookings = $query->paginate(15)->withQueryString();

        // If AJAX (fetching HTML fragment or JSON), return JSON payload
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'data' => $bookings->items(),
                'meta' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
            ]);
        }

        return view('customer.chats.index', compact('bookings', 'search', 'sort'));
    }

    public function send(Request $request, Booking $booking)
    {
        $user = auth()->user();
        if ($booking->customer_id !== $user->id && $booking->provider_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $receiverId = $booking->customer_id === $user->id ? $booking->provider_id : $booking->customer_id;

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'body' => $request->body,
        ]);

        // Broadcast the new message to the booking channel
        event(new \App\Events\MessageCreated($message));

        // Send email notification to the receiver (queued if queue is configured)
        try {
            \Mail::to($message->receiver)->queue(new \App\Mail\NewMessageNotification($message));
        } catch (\Exception $e) {
            \Log::error('Failed to queue new message email', ['error' => $e->getMessage()]);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'sent', 'data' => $message], 201);
        }

        return redirect()->route('chat.thread', $booking)->with('success', 'Message sent');
    }
}


