# ArtCore Page Overview & File Map

Dokumen ini memetakan setiap halaman utama pada aplikasi ArtCore ke file Blade / controller / route yang  menangani logikanya.

---

## 1. Landing Page (Guest)

- **View utama** : `resources/views/home.blade.php`
- **Controller** : `app/Http/Controllers/Web/HomeController.php@index`
- **Route** : `GET /` (`name: home`) – `routes/web.php:8`
- **Layout & Navbar** : `resources/views/layouts/app.blade.php`, partial `resources/views/layouts/navigation.blade.php`
- **Komponen katalog** : `resources/views/units/_grid.blade.php`
- **Fungsi utama**
  - Hero CTA menuju `#katalog` dan `route('register')`
  - Form filter katalog (query string, auto-scroll via script di layout)
  - Carousel sorotan (`$highlights`)
  - Grid katalog paginasi (`$units`)
  - Guest yang klik unit diarahkan ke `route('login')`

---

## 2. Halaman Katalog Lengkap

- **View** : `resources/views/units/index.blade.php`
- **Controller** : `UnitController@index` (`app/Http/Controllers/Web/UnitController.php`)
- **Route** : `GET /units` (`name: units.index`) – `routes/web.php:17`
- **Komponen** : Re-use `units/_grid.blade.php`
- **Fungsi**
  - Filter/search dengan query string
  - Paginasi 18 unit/per page
  - Status badge & add-to-cart behavior sama seperti landing

---

## 3. Detail Unit

- **View** : `resources/views/units/show.blade.php`
- **Controller** : `UnitController@show`
- **Route** : `GET /units/{unit}` (`name: units.show`)
- **Fungsi**
  - Galeri gambar, info penjualan & sewa
  - Tombol sewa (POST `/rentals`) bila user login
  - Guest diarahkan ke login

---

## 4. Autentikasi

- **Views** : `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`
- **Routes** : Dideklarasikan di `routes/auth.php` (Laravel Breeze/Fortify)
- **Controllers** :
  - `AuthenticatedSessionController` – login/logout
  - `RegisteredUserController` – registrasi & otomatis login
- **Workflow**
  - Guest klik navbar `Daftar/Login` → form sesuai
  - Setelah login, middleware `auth` mengizinkan akses ke rute user/admin

---

## 5. Dashboard User

- **View** : `resources/views/user/dashboard.blade.php`
- **Controller** : `HomeController@index` → redirect setelah login user
- **Route** : `GET /dashboard` (`name: dashboard`) – default Breeze
- **Fungsi**
  - Sapaan, ringkasan sewa aktif (query di `RentalController@index`)
  - Link cepat ke profil & sewa aktif

---

## 6. Keranjang

- **View** : `resources/views/user/cart.blade.php` & `user/cart-empty.blade.php`
- **Controller** : `RentalController@cart`, `@addToCart`, `@removeFromCart`
- **Routes** :
  - `GET /cart` (`name: cart`)
  - `POST /cart/add`
  - `DELETE /cart/{unit}`
- **Fungsi**
  - Menampilkan item, hitung deposit 30% (via `PricingService`)
  - Validasi maksimum 2 slot sewa
  - Tombol checkout (POST `/rentals`)

---

## 7. Sewa Aktif & Trial-to-Own

- **View** : `resources/views/rentals/index.blade.php`
- **Controller** : `RentalController@index` (daftar sewa aktif), `@purchase` (Trial-to-Own), `@requestReturn`
- **Routes** :
  - `GET /rentals` (`name: rentals.index`)
  - `POST /rentals/{rental}/purchase`
  - `POST /rentals/{rental}/return-request`
- **Fungsi**
  - Daftar maks 2 unit yang sedang disewa + jatuh tempo
  - Tombol ajukan pengembalian → status `RETURN_REQUESTED`
  - Tombol TPO (Trial-to-Own) selama 5 hari pertama

---

## 8. Riwayat Pembelian

- **View** : `resources/views/user/purchases.blade.php`
- **Controller** : `RentalController@purchases`
- **Route** : `GET /purchases` (`name: purchases`)
- **Fungsi** : Menampilkan unit yang dibeli (status `PURCHASED`)

---

## 9. Profil User

- **View** : `resources/views/profile/edit.blade.php`
- **Controller** : `ProfileController@edit`, `@update` (`app/Http/Controllers/Web/ProfileController.php`)
- **Routes** : `GET /profile`, `PATCH /profile`
- **Fungsi**
  - Menampilkan detail (nama, email, phone, alamat)
  - Form update + ubah password (opsional)

