<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

class PlatformSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // additional admin middleware can be added
    }

    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', "D'RANIK Techs"),
            'support_email' => Setting::get('support_email', 'support@draniktech.com'),
        ];

        return view('admin.platform-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'logo' => 'nullable|image|max:2048',
            // Increase favicon max to 3MB (3072 KB)
            'favicon' => 'nullable|image|max:3072',
        ]);

        Setting::set('site_name', $data['site_name']);
        Setting::set('support_email', $data['support_email']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('site_favicon', $path);
        }

        // Clear cached platform settings so AppServiceProvider picks up changes
        Cache::forget('platform_settings');

        return redirect()->back()->with('status', 'Platform settings updated successfully.');
    }
}
