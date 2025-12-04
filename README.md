# 1. Nama Project
## Carikost.Id

## 2. Nama Kelompok & Anggota
| No | Nama Lengkap | NIM | Jobdesk |
|----|--------------|-----|-------|
| 1  | Dewi Rahayu     | 701230015 | Dokumentasi Design, Deployment ke hosting, UI/UX Design |
| 2  | Intan Rhamadani     | 701230074 | Membuat Activity Diagram, Use Case Diagram, UI/UX Design, Front End |
| 3  | Soli Amalia Rahmadhani     | 701230075 | Membuat Backlog, UI/UX Design, Slide PPT, Back End |

## 3. Deskripsi Singkat Aplikasi
Carikost.id adalah aplikasi (web) untuk mempermudah pencarian dan manajemen informasi kost. Pengguna dapat mencari kost berdasarkan lokasi, harga, fasilitas, dan melihat detail unit serta pemilik kost.

## 4. Tujuan Sistem / Permasalahan yang Diselesaikan
- Memudahkan pencarian tempat tinggal (kost) bagi mahasiswa/pekerja.
- Menyediakan informasi lengkap (foto, fasilitas, harga, kontak) sehingga mengurangi kebutuhan kunjungan awal.
- Memberikan fitur manajemen listing bagi pemilik kost untuk mengelola unit dan pemesanan.
- Mengatasi kesulitan pencarian kost yang tersebar di berbagai platform tanpa standar informasi.

## 5. Teknologi yang Digunakan
- Bahasa Pemrograman: PHP (Native) versi 8.0 ke atas.
- Database: Firebase Realtime Database (NoSQL).
- Library PHP: kreait/firebase-php (Untuk koneksi ke Firebase).
- Frontend: HTML, CSS (Custom Style), JavaScript (Firebase Web SDK).

## 6. Cara Menjalankan Aplikasi
### 1) Persiapan & Instalasi
- Pastikan di komputer sudah terinstall PHP dan Composer.
- Download/Clone folder proyek ini.
- Buka terminal/CMD di dalam folder proyek, lalu jalankan perintah berikut untuk menginstall library Firebase:
    ```bash
  composer require kreait/firebase-php
  ```
- Buat folder baru bernama uploads di dalam folder proyek (untuk menyimpan gambar secara lokal).
### 2) Konfigurasi Database
- Pastikan file firebase_key.json (Service Account dari Firebase Console) sudah diletakkan di dalam folder root proyek.
- Buka file db.php dan pastikan URL database sudah sesuai dengan project Firebase Anda.
- Pastikan Rules di Firebase Realtime Database sudah diatur ke true (read/write).
### 3) Cara Menjalankan (Run Project)
- Pastikan di komputer sudah terinstall PHP dan Composer.
- Download/Clone folder proyek ini.
- Dengan built-in PHP server (untuk development cepat):
  ```bash
  php -S localhost:8000
  ```
- Lalu buka http://localhost:8000 pada browser (sesuaikan path jika index berada di subfolder)

## 7. Akun Demo
- Admin:
  - username/email: admin@gmail.com
  - password: 123456789
- Pengguna (Penyewa):
  - username/email: lmhsunny02@gmail.com
  - password: 12345678
- Pengguna (Pemilik):
  - username/email: rosalesya@gmail.com
  - password: 87654321
 
## 8. Link Deployment
 - ## link Deployment: https://carikost-id.vercel.app/](http://carikost.infinityfree.me
 
## 9. Screenshot Halaman Utama
  <img width="1918" height="939" alt="image" src="https://github.com/user-attachments/assets/3d4ee384-050d-42a0-8885-ab66ccd3aebc" />
  <img width="1919" height="945" alt="image" src="https://github.com/user-attachments/assets/3654ba83-830b-41bb-bdac-981ce8de54a9" />

## 10. Catatan Tambahan
### 1) Keterbatasan Sistem (Penyimpanan Gambar):
- Karena menggunakan paket Firebase Gratis (Spark Plan), fitur Firebase Storage tidak dapat digunakan tanpa kartu kredit. Oleh karena itu, sistem ini menggunakan Penyimpanan Lokal (Local Storage).
- Gambar yang diupload akan disimpan di folder uploads/ pada laptop/server tempat aplikasi dijalankan.
- Jika folder uploads/ dihapus, gambar pada website akan hilang.

### 2) Fitur Realtime:
- Fitur Chat dan Notifikasi Pesanan berjalan secara realtime menggunakan JavaScript. Pastikan perangkat terhubung ke internet saat menjalankan aplikasi agar fitur ini berfungsi.

### 3) Keamanan:
- Sistem reset password menggunakan verifikasi data (Email & No HP) karena keterbatasan pengiriman email (SMTP) di lingkungan localhost.

## 11. Keterangan Tugas
Project ini dibuat untuk memenuhi tugas Final Project mata kuliah Rekayasa Perangkat Lunak.  
- ### Mata Kuliah: Rekayasa Perangkat Lunak  
- ### Dosen Pengampu: Dila Nurlaila, M.Kom
