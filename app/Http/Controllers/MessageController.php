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

        Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'body' => $request->body,
        ]);

        return redirect()->route('chat.thread', $booking);
    }
}


