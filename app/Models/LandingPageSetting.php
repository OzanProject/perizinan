<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageSetting extends Model
{
  use HasFactory;

  protected $table = 'landing_page_settings';

  protected $fillable = [
    'dinas_id',
    'hero_title',
    'hero_subtitle',
    'hero_image',
    'support_text',
    'support_agents_count',
    'stats_izin_aktif',
    'stats_proses_bulan_ini',
    'support_agents_count',
    'license_title',
    'license_subtitle',
    'license_description',
    'license_types',
    'faq_title',
    'faq_subtitle',
    'faq',
    'contact_phone',
    'contact_email',
    'contact_address',
    'footer_description',
    'show_login_button',
    'meta_description',
    'meta_keywords',
    'cta_primary_text',
    'cta_primary_url',
    'cta_secondary_text',
    'cta_secondary_url',
    'google_maps_embed',
    'social_facebook',
    'social_instagram',
    'social_twitter',
    'social_youtube',
  ];

  protected $casts = [
    'license_types' => 'array',
    'faq' => 'array',
    'show_login_button' => 'boolean',
    'stats_izin_aktif' => 'integer',
    'stats_proses_bulan_ini' => 'integer',
    'stats_rata_hari' => 'integer',
  ];

  public function dinas()
  {
    return $this->belongsTo(Dinas::class);
  }
}
