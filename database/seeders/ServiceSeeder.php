<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'provider_id' => 2, // John Doe
                'category_id' => 1, // Home Services
                'title' => 'Professional House Cleaning',
                'slug' => 'professional-house-cleaning',
                'description' => 'Complete house cleaning service including all rooms, kitchen, and bathrooms. We use eco-friendly products and professional equipment.',
                'price' => 15000,
                'price_type' => 'fixed',
                'duration' => 180,
                'location' => 'Lagos, Nigeria',
                'is_mobile' => true,
                'is_active' => true,
                'is_featured' => true,
                'rating' => 4.8,
                'total_reviews' => 15,
                'total_bookings' => 25,
            ],
            [
                'provider_id' => 2, // John Doe
                'category_id' => 1, // Home Services
                'title' => 'Plumbing Repair Services',
                'slug' => 'plumbing-repair-services',
                'description' => 'Expert plumbing services for leaks, installations, and repairs. Available for emergency calls.',
                'price' => 8000,
                'price_type' => 'hourly',
                'duration' => 60,
                'location' => 'Lagos, Nigeria',
                'is_mobile' => true,
                'is_active' => true,
                'is_featured' => false,
                'rating' => 4.6,
                'total_reviews' => 10,
                'total_bookings' => 18,
            ],
            [
                'provider_id' => 3, // Sarah Johnson
                'category_id' => 2, // Beauty & Wellness
                'title' => 'Mobile Spa Services',
                'slug' => 'mobile-spa-services',
                'description' => 'Relaxing spa treatments in the comfort of your home. Includes massage, facial, and wellness packages.',
                'price' => 25000,
                'price_type' => 'fixed',
                'duration' => 120,
                'location' => 'Abuja, Nigeria',
                'is_mobile' => true,
                'is_active' => true,
                'is_featured' => true,
                'rating' => 4.9,
                'total_reviews' => 12,
                'total_bookings' => 20,
            ],
            [
                'provider_id' => 3, // Sarah Johnson
                'category_id' => 2, // Beauty & Wellness
                'title' => 'Professional Makeup Services',
                'slug' => 'professional-makeup-services',
                'description' => 'Professional makeup for weddings, events, and special occasions. Includes consultation and trial.',
                'price' => 35000,
                'price_type' => 'fixed',
                'duration' => 90,
                'location' => 'Abuja, Nigeria',
                'is_mobile' => true,
                'is_active' => true,
                'is_featured' => true,
                'rating' => 5.0,
                'total_reviews' => 8,
                'total_bookings' => 15,
            ],
            [
                'provider_id' => 4, // Michael Brown
                'category_id' => 3, // Events & Entertainment
                'title' => 'Event Photography',
                'slug' => 'event-photography',
                'description' => 'Professional photography for weddings, corporate events, and celebrations. Includes editing and digital delivery.',
                'price' => 75000,
                'price_type' => 'fixed',
                'duration' => 480,
                'location' => 'Port Harcourt, Nigeria',
                'is_mobile' => true,
                'is_active' => true,
                'is_featured' => true,
                'rating' => 4.7,
                'total_reviews' => 20,
                'total_bookings' => 32,
            ],
            [
                'provider_id' => 4, // Michael Brown
                'category_id' => 3, // Events & Entertainment
                'title' => 'Wedding Planning Services',
                'slug' => 'wedding-planning-services',
                'description' => 'Complete wedding planning and coordination services. From venue selection to day-of coordination.',
                'price' => 150000,
                'price_type' => 'negotiable',
                'duration' => null,
                'location' => 'Port Harcourt, Nigeria',
                'is_mobile' => false,
                'is_active' => true,
                'is_featured' => false,
                'rating' => 4.8,
                'total_reviews' => 12,
                'total_bookings' => 18,
            ],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
