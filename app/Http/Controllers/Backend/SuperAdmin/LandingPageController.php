<?php

namespace App\Http\Controllers\Backend\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
  public function index()
  {
    $dinas = Auth::user()->dinas;
    $setting = LandingPageSetting::firstOrCreate(['dinas_id' => $dinas->id]);

    return view('backend.super_admin.landing_page.index', compact('setting'));
  }

  public function update(Request $request)
  {
    $dinas = Auth::user()->dinas;
    $setting = LandingPageSetting::where('dinas_id', $dinas->id)->first();

    $request->validate([
      'hero_title' => 'required|string|max:255',
      'hero_subtitle' => 'nullable|string',
      'support_text' => 'nullable|string|max:255',
      'track_placeholder' => 'nullable|string|max:255',
      'license_title' => 'nullable|string|max:255',
      'license_subtitle' => 'nullable|string|max:255',
      'license_description' => 'nullable|string',
      'faq_title' => 'nullable|string|max:255',
      'faq_subtitle' => 'nullable|string|max:255',
      'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
      'stats_izin_aktif' => 'required|integer',
      'stats_proses_bulan_ini' => 'required|integer',
      'stats_rata_hari' => 'required|integer',
      'contact_phone' => 'nullable|string',
      'contact_email' => 'nullable|email',
      'contact_address' => 'nullable|string',
      'footer_description' => 'nullable|string',
      'meta_description' => 'nullable|string',
      'meta_keywords' => 'nullable|string',
      'google_maps_embed' => 'nullable|string',
      'social_facebook' => 'nullable|url|max:255',
      'social_instagram' => 'nullable|url|max:255',
      'social_twitter' => 'nullable|url|max:255',
      'social_youtube' => 'nullable|url|max:255',
    ]);

    $data = $request->except(['hero_image', 'faq', 'license_types']);

    if ($request->hasFile('hero_image')) {
      if ($setting->hero_image) {
        Storage::disk('public')->delete($setting->hero_image);
      }
      $data['hero_image'] = $request->file('hero_image')->store('landing_page', 'public');
    }

    // Handle JSON fields (simple implementation for now, can be improved with dynamic JS in frontend)
    $data['faq'] = $request->faq ?? [];
    $data['license_types'] = $request->license_types ?? [];

    $setting->update($data);

    return back()->with('success', 'Pengaturan landing page berhasil diperbarui.');
  }
}
