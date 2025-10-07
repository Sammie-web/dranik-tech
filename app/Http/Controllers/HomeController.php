<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured categories
        $categories = ServiceCategory::active()
            ->ordered()
            ->take(8)
            ->get();

        // Get featured services
        $featuredServices = Service::active()
            ->featured()
            ->with(['provider', 'category', 'reviews'])
            ->take(8)
            ->get();

        // Get recent reviews
        $recentReviews = Review::with(['customer', 'service', 'provider'])
            ->verified()
            ->latest()
            ->take(6)
            ->get();

        // Statistics
        $stats = [
            'total_services' => Service::active()->count(),
            'total_providers' => \App\Models\User::where('role', 'provider')->where('is_active', true)->count(),
            'total_bookings' => \App\Models\Booking::where('status', 'completed')->count(),
            'average_rating' => Review::avg('rating') ?? 0,
        ];

        return view('home', compact('categories', 'featuredServices', 'recentReviews', 'stats'));
    }
}
