<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Home Services',
                'slug' => 'home-services',
                'description' => 'Professional home maintenance and repair services',
                'icon' => 'home',
                'sort_order' => 1,
            ],
            [
                'name' => 'Beauty & Wellness',
                'slug' => 'beauty-wellness',
                'description' => 'Beauty treatments, spa services, and wellness care',
                'icon' => 'heart',
                'sort_order' => 2,
            ],
            [
                'name' => 'Events & Entertainment',
                'slug' => 'events-entertainment',
                'description' => 'Event planning, photography, and entertainment services',
                'icon' => 'calendar',
                'sort_order' => 3,
            ],
            [
                'name' => 'Healthcare',
                'slug' => 'healthcare',
                'description' => 'Medical consultations and healthcare services',
                'icon' => 'activity',
                'sort_order' => 4,
            ],
            [
                'name' => 'Fitness & Sports',
                'slug' => 'fitness-sports',
                'description' => 'Personal training and sports coaching services',
                'icon' => 'zap',
                'sort_order' => 5,
            ],
            [
                'name' => 'Education & Tutoring',
                'slug' => 'education-tutoring',
                'description' => 'Academic tutoring and skill development',
                'icon' => 'book',
                'sort_order' => 6,
            ],
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'IT support, web development, and tech services',
                'icon' => 'monitor',
                'sort_order' => 7,
            ],
            [
                'name' => 'Transportation',
                'slug' => 'transportation',
                'description' => 'Ride services and delivery solutions',
                'icon' => 'truck',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\ServiceCategory::create($category);
        }
    }
}
