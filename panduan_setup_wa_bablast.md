# Panduan Setup WhatsApp Notifikasi via Bablast.id (WABA Meta API)

Integrasi telah menggunakan Endpoint resmi Meta dan Bablast API. Ikuti tahapan di bawah ini untuk menghubungkan sistem:

## 1. Setup di Meta for Developer (Webhook)
Karena Anda telah memiliki Akun WABA aktif dan terdaftar, sekarang masuk ke aplikasi Anda di [Meta for Developers](https://developers.facebook.com/).

1. Klik menu **WhatsApp** -> **Configuration** (Konfigurasi).
2. Temukan bagian **Webhooks** dan klik **Edit** (atau Configure).
3. Isi parameter berikut:
   - **Callback URL:** Isi dengan URL aplikasi Anda yang dapat diakses publik ditambah `/webhook/whatsapp` (Contoh: `https://domain-anda.com/webhook/whatsapp`).
   - **Verify Token:** Isi dengan *secret token* Anda. Secara default, sistem menggunakan kata kunci: `smk_bpa_webhook_secret`. (Anda dapat mengubah ini di `.env` dengan menambahkan `BABLAST_WEBHOOK_VERIFY_TOKEN`).
4. Klik **Verify and Save**. (Meta akan mengirim request GET ke sistem kita dan jika `smk_bpa_webhook_secret` cocok, Meta akan menyetujui webhook-nya).
5. Pada *Webhook Fields*, klik **Manage** dan centang/subscribe bagian **messages** agar notifikasi status pengiriman dan balasan pesan dikirim ke sistem.

## 2. Setup di Bablast.id (Mendapatkan Kredensial)
1. Login ke dashboard Bablast.id Anda ([https://dash.bablast.id/sender](https://dash.bablast.id/sender)).
2. Pada nomor WhatsApp Business API (WABA) yang telah berstatus *Terhubung*, klik tombol **Developer** (seperti pada screenshot) atau ikon setting API.
3. Anda akan mendapatkan 2 buah kunci utama, yaitu:
   - **API Token**
   - **Sender ID** (Kode pengirim/devices Anda, contoh: `PJC35W82`)
4. Copy kedua kunci tersebut.

## 3. Konfigurasi di Dashboard Admin Sistem
Setelah database di-*migrate* (`php artisan migrate`):
1. Login ke Dashboard Sistem SPMB SMK Bhakti Praja Adiwerna.
2. Masuk ke menu **Pengaturan** (Settings).
3. Buka tab **WhatsApp**.
4. Akan muncul field baru:
   - **Bablast API Token:** Tempelkan *API Token* dari langkah ke-2.
   - **Bablast Sender ID:** Tempelkan *Sender ID* dari langkah ke-2.
5. Pastikan status **Aktif**. Klik Simpan.
6. Anda bisa menggunakan tombol **Test Koneksi WhatsApp** untuk mengirimkan pesan ujicoba langsung ke nomor tujuan Anda.

Selamat, integrasi WhatsApp Business API WABA telah aktif!
