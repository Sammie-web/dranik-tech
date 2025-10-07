<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public function createNotification(User $user, string $type, string $title, string $message, array $data = [])
    {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);

            // Send email notification if user has email notifications enabled
            $this->sendEmailNotification($user, $notification);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send booking confirmation notification
     */
    public function sendBookingConfirmation($booking)
    {
        // Notify customer
        $this->createNotification(
            $booking->customer,
            'booking_confirmed',
            'Booking Confirmed',
            "Your booking for {$booking->service->title} has been confirmed for " . $booking->scheduled_at->format('M d, Y \a\t g:i A'),
            [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'provider_id' => $booking->provider_id,
            ]
        );

        // Notify provider
        $this->createNotification(
            $booking->provider,
            'new_booking',
            'New Booking Received',
            "You have a new booking for {$booking->service->title} from {$booking->customer->name}",
            [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'customer_id' => $booking->customer_id,
            ]
        );
    }

    /**
     * Send payment confirmation notification
     */
    public function sendPaymentConfirmation($payment)
    {
        // Notify customer
        $this->createNotification(
            $payment->customer,
            'payment_confirmed',
            'Payment Confirmed',
            "Your payment of ₦" . number_format($payment->amount) . " has been processed successfully",
            [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'amount' => $payment->amount,
            ]
        );

        // Notify provider
        $this->createNotification(
            $payment->provider,
            'payment_received',
            'Payment Received',
            "Payment of ₦" . number_format($payment->provider_amount) . " has been received for your service",
            [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'amount' => $payment->provider_amount,
            ]
        );
    }

    /**
     * Send booking reminder notification
     */
    public function sendBookingReminder($booking)
    {
        $this->createNotification(
            $booking->customer,
            'booking_reminder',
            'Booking Reminder',
            "Reminder: Your appointment for {$booking->service->title} is scheduled for " . $booking->scheduled_at->format('M d, Y \a\t g:i A'),
            [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'provider_id' => $booking->provider_id,
            ]
        );
    }

    /**
     * Send service completion notification
     */
    public function sendServiceCompleted($booking)
    {
        // Notify customer
        $this->createNotification(
            $booking->customer,
            'service_completed',
            'Service Completed',
            "Your service {$booking->service->title} has been completed. Please rate your experience!",
            [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'provider_id' => $booking->provider_id,
                'can_review' => true,
            ]
        );

        // Notify provider
        $this->createNotification(
            $booking->provider,
            'service_completed',
            'Service Completed',
            "You have successfully completed the service {$booking->service->title} for {$booking->customer->name}",
            [
                'booking_id' => $booking->id,
                'service_id' => $booking->service_id,
                'customer_id' => $booking->customer_id,
            ]
        );
    }

    /**
     * Send review notification
     */
    public function sendReviewNotification($review)
    {
        $this->createNotification(
            $review->provider,
            'new_review',
            'New Review Received',
            "{$review->customer->name} left a {$review->rating}-star review for your service {$review->service->title}",
            [
                'review_id' => $review->id,
                'booking_id' => $review->booking_id,
                'service_id' => $review->service_id,
                'rating' => $review->rating,
            ]
        );
    }

    /**
     * Send booking cancellation notification
     */
    public function sendBookingCancellation($booking, $cancelledBy)
    {
        if ($cancelledBy === 'customer') {
            // Notify provider
            $this->createNotification(
                $booking->provider,
                'booking_cancelled',
                'Booking Cancelled',
                "The booking for {$booking->service->title} by {$booking->customer->name} has been cancelled",
                [
                    'booking_id' => $booking->id,
                    'service_id' => $booking->service_id,
                    'customer_id' => $booking->customer_id,
                    'cancelled_by' => 'customer',
                ]
            );
        } else {
            // Notify customer
            $this->createNotification(
                $booking->customer,
                'booking_cancelled',
                'Booking Cancelled',
                "Your booking for {$booking->service->title} has been cancelled by the provider",
                [
                    'booking_id' => $booking->id,
                    'service_id' => $booking->service_id,
                    'provider_id' => $booking->provider_id,
                    'cancelled_by' => 'provider',
                ]
            );
        }
    }

    /**
     * Send welcome notification to new users
     */
    public function sendWelcomeNotification(User $user)
    {
        $message = $user->isProvider() 
            ? "Welcome to D'RANiK Techs! Start by adding your services and setting your availability."
            : "Welcome to D'RANiK Techs! Discover amazing services from verified providers in your area.";

        $this->createNotification(
            $user,
            'welcome',
            'Welcome to D\'RANiK Techs!',
            $message,
            [
                'user_type' => $user->role,
                'registration_date' => now()->toDateString(),
            ]
        );
    }

    /**
     * Send provider verification notification
     */
    public function sendProviderVerified(User $provider)
    {
        $this->createNotification(
            $provider,
            'provider_verified',
            'Account Verified!',
            "Congratulations! Your provider account has been verified. You can now start receiving bookings.",
            [
                'verification_date' => now()->toDateString(),
            ]
        );
    }

    /**
     * Send email notification (placeholder for actual email implementation)
     */
    private function sendEmailNotification(User $user, Notification $notification)
    {
        // For now, we'll just log the email notification
        // In a real application, you would send actual emails here
        Log::info('Email notification sent', [
            'user_id' => $user->id,
            'email' => $user->email,
            'type' => $notification->type,
            'title' => $notification->title,
        ]);

        // Example of how you might send actual emails:
        /*
        try {
            Mail::to($user->email)->send(new NotificationMail($notification));
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
        */
    }

    /**
     * Get notification icon based on type
     */
    public static function getNotificationIcon(string $type): string
    {
        $icons = [
            'booking_confirmed' => 'check-circle',
            'new_booking' => 'calendar',
            'payment_confirmed' => 'credit-card',
            'payment_received' => 'dollar-sign',
            'booking_reminder' => 'clock',
            'service_completed' => 'check-square',
            'new_review' => 'star',
            'booking_cancelled' => 'x-circle',
            'welcome' => 'heart',
            'provider_verified' => 'shield-check',
            'default' => 'bell',
        ];

        return $icons[$type] ?? $icons['default'];
    }

    /**
     * Get notification color based on type
     */
    public static function getNotificationColor(string $type): string
    {
        $colors = [
            'booking_confirmed' => 'green',
            'new_booking' => 'blue',
            'payment_confirmed' => 'green',
            'payment_received' => 'green',
            'booking_reminder' => 'yellow',
            'service_completed' => 'green',
            'new_review' => 'yellow',
            'booking_cancelled' => 'red',
            'welcome' => 'purple',
            'provider_verified' => 'green',
            'default' => 'gray',
        ];

        return $colors[$type] ?? $colors['default'];
    }
}

