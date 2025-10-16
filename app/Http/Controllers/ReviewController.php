<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display reviews for a service
     */
    public function index(Service $service)
    {
        $reviews = $service->reviews()
            ->with(['customer'])
            ->verified()
            ->latest()
            ->paginate(10);

        $ratingBreakdown = $this->getRatingBreakdown($service);

        return view('reviews.index', compact('service', 'reviews', 'ratingBreakdown'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create(Booking $booking)
    {
        // Ensure user can only review their own completed bookings
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->back()->withErrors(['error' => 'You can only review completed services.']);
        }

        // Check if review already exists
        if ($booking->review) {
            return redirect()->route('reviews.show', $booking->review)
                ->with('info', 'You have already reviewed this service.');
        }

        $booking->load(['service', 'provider']);

        return view('reviews.create', compact('booking'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Booking $booking)
    {
        // Ensure user can only review their own completed bookings
        if ($booking->customer_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return redirect()->back()->withErrors(['error' => 'You can only review completed services.']);
        }

        // Check if review already exists
        if ($booking->review) {
            return redirect()->route('reviews.show', $booking->review)
                ->with('info', 'You have already reviewed this service.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

    DB::beginTransaction();
    try {
            // Handle image uploads
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $images[] = Storage::url($path);
                }
            }

            // Create review
            $review = Review::create([
                'booking_id' => $booking->id,
                'customer_id' => auth()->id(),
                'provider_id' => $booking->provider_id,
                'service_id' => $booking->service_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $images,
                'is_verified' => true, // Auto-verify since it's from a completed booking
            ]);

            // Update service and provider ratings
            $this->updateRatings($booking->service, $booking->provider);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'ok', 'review' => $review], 201);
            }

            return redirect()->route('reviews.show', $review)
                ->with('success', 'Thank you for your review! It helps other customers make informed decisions.');

        } catch (\Exception $e) {
            DB::rollback();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Failed to submit review. Please try again.'], 500);
            }
            return back()->withErrors(['error' => 'Failed to submit review. Please try again.']);
        }
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $review->load(['customer', 'provider', 'service', 'booking']);

        // Check if user can view this review
        if (!$this->canViewReview($review)) {
            abort(403);
        }

        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified review
     */
    public function edit(Review $review)
    {
        // Only customer can edit their own review within 7 days
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($review->created_at->diffInDays(now()) > 7) {
            return redirect()->back()->withErrors(['error' => 'Reviews can only be edited within 7 days of submission.']);
        }

        $review->load(['service', 'provider', 'booking']);

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review
     */
    public function update(Request $request, Review $review)
    {
        // Only customer can edit their own review within 7 days
        if ($review->customer_id !== auth()->id()) {
            abort(403);
        }

        if ($review->created_at->diffInDays(now()) > 7) {
            return redirect()->back()->withErrors(['error' => 'Reviews can only be edited within 7 days of submission.']);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle image uploads
            $images = $review->images ?? [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $images[] = Storage::url($path);
                }
            }

            // Update review
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $images,
            ]);

            // Update service and provider ratings
            $this->updateRatings($review->service, $review->provider);

            DB::commit();

            return redirect()->route('reviews.show', $review)
                ->with('success', 'Review updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update review. Please try again.']);
        }
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Only customer or admin can delete review
        if ($review->customer_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $service = $review->service;
            $provider = $review->provider;

            $review->delete();

            // Update service and provider ratings
            $this->updateRatings($service, $provider);

            DB::commit();

            return redirect()->back()->with('success', 'Review deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to delete review. Please try again.']);
        }
    }

    /**
     * Toggle featured status of a review (Admin only)
     */
    public function toggleFeatured(Review $review)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $review->update(['is_featured' => !$review->is_featured]);

        return back()->with('success', 'Review featured status updated.');
    }

    /**
     * Get reviews for a provider
     */
    public function providerReviews(User $provider)
    {
        if (!$provider->isProvider()) {
            abort(404);
        }

        $reviews = $provider->receivedReviews()
            ->with(['customer', 'service'])
            ->verified()
            ->latest()
            ->paginate(10);

        $ratingBreakdown = $this->getProviderRatingBreakdown($provider);

        return view('reviews.provider', compact('provider', 'reviews', 'ratingBreakdown'));
    }

    /**
     * Get customer's reviews
     */
    public function customerReviews()
    {
        $reviews = auth()->user()->givenReviews()
            ->with(['provider', 'service'])
            ->latest()
            ->paginate(10);

        return view('reviews.customer', compact('reviews'));
    }

    /**
     * List completed bookings belonging to customer that don't have a review yet
     */
    public function pendingForCustomer()
    {
        $user = auth()->user();
        $bookings = Booking::where('customer_id', $user->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with(['service', 'provider'])
            ->latest('completed_at')
            ->paginate(10);

        return view('reviews.pending', compact('bookings'));
    }

    /**
     * Report a review as inappropriate
     */
    public function report(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Here you would typically create a report record
        // For now, we'll just log it
        \Log::info('Review reported', [
            'review_id' => $review->id,
            'reported_by' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Review has been reported. We will investigate and take appropriate action.');
    }

    /**
     * Update service and provider ratings
     */
    private function updateRatings(Service $service, User $provider)
    {
        // Update service rating
        $serviceRating = $service->reviews()->verified()->avg('rating');
        $serviceReviewCount = $service->reviews()->verified()->count();
        
        $service->update([
            'rating' => round($serviceRating, 2),
            'total_reviews' => $serviceReviewCount,
        ]);

        // Update provider rating
        $providerRating = $provider->receivedReviews()->verified()->avg('rating');
        $providerReviewCount = $provider->receivedReviews()->verified()->count();
        
        $provider->update([
            'rating' => round($providerRating, 2),
            'total_reviews' => $providerReviewCount,
        ]);
    }

    /**
     * Get rating breakdown for a service
     */
    private function getRatingBreakdown(Service $service)
    {
        $breakdown = [];
        $totalReviews = $service->reviews()->verified()->count();

        for ($i = 5; $i >= 1; $i--) {
            $count = $service->reviews()->verified()->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            
            $breakdown[$i] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return $breakdown;
    }

    /**
     * Get rating breakdown for a provider
     */
    private function getProviderRatingBreakdown(User $provider)
    {
        $breakdown = [];
        $totalReviews = $provider->receivedReviews()->verified()->count();

        for ($i = 5; $i >= 1; $i--) {
            $count = $provider->receivedReviews()->verified()->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            
            $breakdown[$i] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return $breakdown;
    }

    /**
     * Check if user can view review
     */
    private function canViewReview(Review $review)
    {
        return $review->customer_id === auth()->id() 
            || $review->provider_id === auth()->id() 
            || auth()->user()->isAdmin();
    }
}
