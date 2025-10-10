<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Broadcast Channel Routes
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are used
| to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('booking.{bookingId}', function ($user, $bookingId) {
    // Only allow the booking's customer, provider, or admins to listen
    $booking = \App\Models\Booking::find($bookingId);
    if (! $booking) return false;
    return in_array($user->id, [$booking->customer_id, $booking->provider_id]) || $user->isAdmin();
});