---

## 10. Dashboard Admin (AdminManage)

- **View** : `resources/views/admin/dashboard.blade.php`
- **Controller** : `App\Http\Controllers\Admin\DashboardController@index`
- **Route** : `GET /admin-manage` (`name: adminManage.dashboard`)
- **Fungsi**
  - Statistik (jumlah unit, sewa aktif, deposit, return request, total user)
  - Tabel unit terbaru (aksi edit/hapus)
  - Daftar pengguna baru
  - Card status unit (tersedia/disewa/sold)
  - Link cepat ke manajemen katalog

---

## 11. Manajemen Unit (Admin)

- **Views** :
  - Index: `resources/views/admin/units/index.blade.php`
  - Create: `resources/views/admin/units/create.blade.php`
  - Edit: `resources/views/admin/units/edit.blade.php`
- **Controller** : `Admin\UnitAdminController`
- **Routes** : Resource `Route::resource('units', UnitAdminController::class)` (prefix `admin-manage`)
- **Fungsi**
  - List unit dengan status, tombol edit/hapus
  - Form tambah/edit (upload foto multiple, flag SOLD)
  - Update `is_available` / `is_sold`

---

## 12. Manajemen Kategori (Admin)

- **Views** : `admin/categories/index/create/edit.blade.php`
- **Controller** : `Admin\CategoryAdminController`
- **Routes** : Resource `categories`
- **Fungsi** : CRUD kategori (nama saja, brand-input sudah hitam)

---

## 13. Manajemen User (Admin)

- **Views** : `admin/users/index/create/edit.blade.php`
- **Controller** : `Admin\UserAdminController`
- **Routes** : Resource `users`
- **Fungsi**
  - CRUD anggota (nama, email, phone, alamat, admin flag)
  - Halaman edit menampilkan riwayat sewa singkat user

---

## 14. Transaksi & Pengembalian (Admin)

- **View daftar sewa** : `resources/views/admin/rentals/index.blade.php`, detail `show.blade.php`
- **Controller** : `Admin\RentalAdminController`
- **Routes** :
  - `GET admin-manage/rentals` (`index`)
  - `GET admin-manage/rentals/{rental}` (`show`)

- **Konfirmasi Pengembalian** :
  - Views: `admin/returns/index.blade.php`, `confirm.blade.php`
  - Controller: `Admin\ReturnAdminController`
  - Routes:
    - `GET admin-manage/returns` (`returns.index`)
    - `GET admin-manage/returns/{rental}/confirm` (form)
    - `POST admin-manage/returns/{rental}/confirm` (proses)
  - Fungsi: menghitung denda telat 10%, cleaning/damage fee, potong deposit, mengubah status rental ke `RETURNED`, mengembalikan unit jadi `is_available=true`.

---

## 15. Riwayat Sewa & Ekspor (Admin)

- **View** : `resources/views/admin/rentals/history.blade.php`
- **Controller** : `Admin\RentalAdminController@history`, `@exportHistory`
- **Routes** :
  - `GET admin-manage/reports/rentals`
  - `GET admin-manage/reports/rentals/export`
- **Fungsi** : Filter histori sewa dan unduh CSV

---

## 16. Seeder & Setup Awal

- **Seeder** :
  - `database/seeders/CategorySeeder.php`
  - `database/seeders/UnitSeeder.php` (22 unit contoh)
  - `database/seeders/AdminUserSeeder.php` (akun admin default)
- **DatabaseSeeder** memanggil semua seeder di atas.

---

## 17. Middleware & Proteksi Route

- **EnsureUserIsAdmin** : `app/Http/Middleware/EnsureUserIsAdmin.php`
  - Dipasang di group `Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin-manage')` (lihat `routes/web.php:25`)
- **Auth middleware** : didefinisikan di `app/Http/Kernel.php`
  - Menjamin halaman user/admin hanya diakses setelah login

---

## 18. Pricing & Bisnis Logic

- **PricingService** : `app/Services/PricingService.php`
  - `calcDeposit(Unit $unit)` (30% sewa untuk vintage 60s/70s)
  - `calcLateFee(Rental $rental, Carbon $returnTime)` (denda 10% untuk keterlambatan)
- **Requests** :
  - `StoreRentalRequest`, `StoreUnitRequest`, `UpdateUnitRequest`, `ReturnConfirmRequest`
  - Menyediakan validasi input untuk tiap operasi.

---

Gunakan file ini sebagai peta ketika menelusuri atau mendemokan fitur: cari route → controller → view terkait, lalu jelaskan fungsi sesuai bagian di atas.

