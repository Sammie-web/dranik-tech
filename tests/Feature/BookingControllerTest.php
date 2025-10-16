<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Service;
use App\Models\ProviderAvailability;
use App\Models\Booking;
use Carbon\Carbon;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_cannot_book_on_unavailable_weekday()
    {
        // Arrange: provider and service
        $provider = User::factory()->create(['role' => 'provider']);
    $category = \App\Models\ServiceCategory::create(['name' => 'Test Cat', 'slug' => 'test-cat']);
        $service = Service::create([
            'provider_id' => $provider->id,
            'category_id' => $category->id,
            'title' => 'Test Service',
            'slug' => 'test-service',
            'description' => 'A test service',
            'price' => 1000,
            'is_active' => true,
        ]);
        // Provider has availability only on Monday
        ProviderAvailability::create([
            'provider_id' => $provider->id,
            'day_of_week' => 'monday',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);

        // Act: customer tries to book on a Sunday
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer);

        // Find next sunday
        $date = Carbon::now()->next(Carbon::SUNDAY)->format('Y-m-d');
        $time = '10:00';

        $response = $this->post(route('bookings.store', $service), [
            'scheduled_date' => $date,
            'scheduled_time' => $time,
        ]);

        // Assert validation error about provider availability
        $response->assertSessionHasErrors('scheduled_at');
    }

    public function test_cannot_book_if_time_slot_already_taken()
    {
        // Arrange: provider, service and availability for the day
        $provider = User::factory()->create(['role' => 'provider']);
    $category = \App\Models\ServiceCategory::first() ?? \App\Models\ServiceCategory::create(['name' => 'Test Cat 2', 'slug' => 'test-cat-2']);
        $service = Service::create([
            'provider_id' => $provider->id,
            'category_id' => $category->id,
            'title' => 'Test Service 2',
            'slug' => 'test-service-2',
            'description' => 'Another test service',
            'price' => 1500,
            'is_active' => true,
        ]);
        ProviderAvailability::create([
            'provider_id' => $provider->id,
            'day_of_week' => strtolower(Carbon::now()->format('l')),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_available' => true,
        ]);

        // Create existing booking at now + 1 hour
        $bookedAt = Carbon::now()->addHour()->startOfMinute();
        $existingCustomer = User::factory()->create(['role' => 'customer']);
        Booking::create([
            'customer_id' => $existingCustomer->id,
            'provider_id' => $provider->id,
            'service_id' => $service->id,
            'scheduled_at' => $bookedAt,
            'amount' => $service->price,
            'commission' => 0,
            'status' => 'pending',
        ]);

        // Act: another customer attempts same slot
        $customer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($customer);

        $date = $bookedAt->format('Y-m-d');
        $time = $bookedAt->format('H:i');

        $response = $this->post(route('bookings.store', $service), [
            'scheduled_date' => $date,
            'scheduled_time' => $time,
        ]);

        $response->assertSessionHasErrors('scheduled_at');
    }
}
