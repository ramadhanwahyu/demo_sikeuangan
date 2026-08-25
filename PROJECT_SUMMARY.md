# Keuangan Pesantren v2 - Project Summary

## Stack Teknis
- Laravel 12
- Database: SQLite
- Auth: Session-based (Laravel default)
- CSS: Tailwind CSS (via CDN sementara)

## Role & Hak Akses
| Role | Hak Akses |
|------|-----------|
| admin | Kelola semua data, kelola user |
| bendahara | Kelola data sesuai tingkat (SMP/SMA) |
| pimpinan | Lihat laporan semua tingkat |
| ortu | Lihat data anak (SPP & uang jajan) |

## Struktur Database
### Tabel Utama
- **tingkat**: id, nama (SMP/SMA)
- **users**: id, nama, email, password, role, tingkat_id, no_hp
- **kelas**: id, nama, tingkat_id, urutan
- **tahun_ajaran**: id, nama, is_active
- **santri**: id, nis, nama_lengkap, tanggal_lahir, alamat, tingkat_id, kelas_id, tahun_masuk, status, saldo_uang_jajan
- **kategori_transaksi**: id, nama, jenis (pemasukan/pengeluaran), is_uang_jajan, is_sistem
- **transaksi_kas**: id, tanggal, kategori_id, jenis, jumlah, keterangan, santri_id, tingkat_id, user_id
- **spp_rates**: id, tahun_ajaran_id, tingkat_id, kelas_id, nominal
- **spp_bills**: id, santri_id, tahun_ajaran_id, bulan, nominal, status, tanggal_bayar, transaksi_kas_id
- **wali_santri**: id, user_id, santri_id, hubungan

### Kategori Default
- **Pemasukan**: SPP, Uang Pendaftaran, Uang Ujian, Pemasukan Lainnya, Uang Jajan Santri
- **Pengeluaran**: Gaji Guru, ATK, Listrik, WiFi, PDAM, Dapur, Uang Jajan Santri, Lainnya

## Fitur Selesai
- [x] Migration & struktur database
- [x] Models dengan relasi
- [x] Seeders (tingkat, kelas, tahun ajaran, kategori, user)
- [x] Autentikasi (login/logout)
- [x] Dashboard per role (bendahara sudah lengkap dengan shortcut & ringkasan)
- [x] Middleware CheckRole
- [x] Migration sessions table & cache
- [x] Layout utama (app.blade.php) dengan navigasi
- [x] CRUD Santri (dengan filter tingkat & kelas, otorisasi bendahara)
- [x] CRUD Transaksi Kas (dengan filter, otorisasi tingkat, pemisahan kas umum & uang jajan)
- [x] Fitur Saldo Uang Jajan Santri (daftar saldo, detail transaksi, shortcut tambah/tarik)
- [x] Pemisahan transaksi kas umum dan uang jajan santri (tab terpisah, saldo terpisah)

## Fitur Berikutnya
- [ ] CRUD Kategori Transaksi (kustom)
- [ ] Modul SPP (rates, generate tagihan, pembayaran)
- [ ] Laporan keuangan (per periode, per tingkat)
- [ ] Manajemen User (admin)
- [ ] Portal Ortu (pantau SPP & uang jajan)
- [ ] Kelola Tahun Ajaran
- [ ] Filter kelas berdasarkan tingkat di form santri (jika diperlukan)
- [ ] Integrasi CSS framework yang lebih permanen (Vite, dsb)

## Akun Default (Seeder)
- admin@pesantren.test / password123 (Admin)
- bendahara.smp@pesantren.test / password123 (Bendahara SMP)
- bendahara.sma@pesantren.test / password123 (Bendahara SMA)
- pimpinan@pesantren.test / password123 (Pimpinan)
- ortu@pesantren.test / password123 (Orang Tua)

## Catatan Penting
- Pemisahan data SMP/SMA via `tingkat_id`
- Bendahara hanya dapat mengelola data sesuai tingkatnya (otorisasi penuh di controller)
- Uang jajan santri tercatat di transaksi_kas dengan kategori khusus (`is_uang_jajan = true`)
- Saldo uang jajan santri di-cache di tabel `santri.saldo_uang_jajan`
- Transaksi kas dipisah menjadi Kas Umum dan Uang Jajan Santri (via query param `tipe`)
- Satu tahun ajaran aktif, lainnya otomatis non-aktif (perlu implementasi toggle)
- Form transaksi: kategori difilter otomatis berdasarkan jenis, tingkat dikunci untuk bendahara
- Cache & session menggunakan file/database (pastikan konfigurasi benar)