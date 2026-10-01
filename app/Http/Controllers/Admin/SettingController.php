<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('name')->get();
        
        // Group settings by category
        $groupedSettings = [
            'Landing Page' => $settings->filter(function($setting) {
                return in_array($setting->key, ['logo', 'nama_sekolah', 'tagline_sekolah', 'landing_hero', 'deskripsi_hero', 'tahun_ajaran', 'brosur', 'singkatan_sekolah', 'alamat_sekolah', 'telepon_sekolah', 'email_sekolah', 'pengumuman_nama_kepsek', 'pengumuman_nip_kepsek']);
            })->sortBy(function($setting) {
                $order = [
                    'nama_sekolah' => 1,
                    'singkatan_sekolah' => 2,
                    'tagline_sekolah' => 3,
                    'alamat_sekolah' => 4,
                    'email_sekolah' => 5,
                    'telepon_sekolah' => 6,
                    'logo' => 7,
                    'landing_hero' => 8,
                    'deskripsi_hero' => 9,
                    'brosur' => 10,
                    'tahun_ajaran' => 11,
                    'pengumuman_nama_kepsek' => 12,
                    'pengumuman_nip_kepsek' => 13
                ];
                return $order[$setting->key] ?? 99;
            }),
            'Jadwal' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'jadwal_') || in_array($setting->key, ['pengumuman_tgl_mpls', 'pengumuman_tgl_masuk']);
            }),
            'Kontak' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'kontak_');
            }),
            'Alur Pendaftaran' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'alur_') && !str_starts_with($setting->key, 'alur_daftar_ulang_');
            }),
            'Alur Daftar Ulang' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'alur_daftar_ulang_');
            }),
            'WhatsApp' => $settings->filter(function($setting) {
                return in_array($setting->key, ['wa_status', 'bablast_api_token', 'template_pesan_pendaftaran', 'template_pesan', 'wa_pesan_ingatkan', 'wa_pesan_daftar_ulang', 'wa_pesan_kelulusan', 'wa_pesan_tagihan_daftar_ulang', 'wa_pesan_pembayaran_sukses']);
            })->sortBy(function($setting) {
                $order = ['bablast_api_token' => 1, 'wa_status' => 2, 'template_pesan_pendaftaran' => 3, 'wa_pesan_ingatkan' => 4, 'wa_pesan_daftar_ulang' => 5, 'wa_pesan_kelulusan' => 6, 'wa_pesan_tagihan_daftar_ulang' => 7, 'wa_pesan_pembayaran_sukses' => 8, 'template_pesan' => 9];
                return $order[$setting->key] ?? 99;
            }),
            'Midtrans' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'midtrans_');
            }),
            'Tanda Tangan & Surat' => $settings->filter(function($setting) {
                return in_array($setting->key, ['pengumuman_ttd_kepsek', 'pengumuman_ttd_ketua', 'kwitansi_ttd_panitia', 'kwitansi_stempel_panitia']);
            }),
            'Lainnya' => $settings->filter(function($setting) {
                return !in_array($setting->key, ['logo', 'nama_sekolah', 'tagline_sekolah', 'landing_hero', 'deskripsi_hero', 'tahun_ajaran', 'brosur', 'wa_status', 'bablast_api_token', 'template_pesan_pendaftaran', 'template_pesan', 'wa_pesan_ingatkan', 'wa_pesan_daftar_ulang', 'wa_pesan_kelulusan', 'wa_pesan_tagihan_daftar_ulang', 'wa_pesan_pembayaran_sukses', 'pengumuman_ttd_kepsek', 'pengumuman_ttd_ketua', 'kwitansi_ttd_panitia', 'kwitansi_stempel_panitia', 'pengumuman_tgl_mpls', 'pengumuman_tgl_masuk', 'singkatan_sekolah', 'alamat_sekolah', 'telepon_sekolah', 'email_sekolah', 'pengumuman_nama_kepsek', 'pengumuman_nip_kepsek']) 
                    && !str_starts_with($setting->key, 'jadwal_') 
                    && !str_starts_with($setting->key, 'kontak_')
                    && !str_starts_with($setting->key, 'alur_')
                    && !str_starts_with($setting->key, 'midtrans_');
            })
        ];
        
        return view('admin.settings.index', compact('groupedSettings', 'settings'));
    }

    public function update(Request $request)
    {
        // Get all file settings
        $fileSettings = Setting::where('type', 'file')->get();
        
        // Build validation rules for file uploads
        $rules = [];
        foreach ($fileSettings as $setting) {
            if ($request->hasFile($setting->key)) {
                // Brosur bisa PDF atau image
                if ($setting->key === 'brosur') {
                    $rules[$setting->key] = 'mimes:pdf,png,jpg,jpeg|max:5120'; // 5MB untuk PDF
                } else if ($setting->key === 'landing_hero') {
                    $rules[$setting->key . '.*'] = 'image|mimes:png,jpg,jpeg|max:2048';
                } else {
                    $rules[$setting->key] = 'image|mimes:png,jpg,jpeg|max:2048';
                }
            }
        }
        
        // Validate if there are file uploads
        if (!empty($rules)) {
            $request->validate($rules);
        }
        
        $data = $request->except('_token', '_method');
        
        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }
            
            // Handle file uploads
            if ($setting->type === 'file' && $request->hasFile($key)) {
                if ($key === 'landing_hero') {
                    $files = $request->file($key);
                    $paths = [];
                    
                    // Delete old files if they exist
                    $oldFiles = json_decode($setting->value, true);
                    if (is_array($oldFiles)) {
                        foreach ($oldFiles as $oldPath) {
                            if (Storage::disk('public')->exists($oldPath)) {
                                Storage::disk('public')->delete($oldPath);
                            }
                        }
                    } else if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    // Store new files
                    foreach ($files as $index => $file) {
                        $filename = $key . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                        $paths[] = $file->storeAs('settings', $filename, 'public');
                    }
                    
                    $setting->update(['value' => json_encode($paths)]);
                } else {
                    $file = $request->file($key);
                    
                    // Delete old file if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    
                    // Store new file
                    $filename = $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('settings', $filename, 'public');
                    
                    $setting->update(['value' => $path]);
                }
            } else if ($setting->type !== 'file') {
                // Update non-file settings
                $setting->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function testWhatsapp(Request $request)
    {
        $request->validate([
            'test_nomor'    => 'required|string',
            'test_template' => 'required|string|alpha_dash',
        ]);

        $templateName = $request->test_template;
        $language     = $templateName === 'hello_world' ? 'en_US' : 'id';

        $success = \App\Services\WhatsAppService::sendTemplate(
            $request->test_nomor,
            $templateName,
            [], // Test tanpa parameter
            $language
        );

        if ($success) {
            return back()->with('success', "Pesan test template '{$templateName}' berhasil dikirim ke {$request->test_nomor}.");
        } else {
            return back()->with('error', "Gagal mengirim template '{$templateName}'. Pastikan: (1) API Token benar, (2) Template sudah APPROVED di Meta, (3) Nomor valid. Cek log Laravel untuk detail error.");
        }
    }
}


