<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = \App\Models\User::where('role', 'provider')->get();
        
        foreach ($providers as $provider) {
            // Create availability for weekdays (Monday to Friday)
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            foreach ($weekdays as $day) {
                \App\Models\ProviderAvailability::create([
                    'provider_id' => $provider->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_available' => true,
                ]);
            }
            
            // Create availability for Saturday (shorter hours)
            \App\Models\ProviderAvailability::create([
                'provider_id' => $provider->id,
                'day_of_week' => 'saturday',
                'start_time' => '10:00',
                'end_time' => '15:00',
                'is_available' => true,
            ]);
            
            // Sunday - not available
            \App\Models\ProviderAvailability::create([
                'provider_id' => $provider->id,
                'day_of_week' => 'sunday',
                'start_time' => '10:00',
                'end_time' => '15:00',
                'is_available' => false,
            ]);
        }
    }
}
