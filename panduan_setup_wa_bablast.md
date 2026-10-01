# Panduan Setup WhatsApp Notifikasi via Bablast.id (WABA Meta API)

Integrasi telah menggunakan Endpoint resmi Meta dan Bablast API. Ikuti tahapan di bawah ini untuk menghubungkan sistem:



## 2. Setup di Bablast.id (Mendapatkan Kredensial)
1. Login ke dashboard Bablast.id Anda ([https://dash.bablast.id/sender](https://dash.bablast.id/sender)).
2. Pada nomor WhatsApp Business API (WABA) yang telah berstatus *Terhubung*, klik tombol **Developer** (atau ikon setting API).
3. Anda akan mendapatkan sebuah **Secret Token** (Contoh: `ctF0t7IY5awLBK...`).
4. Copy token tersebut.

## 3. Konfigurasi di Dashboard Admin Sistem
Setelah database di-*migrate* (`php artisan migrate`):
1. Login ke Dashboard Sistem SPMB SMK Bhakti Praja Adiwerna.
2. Masuk ke menu **Pengaturan** (Settings).
3. Buka tab **WhatsApp**.
4. Akan muncul field baru:
   - **Bablast API Token:** Tempelkan *Secret Token* dari langkah ke-2.
5. Pastikan status **Aktif**. Klik Simpan.
6. Anda bisa menggunakan tombol **Test Koneksi WhatsApp** untuk mengirimkan pesan ujicoba langsung ke nomor tujuan Anda.

**Catatan WABA:**
Pesan teks biasa (Free-text) via WABA hanya bisa dikirim dalam Jendela 24 Jam (ketika calon siswa membalas pesan Anda terlebih dahulu). Jika di luar jendela tersebut, sistem WABA memerlukan pengiriman *Template Message*. Pastikan Anda telah mensetting Template WABA Anda di dashboard Bablast.id jika ingin notifikasi selalu terkirim tanpa batasan waktu balas.

Selamat, integrasi WhatsApp Business API WABA telah aktif!
