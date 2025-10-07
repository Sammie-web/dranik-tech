<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Booking;
use App\Models\User;
use App\Models\Service;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's create some completed bookings to review
        $customers = User::where('role', 'customer')->get();
        $services = Service::with('provider')->get();
        
        $bookings = [];
        
        // Create some completed bookings
        foreach ($services as $service) {
            // Create 2-4 bookings per service
            $bookingCount = rand(2, 4);
            
            for ($i = 0; $i < $bookingCount; $i++) {
                $customer = $customers->random();
                
                $booking = Booking::create([
                    'customer_id' => $customer->id,
                    'provider_id' => $service->provider_id,
                    'service_id' => $service->id,
                    'scheduled_at' => now()->subDays(rand(7, 30)),
                    'completed_at' => now()->subDays(rand(1, 7)),
                    'status' => 'completed',
                    'amount' => $service->price,
                    'commission' => $service->price * 0.05,
                ]);
                
                $bookings[] = $booking;
            }
        }
        
        // Sample review comments
        $positiveComments = [
            "Excellent service! The provider was professional, punctual, and delivered exactly what was promised. Highly recommend!",
            "Outstanding work quality. Very satisfied with the results and the attention to detail. Will definitely book again.",
            "Great experience from start to finish. Professional, friendly, and efficient. Worth every penny!",
            "Exceeded my expectations! The provider went above and beyond to ensure I was completely satisfied.",
            "Top-notch service! Clean, professional, and completed on time. Couldn't ask for better.",
            "Amazing work! The provider was skilled, courteous, and delivered exceptional results.",
            "Perfect service! Everything was done exactly as requested and the quality was superb.",
            "Fantastic experience! Professional, reliable, and great value for money. Highly recommended!",
        ];
        
        $neutralComments = [
            "Good service overall. The work was completed as expected, though there were minor delays.",
            "Decent service. The provider was professional and the work was satisfactory.",
            "Average experience. The service was okay but nothing exceptional. Fair pricing though.",
            "The service was fine. Met basic expectations but could have been more thorough.",
            "Acceptable work quality. The provider was polite and completed the job adequately.",
        ];
        
        $negativeComments = [
            "Service was below expectations. The provider arrived late and the work quality was poor.",
            "Not satisfied with the service. Several issues were not addressed properly.",
            "Disappointing experience. The provider seemed rushed and didn't pay attention to details.",
        ];
        
        // Create reviews for most bookings
        foreach ($bookings as $booking) {
            // 85% chance of getting a review
            if (rand(1, 100) <= 85) {
                // Determine rating and comment based on probability
                $rand = rand(1, 100);
                
                if ($rand <= 60) {
                    // 60% chance of 4-5 star reviews
                    $rating = rand(4, 5);
                    $comment = $positiveComments[array_rand($positiveComments)];
                } elseif ($rand <= 85) {
                    // 25% chance of 3 star reviews
                    $rating = 3;
                    $comment = $neutralComments[array_rand($neutralComments)];
                } else {
                    // 15% chance of 1-2 star reviews
                    $rating = rand(1, 2);
                    $comment = $negativeComments[array_rand($negativeComments)];
                }
                
                $review = Review::create([
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    'provider_id' => $booking->provider_id,
                    'service_id' => $booking->service_id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'is_verified' => true,
                    'is_featured' => rand(1, 100) <= 20, // 20% chance of being featured
                ]);
            }
        }
        
        // Update service and provider ratings
        foreach ($services as $service) {
            $serviceReviews = Review::where('service_id', $service->id)->get();
            if ($serviceReviews->count() > 0) {
                $avgRating = $serviceReviews->avg('rating');
                $service->update([
                    'rating' => round($avgRating, 2),
                    'total_reviews' => $serviceReviews->count(),
                ]);
                
                // Update provider rating
                $provider = $service->provider;
                $providerReviews = Review::where('provider_id', $provider->id)->get();
                if ($providerReviews->count() > 0) {
                    $providerAvgRating = $providerReviews->avg('rating');
                    $provider->update([
                        'rating' => round($providerAvgRating, 2),
                        'total_reviews' => $providerReviews->count(),
                    ]);
                }
            }
        }
    }
}
