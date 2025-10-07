<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notificationService = new NotificationService();
        
        // Get users
        $customers = User::where('role', 'customer')->get();
        $providers = User::where('role', 'provider')->get();
        $admin = User::where('role', 'admin')->first();
        
        // Send welcome notifications to all users
        foreach ($customers as $customer) {
            $notificationService->sendWelcomeNotification($customer);
        }
        
        foreach ($providers as $provider) {
            $notificationService->sendWelcomeNotification($provider);
            $notificationService->sendProviderVerified($provider);
        }
        
        // Create some booking-related notifications
        $bookings = Booking::with(['customer', 'provider', 'service'])->get();
        
        foreach ($bookings->take(5) as $booking) {
            // Booking confirmation notifications
            $notificationService->sendBookingConfirmation($booking);
            
            if ($booking->status === 'completed') {
                // Service completion notifications
                $notificationService->sendServiceCompleted($booking);
                
                // Payment confirmation (simulate)
                if ($booking->payment) {
                    $notificationService->sendPaymentConfirmation($booking->payment);
                }
            }
        }
        
        // Create some additional sample notifications
        foreach ($customers->take(3) as $customer) {
            // Booking reminder
            Notification::create([
                'user_id' => $customer->id,
                'type' => 'booking_reminder',
                'title' => 'Upcoming Appointment Reminder',
                'message' => 'Your appointment is scheduled for tomorrow at 2:00 PM. Please be ready!',
                'data' => [
                    'booking_id' => $bookings->where('customer_id', $customer->id)->first()?->id,
                ],
                'is_read' => rand(0, 1),
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
            
            // Special offer notification
            Notification::create([
                'user_id' => $customer->id,
                'type' => 'special_offer',
                'title' => 'Special Offer Just for You!',
                'message' => 'Get 20% off your next booking with code SAVE20. Limited time offer!',
                'data' => [
                    'offer_code' => 'SAVE20',
                    'discount' => 20,
                ],
                'is_read' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 7)),
            ]);
        }
        
        foreach ($providers->take(3) as $provider) {
            // Earnings notification
            Notification::create([
                'user_id' => $provider->id,
                'type' => 'earnings_update',
                'title' => 'Weekly Earnings Summary',
                'message' => 'You earned ₦' . number_format(rand(50000, 200000)) . ' this week from ' . rand(5, 15) . ' completed services.',
                'data' => [
                    'period' => 'weekly',
                    'amount' => rand(50000, 200000),
                    'services_count' => rand(5, 15),
                ],
                'is_read' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 7)),
            ]);
            
            // Profile update reminder
            Notification::create([
                'user_id' => $provider->id,
                'type' => 'profile_reminder',
                'title' => 'Update Your Profile',
                'message' => 'Complete your profile to attract more customers. Add photos and update your bio!',
                'data' => [
                    'completion_percentage' => rand(60, 85),
                ],
                'is_read' => rand(0, 1),
                'created_at' => now()->subDays(rand(2, 10)),
            ]);
        }
        
        // Admin notifications
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'platform_stats',
                'title' => 'Daily Platform Statistics',
                'message' => 'Today: ' . rand(20, 50) . ' new bookings, ' . rand(5, 15) . ' new users, ₦' . number_format(rand(500000, 1000000)) . ' in transactions.',
                'data' => [
                    'new_bookings' => rand(20, 50),
                    'new_users' => rand(5, 15),
                    'transactions' => rand(500000, 1000000),
                ],
                'is_read' => false,
                'created_at' => now()->subHours(rand(1, 6)),
            ]);
            
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'pending_verification',
                'title' => 'Providers Pending Verification',
                'message' => rand(2, 8) . ' providers are waiting for account verification. Please review their applications.',
                'data' => [
                    'pending_count' => rand(2, 8),
                ],
                'is_read' => rand(0, 1),
                'created_at' => now()->subHours(rand(6, 24)),
            ]);
        }
    }
}
