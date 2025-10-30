# ArtCore – User Flows & Rental Scheme

## 1. Guest → User → Admin Flow

### Guest Flow
- **Landing Page** (`GET /`, `HomeController@index`, `resources/views/home.blade.php`)  
  - Melihat hero, highlight, dan katalog paginasi (`units/_grid.blade.php`).  
  - Akses filter → hasil tetap menampilkan unit tersedia.  
  - Klik unit → diarahkan ke `route('login')` bila belum login.  
- **Registrasi/Login** (`routes/auth.php`, `RegisteredUserController`, `AuthenticatedSessionController`)  
  - Guest menekan **Daftar** atau **Login** dari navbar (`layouts/navigation.blade.php`).  
  - Setelah sukses login, diarahkan ke beranda dengan nav khusus user.

### User Flow
- **Dashboard User** (`resources/views/user/dashboard.blade.php`)  
  - Menampilkan ringkasan sewa aktif, shortcut ke profil/keranjang.  
- **Menjelajah Katalog / Detail** (`units/index.blade.php`, `units/show.blade.php`)  
  - User login dapat menambah unit ke keranjang via AJAX `POST /cart/add`.  
- **Keranjang** (`user/cart.blade.php`, `RentalController@cart`)  
  - Validasi: slot maksimal 2, deposit 30% untuk vintage 60s/70s (via `PricingService`).  
  - Checkout (`POST /rentals`, `RentalController@store`) membuat rental ACTIVE dan mengunci ketersediaan unit.  
- **Sewa Aktif** (`rentals/index.blade.php`)  
  - Melihat daftar sewa, TPO (Trial-to-Own) (`POST /rentals/{rental}/purchase`), ajukan pengembalian (`POST /rentals/{rental}/return-request`).  
- **Riwayat Pembelian / Profil** (`user/purchases.blade.php`, `profile/edit.blade.php`)  
  - Meninjau unit yang dibeli, memperbarui data pribadi & password.

### Admin Flow
- **Login Admin** (`AdminUserSeeder` menyediakan akun awal).  
- **Dashboard Admin** (`admin/dashboard.blade.php`, `Admin\DashboardController@index`)  
  - Lihat statistik, unit terbaru, anggota baru, status unit tersedia/disewa/sold.  
- **Manajemen Data**  
  - Unit (`admin/units/*.blade.php`, `UnitAdminController`) – tambah/edit/hapus, upload foto, tandai SOLD.  
  - Kategori (`admin/categories/*.blade.php`, `CategoryAdminController`).  
  - User (`admin/users/*.blade.php`, `UserAdminController`) – ubah role admin, lihat riwayat sewa singkat.  
- **Transaksi Sewa**  
  - Daftar sewa (`admin/rentals/index.blade.php`) dan detail (`show.blade.php`).  
  - Konfirmasi pengembalian (`admin/returns/*.blade.php`, `ReturnAdminController@confirm`) – menghitung denda dan memotong deposit.  
  - Riwayat & ekspor (`admin/rentals/history.blade.php`, `RentalAdminController@history/exportHistory`).

---

## 2. Rental Scheme (Minimal vs. Custom Enhancements)

### A. Skema Minimal (Requirement Wajib)
1. **Jenis anggota** : `users.is_admin` membedakan admin/user (migration `2025_10_29_013531_add_is_admin_to_users.php`).  
2. **Login wajib** : Semua rute katalog/checkout berada dalam grup `Route::middleware(['auth'])`.  
3. **User harus terdaftar** : Registrasi di `RegisteredUserController` sebelum menyewa.  
4. **Satu user satu profil** : Tabel `users` (migration `0001_01_01_000000_create_users_table.php`), update lewat `ProfileController@update`.  
5. **Halaman profil pengguna** : View `profile/edit.blade.php`.  
6. **Unit satu kategori** : Kolom `units.category_id` (migration `2025_10_29_013527_create_units_table.php`) + relasi `Unit::category()`.  
7. **Unit bisa memiliki nama sama, kode unik** : Validasi `StoreUnitRequest` / `UpdateUnitRequest`.  
8. **Pencarian** : `UnitController@index` & `HomeController@index` menerima parameter `s` (nama/kode).  
9. **Admin CRUD** : Controller `UnitAdminController`, `CategoryAdminController`, `UserAdminController` menangani tambah, update, hapus data.  
10. **Limit 2 unit per user** : `RentalController@store` & `@addToCart` menghitung slot (Sculpture = 2).  
11. **Pinjaman maksimal 5 hari + denda** : `RentalController@store` set `rental_end_plan = start+5`. Denda dihitung di `PricingService::calcLateFee()` (10%).  
12. **Hanya admin yang mengembalikan** : User hanya `requestReturn`; admin proses via `ReturnAdminController@confirm`.  
13. **Admin lihat list unit dipinjam** : `Admin\RentalAdminController@index` dan dashboard card sewa aktif.  
14. **User lihat list sewa sendiri** : `RentalController@index`.  
15. **Admin melihat & mencetak riwayat** : `RentalAdminController@history`, `@exportHistory`.  
16. **Validasi field** : Requests (`StoreRentalRequest`, `StoreUnitRequest`, `UpdateUnitRequest`, `ReturnConfirmRequest`).  
17. **Migrasi** : Seluruh tabel di folder `database/migrations`.  
18. **Seeder awal** : `CategorySeeder`, `UnitSeeder`, `AdminUserSeeder`, `DatabaseSeeder`.

### B. Skema Khusus ArtCore
- **Deposit otomatis 30%** untuk unit vintage 60s/70s – dihitung oleh `PricingService::calcDeposit`, disimpan sebagai `deposit_required` & `deposit_paid`.  
- **Trial-to-Own 5 hari** – User dapat membeli unit sewaan selama window 5 hari (`RentalController@purchase`) → status `PURCHASED`, `unit.is_sold=true`.  
- **Status badge & ordering** – `Unit::scopeStatusOrdering()` memastikan listing menampilkan “Tersedia → Tidak Tersedia → SOLD”.  
- **Admin Dashboard Insights** – Statistik (total unit, deposit, return request) dan quick-action cards.  
- **AJAX Keranjang** – Behaviour `.js-add-to-cart` dengan response JSON, menampilkan alert bila validasi gagal (slot, deposit, duplicate).  
- **Denda multi jenis** – Form admin untuk menambah `cleaning_fee` & `damage_fee` selain denda telat otomatis.  
- **Export CSV** – Laporan sewa dapat diunduh untuk kebutuhan administrasi.  
- **UI konsisten** – Input `brand-input` (hitam) untuk form admin/user agar sesuai desain tema ArtCore.

