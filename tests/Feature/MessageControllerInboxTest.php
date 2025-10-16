<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageControllerInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_inbox_and_unread_count_is_shown()
    {
        // create users
        $customer = User::factory()->create(['role' => 'customer']);
        $provider = User::factory()->create(['role' => 'provider']);

        // create a service
        $service = Service::factory()->create([
            'provider_id' => $provider->id,
            'title' => 'Test Service',
            'price' => 1000,
            'is_active' => true,
        ]);

        // booking
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'provider_id' => $provider->id,
            'service_id' => $service->id,
            'status' => 'pending',
            'amount' => 0,
        ]);

        // create messages: one unread to customer
        Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $provider->id,
            'receiver_id' => $customer->id,
            'body' => 'Hello customer',
        ]);

        // another message read
        Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $customer->id,
            'receiver_id' => $provider->id,
            'body' => 'Thanks',
            'read_at' => now(),
        ]);

        $this->actingAs($customer);

        $response = $this->get(route('customer.chats'));

        $response->assertStatus(200);
        $response->assertSeeText('Test Service');
        // unread count badge should show '1'
        $response->assertSee('>1<');
    }
}
