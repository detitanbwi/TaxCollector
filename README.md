# Sistem Penagihan Pajak PBB Digital

Aplikasi ini adalah Sistem Penagihan Pajak PBB Digital berbasis web yang menggunakan Laravel (versi terbaru) dan Tailwind CSS. Aplikasi ini memiliki dua antarmuka utama:
1. **Admin Panel** (Desktop Optimized): Digunakan oleh admin untuk mengelola pengguna (akun penagih) dan mengelola data tagihan pajak (termasuk fitur impor massal via Excel).
2. **Portal Penagih** (Mobile-First): Digunakan oleh petugas penagih di lapangan untuk mencari tagihan berdasarkan Nomor Polisi (Nopol) dan mengirimkan pemberitahuan tagihan langsung ke WhatsApp warga.

## Syarat Sistem (System Requirements)
- PHP >= 8.2
- Composer
- Node.js & npm (untuk proses build aset frontend)
- Ekstensi PHP: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, ext-zip (dibutuhkan untuk Maatwebsite Excel)

## Langkah-langkah Instalasi & Deployment Lokal

1. **Clone/Copy Project**  
   Pindahkan folder proyek ini ke direktori web server lokal Anda (misal: `xampp/htdocs`).

2. **Instalasi Dependensi PHP (Composer)**  
   Buka terminal di root direktori proyek, lalu jalankan:
   ```bash
   composer install
   ```

3. **Instalasi Dependensi Node (NPM)**  
   ```bash
   npm install
   ```

4. **Konfigurasi Lingkungan (.env)**  
   Gandakan file `.env.example` dan ubah namanya menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   *Secara default, proyek ini telah disesuaikan untuk menggunakan MySQL dengan nama database `db_tagih_pajak` di `.env`. Pastikan server MySQL lokal Anda (seperti XAMPP MySQL) telah aktif sebelum menjalankan migrasi.*

5. **Generate Application Key**  
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder Database**  
   Langkah ini akan membuat tabel-tabel yang dibutuhkan dan membuat akun demo secara otomatis:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build Aset Frontend (Tailwind CSS)**  
   Lakukan *build* untuk mengkompilasi file Tailwind CSS:
   ```bash
   npm run build
   ```
   *(Untuk mode development (pengembangan) yang secara otomatis akan *re-build* ketika ada perubahan, Anda dapat menggunakan perintah `npm run dev`)*.

8. **Jalankan Aplikasi**  
   Mulai *development server*:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di browser melalui URL: `http://localhost:8000`

---

## Akun Demo

Aplikasi ini telah menyediakan akun demo bawaan setelah Anda menjalankan seeder.

| Peran | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `password` | Memiliki akses penuh ke panel admin, manajemen data pajak dan pengguna. |
| **Penagih** | `penagih1` | `password` | Mengakses tampilan *mobile-first* untuk mencari dan menagih pajak. |

---

## Panduan Penggunaan Modul Excel

- Pastikan Anda menggunakan file berformat `.xlsx` atau `.csv`.
- Format struktur kolom *header* harus berada pada baris pertama dan mencakup judul kolom berikut (tidak sensitif huruf kapital):
  `nopol`, `nama_pemilik`, `alamat`, `nominal`
- Proses *import* akan secara cerdas menambah data baru, dan tidak akan melakukan duplikasi data pada Nopol yang sama.
