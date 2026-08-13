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
                return in_array($setting->key, ['logo', 'nama_sekolah', 'tagline_sekolah', 'landing_hero', 'deskripsi_hero', 'tahun_ajaran', 'brosur']);
            }),
            'Jadwal' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'jadwal_');
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
                return in_array($setting->key, ['fonnte_token', 'template_pesan_pendaftaran', 'template_pesan', 'wa_pesan_ingatkan', 'wa_pesan_daftar_ulang', 'wa_pesan_kelulusan']);
            }),
            'Midtrans' => $settings->filter(function($setting) {
                return str_starts_with($setting->key, 'midtrans_');
            }),
            'Lainnya' => $settings->filter(function($setting) {
                return !in_array($setting->key, ['logo', 'nama_sekolah', 'tagline_sekolah', 'landing_hero', 'deskripsi_hero', 'tahun_ajaran', 'brosur', 'fonnte_token', 'template_pesan_pendaftaran', 'template_pesan', 'wa_pesan_ingatkan', 'wa_pesan_daftar_ulang', 'wa_pesan_kelulusan']) 
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

    public function testFonnte(Request $request)
    {
        $request->validate([
            'test_nomor' => 'required|string'
        ]);

        $message = "Ini adalah pesan percobaan dari sistem SPMB SMK Bhakti Praja Adiwerna.\nJika Anda menerima pesan ini, koneksi API Fonnte telah berhasil.";
        
        $success = \App\Services\WhatsAppService::sendMessage($request->test_nomor, $message);

        if ($success) {
            return back()->with('success', 'Pesan percobaan berhasil dikirim ke ' . $request->test_nomor);
        } else {
            return back()->with('error', 'Gagal mengirim pesan percobaan. Pastikan token Fonnte aktif dan kuota mencukupi.');
        }
    }
}