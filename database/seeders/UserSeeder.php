<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@draniktech.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample providers
        $providers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => bcrypt('password'),
                'role' => 'provider',
                'phone' => '+234 801 234 5678',
                'address' => 'Lagos, Nigeria',
                'bio' => 'Professional home service provider with 5+ years experience',
                'is_verified' => true,
                'is_active' => true,
                'rating' => 4.8,
                'total_reviews' => 25,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'password' => bcrypt('password'),
                'role' => 'provider',
                'phone' => '+234 802 345 6789',
                'address' => 'Abuja, Nigeria',
                'bio' => 'Certified beauty and wellness specialist',
                'is_verified' => true,
                'is_active' => true,
                'rating' => 4.9,
                'total_reviews' => 18,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'password' => bcrypt('password'),
                'role' => 'provider',
                'phone' => '+234 803 456 7890',
                'address' => 'Port Harcourt, Nigeria',
                'bio' => 'Event planning and photography expert',
                'is_verified' => true,
                'is_active' => true,
                'rating' => 4.7,
                'total_reviews' => 32,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($providers as $provider) {
            \App\Models\User::create($provider);
        }

        // Create sample customers
        \App\Models\User::factory(10)->create([
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => true,
        ]);
    }
}
