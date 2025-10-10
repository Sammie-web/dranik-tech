<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share platform settings with all views and set app name dynamically
        try {
            if (Schema::hasTable('settings')) {
                $settings = Cache::remember('platform_settings', 60, function () {
                    return Setting::all()->pluck('value', 'key')->toArray();
                });

                // Make available in all views as $platformSettings
                View::share('platformSettings', $settings);

                // Update config values dynamically
                if (!empty($settings['site_name'])) {
                    Config::set('app.name', $settings['site_name']);
                }
                if (!empty($settings['support_email'])) {
                    Config::set('mail.from.address', $settings['support_email']);
                }
            }
        } catch (\Exception $e) {
            // If migration hasn't run yet or DB unavailable, skip gracefully
        }
    }
}
