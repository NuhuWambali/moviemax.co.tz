<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'         => 'required|string|max:60',
            'tagline'           => 'nullable|string|max:255',
            'contact_email'     => 'nullable|email|max:120',
            'contact_phone'     => 'nullable|string|max:30',
            'whatsapp_number'   => 'nullable|string|max:30',
            'buy_me_coffee_url' => 'nullable|url|max:255',
            'logo'              => 'nullable|image|mimes:jpeg,png,webp,svg|max:2048',
        ]);

        foreach ([
            'site_name', 'tagline', 'contact_email', 'contact_phone',
            'whatsapp_number', 'buy_me_coffee_url',
        ] as $key) {
            SiteSetting::set($key, $request->input($key, ''));
        }

        $logoPath = SiteSetting::get('logo_path', '');

        if ($request->hasFile('logo')) {
            if ($logoPath && Storage::disk('public')->exists(str_replace('/storage/', '', $logoPath))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $logoPath));
            }
            $path = $request->file('logo')->store('logos', 'public');
            SiteSetting::set('logo_path', Storage::url($path));
        } elseif ($request->boolean('remove_logo')) {
            if ($logoPath && Storage::disk('public')->exists(str_replace('/storage/', '', $logoPath))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $logoPath));
            }
            SiteSetting::set('logo_path', '');
        }

        return back()->with('success', 'Site settings updated successfully.');
    }
}