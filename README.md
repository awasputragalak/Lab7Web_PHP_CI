# Lab 7 - Pemrograman Web 2 (Framework CodeIgniter 4)

Repositori ini berisi hasil Praktikum pada mata kuliah Pemrograman Web 2, yang berfokus pada pengenalan Framework PHP **CodeIgniter 4 (CI4)** dengan konsep **MVC (Model-View-Controller)**.

## Identitas

* **Nama:** Abdi Putra Perdana
* **NIM:** 312410426
* **Kelas:** I241C

---

## Persiapan

1. Mengaktifkan ekstensi PHP di `php.ini` (XAMPP):
   - `php-json`
   - `php-mysqlnd`
   - `php-xml`
   - `php-intl`
   - `mbstring`
2. Konfigurasi `.env` untuk mode development:
   ```env
   CI_ENVIRONMENT = development
   ```

---

## Praktikum 1 - Pengenalan Framework CI4 & MVC

### Tujuan
- Memahami konsep dasar MVC pada CodeIgniter 4
- Membuat routing, controller, dan view sederhana

### Langkah-langkah

1. **Instalasi CI4:** Melakukan instalasi manual di folder `htdocs/lab11_php_ci`.
2. **Membuat Controller:** Membuat file `Page.php` untuk menangani navigasi halaman (About, Contact, FAQs, Artikel).
3. **Konfigurasi Routing:** Mengatur rute URL di `app/Config/Routes.php`.
4. **Membuat Layout (Template):** Membagi tampilan menjadi `header.php` dan `footer.php` agar desain konsisten di semua halaman.
5. **Membuat View:** Implementasi file view untuk setiap menu menggunakan perintah `$this->include()`.

### Tugas: Melengkapi Menu Navigasi

Tugas praktikum ini adalah memastikan semua link pada navigasi (Home, Artikel, About, Contact, FAQ) bekerja dengan layout yang seragam.

#### Struktur File yang Dikerjakan:
- `app/Controllers/Page.php`
- `app/Config/Routes.php`
- `app/Views/template/header.php`
- `app/Views/template/footer.php`
- `app/Views/about.php`, `contact.php`, `faqs.php`, `artikel.php`

### Screenshot Hasil

#### Halaman About
<img width="1919" height="946" alt="image" src="https://github.com/user-attachments/assets/e3c9c625-f32b-4d61-8c1e-b8b15f090f8a" />

#### Halaman Contact
<img width="1919" height="869" alt="image" src="https://github.com/user-attachments/assets/fb613316-36c6-4c7b-b34c-f1072488a489" />

---

## Praktikum 2 - Framework Lanjutan (CRUD)

### Tujuan
- Memahami konsep dasar **Model** pada CodeIgniter 4
- Memahami konsep dasar **CRUD** (Create, Read, Update, Delete)
- Membuat program sederhana menggunakan Framework CodeIgniter 4

---

### Persiapan Database

Praktikum ini menggunakan database MySQL melalui XAMPP. Dibuat database `lab_ci4` dengan tabel `artikel`.

**Struktur Tabel `artikel`:**

| Field  | Tipe Data | Ukuran | Keterangan |
|--------|-----------|--------|------------|
| id     | INT       | 11     | PRIMARY KEY, auto_increment |
| judul  | VARCHAR   | 200    | |
| isi    | TEXT      | -      | |
| gambar | VARCHAR   | 200    | |
| status | TINYINT   | 1      | DEFAULT 0 |
| slug   | VARCHAR   | 200    | |

**Query SQL:**
```sql
CREATE DATABASE lab_ci4;

CREATE TABLE artikel (
  id INT(11) auto_increment,
  judul VARCHAR(200) NOT NULL,
  isi TEXT,
  gambar VARCHAR(200),
  status TINYINT(1) DEFAULT 0,
  slug VARCHAR(200),
  PRIMARY KEY(id)
);
```

**Konfigurasi Database** dilakukan pada file `.env`:
```env
database.default.hostname = localhost
database.default.database = lab_ci4
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
```

---

### Langkah 1 - Membuat Model

Model dibuat untuk memproses data dari tabel `artikel`. File disimpan di `app/Models/ArtikelModel.php`.

```php
<?php
namespace App\Models;
use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['judul', 'isi', 'status', 'slug', 'gambar'];
}
```

> Model ini mendefinisikan nama tabel, primary key, dan field yang diizinkan untuk operasi insert/update.

---

### Langkah 2 - Membuat Controller

Controller `Artikel.php` dibuat di `app/Controllers/` dengan method `index()` untuk menampilkan daftar artikel.

```php
public function index()
{
    $title = 'Daftar Artikel';
    $model = new ArtikelModel();
    $artikel = $model->findAll();
    return view('artikel/index', compact('artikel', 'title'));
}
```

---

### Langkah 3 - Membuat View Daftar Artikel

File view `index.php` dibuat di direktori `app/Views/artikel/`. View ini menampilkan semua artikel yang diambil dari database.

```php
<?= $this->include('template/header'); ?>
<?php if($artikel): foreach($artikel as $row): ?>
<article class="entry">
    <h2><a href="<?= base_url('/artikel/' . $row['slug']);?>"><?= $row['judul']; ?></a></h2>
    <img src="<?= base_url('/gambar/' . $row['gambar']);?>" alt="<?= $row['judul']; ?>">
    <p><?= substr($row['isi'], 0, 200); ?></p>
</article>
<hr class="divider" />
<?php endforeach; else: ?>
<article class="entry">
    <h2>Belum ada data.</h2>
</article>
<?php endif; ?>
<?= $this->include('template/footer'); ?>
```

Kemudian ditambahkan data uji ke database:

```sql
INSERT INTO artikel (judul, isi, slug) VALUE
('Artikel pertama', 'Lorem Ipsum adalah contoh teks...', 'artikel-pertama'),
('Artikel kedua', 'Tidak seperti anggapan banyak orang...', 'artikel-kedua');
```

#### Screenshot - Daftar Artikel

<img width="1919" height="950" alt="image" src="https://github.com/user-attachments/assets/07466127-1142-4d91-9baa-879da92dd086" />

---

### Langkah 4 - Membuat Tampilan Detail Artikel

Ditambahkan method `view($slug)` pada Controller Artikel untuk menampilkan detail artikel berdasarkan slug.

```php
public function view($slug)
{
    $model = new ArtikelModel();
    $artikel = $model->where(['slug' => $slug])->first();

    if (!$artikel) {
        throw PageNotFoundException::forPageNotFound();
    }

    $title = $artikel['judul'];
    return view('artikel/detail', compact('artikel', 'title'));
}
```

File view detail dibuat di `app/Views/artikel/detail.php`. Routing untuk halaman detail juga ditambahkan di `app/Config/Routes.php`:

```php
$routes->get('/artikel/(:any)', 'Artikel::view/$1');
```

#### Screenshot - Detail Artikel

<img width="1919" height="956" alt="image" src="https://github.com/user-attachments/assets/207209d7-c7a4-424f-816d-5b191f6218e1" />

---

### Langkah 5 - Membuat Menu Admin (CRUD)

Menu admin digunakan untuk mengelola data artikel (Create, Read, Update, Delete). Ditambahkan method `admin_index()` pada Controller Artikel.

```php
public function admin_index()
{
    $title = 'Daftar Artikel';
    $model = new ArtikelModel();
    $artikel = $model->findAll();
    return view('artikel/admin_index', compact('artikel', 'title'));
}
```

View `admin_index.php` menampilkan tabel artikel lengkap dengan tombol **Ubah** dan **Hapus**.

Routing untuk menu admin dikonfigurasi sebagai group:

```php
$routes->group('admin', function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->add('artikel/add', 'Artikel::add');
    $routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});
```

#### Screenshot - Halaman Admin

<img width="1919" height="951" alt="image" src="https://github.com/user-attachments/assets/995f4a3a-5df6-465c-8736-888c4f4050a7" />

---

### Langkah 6 - Menambah Data Artikel (Create)

Ditambahkan method `add()` pada Controller Artikel. Method ini menangani validasi dan penyimpanan data baru ke database.

```php
public function add()
{
    $validation = \Config\Services::validation();
    $validation->setRules(['judul' => 'required']);
    $isDataValid = $validation->withRequest($this->request)->run();

    if ($isDataValid) {
        $artikel = new ArtikelModel();
        $artikel->insert([
            'judul' => $this->request->getPost('judul'),
            'isi'   => $this->request->getPost('isi'),
            'slug'  => url_title($this->request->getPost('judul')),
        ]);
        return redirect('admin/artikel');
    }

    $title = "Tambah Artikel";
    return view('artikel/form_add', compact('title'));
}
```

Form tambah artikel dibuat di `app/Views/artikel/form_add.php`.

#### Screenshot - Form Tambah Artikel

<img width="1919" height="950" alt="image" src="https://github.com/user-attachments/assets/110d7d70-e1f2-4fd9-bb8f-9d1a250926c6" />


---

### Langkah 7 - Mengubah Data Artikel (Update)

Ditambahkan method `edit($id)` pada Controller Artikel untuk memuat data lama dan menyimpan perubahan.

```php
public function edit($id)
{
    $artikel = new ArtikelModel();
    $validation = \Config\Services::validation();
    $validation->setRules(['judul' => 'required']);
    $isDataValid = $validation->withRequest($this->request)->run();

    if ($isDataValid) {
        $artikel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'isi'   => $this->request->getPost('isi'),
        ]);
        return redirect('admin/artikel');
    }

    $data = $artikel->where('id', $id)->first();
    $title = "Edit Artikel";
    return view('artikel/form_edit', compact('title', 'data'));
}
```

Form edit artikel dibuat di `app/Views/artikel/form_edit.php` dengan nilai input yang sudah terisi data lama.

#### Screenshot - Form Edit Artikel

<img width="1919" height="947" alt="image" src="https://github.com/user-attachments/assets/9968de99-c21b-4c5c-8eb6-ac4eccc90310" />

---

### Langkah 8 - Menghapus Data Artikel (Delete)

Ditambahkan method `delete($id)` pada Controller Artikel untuk menghapus data berdasarkan ID.

```php
public function delete($id)
{
    $artikel = new ArtikelModel();
    $artikel->delete($id);
    return redirect('admin/artikel');
}
```

Penghapusan data dilakukan setelah pengguna mengkonfirmasi melalui dialog `confirm()` di halaman admin.

#### Screenshot - Konfirmasi Hapus

<img width="1919" height="946" alt="image" src="https://github.com/user-attachments/assets/7867f2b9-22eb-4064-b8e4-fe50aafb5f6c" />


---

## Praktikum 3 - View Layout dan View Cell

### Tujuan
Memahami konsep View Layout di CodeIgniter 4.
Menggunakan View Layout untuk membuat template tampilan.
Memahami dan mengimplementasikan View Cell untuk memanggil komponen UI secara modular.

### Langkah-langkah
Membuat Layout Utama: Memusatkan kerangka HTML (header, nav, sidebar, footer) pada satu file utama di app/Views/layout/main.php. File ini menggunakan $this->renderSection('content') sebagai tempat untuk menyisipkan konten dinamis.

Membuat Class View Cell: Membuat komponen modular ArtikelTerkini.php pada folder app/Cells/ yang berfungsi mengambil 5 artikel terbaru dari database.

Membuat Tampilan View Cell: Mengatur antarmuka untuk widget artikel terkini pada file app/Views/components/artikel_terkini.php. Komponen ini dipanggil pada sidebar layout utama.

Modifikasi File View: Mengubah file view halaman (seperti about.php atau artikel/index.php) agar meng-extend layout utama dan mengisi blok konten dengan perintah $this->section('content').

#### Screenshot Hasil Praktikum 3
<img width="1919" height="948" alt="image" src="https://github.com/user-attachments/assets/79fd51d3-d1f4-47b7-81d7-60bbe954fce6" />

---

## Praktikum 4 - Framework Lanjutan (Modul Login)

### Tujuan
Memahami konsep dasar Auth dan Filter.
Memahami konsep dasar Login System.
Membuat modul login menggunakan Framework CodeIgniter 4.

### Langkah-langkah
Membuat Tabel User & Database Seeder:

Membuat tabel user untuk menampung data admin .

Membuat file UserSeeder.php (php spark make:seeder UserSeeder) untuk membuat data admin dummy dan melakukan enkripsi password menggunakan hash.

Membuat Model User: Membuat file UserModel.php untuk memetakan interaksi tabel user dengan CodeIgniter.

Membuat Controller User: Menambahkan logika validasi email dan kecocokan password (password_verify) di dalam User.php. Apabila valid, data sesi (session) pengguna akan disimpan.

Membuat View Login: Merancang tampilan form otentikasi pada app/Views/user/login.php yang dilengkapi pesan Flashdata apabila login gagal.

Menambahkan Auth Filter: - Membuat kelas Filter Auth.php untuk memeriksa session. Pengguna yang belum login akan dilempar (redirect) otomatis ke halaman login .

Mendaftarkan filter tersebut di file app/Config/Filters.php.

Melindungi Rute Admin: Mengelompokkan seluruh Routes bagian admin ($routes->group) dan menyisipkan filter auth agar tidak dapat diakses secara publik.

### Screenshot Hasil Praktikum 4
<img width="1919" height="951" alt="image" src="https://github.com/user-attachments/assets/46e76b5f-8ec6-425c-8c5f-ae112a8c3bcb" />

---

## Praktikum 5 - Pagination dan Pencarian
### Tujuan
- Memahami konsep dasar Paginasi (Pagination). 
- Memahami konsep dasar Pencarian (Search). 
- Mengimplementasikan Paging dan Pencarian menggunakan fitur bawaan CodeIgniter 4. 

### Langkah-langkah

- Implementasi Paginasi: Menggunakan metode paginate() pada Model untuk membatasi jumlah baris data yang ditampilkan per halaman. 
- Menampilkan Navigasi Paginasi: Menambahkan fungsi <?= $pager->links(); ?> di file view untuk merender tombol navigasi halaman secara otomatis. 
- Membuat Fitur Pencarian: Menangkap input kata kunci (q) dari URL menggunakan getVar() dan menerapkannya ke dalam query database menggunakan metode like(). 
- Integrasi Paginasi dan Pencarian: Menggunakan fungsi only(['q']) pada pager agar parameter pencarian tidak hilang saat pengguna berpindah ke halaman berikutnya. 

### Screenshot Hasil Praktikum 5
<img width="1919" height="944" alt="Cuplikan layar 2026-04-15 135835" src="https://github.com/user-attachments/assets/fda829d6-49c4-428b-8daf-bce2a1dc2d64" />

---

## Prakktikum 6: Relasi Tabel dan Query Builder

Pada praktikum ini, struktur database dikembangkan dengan menerapkan konsep relasi antar tabel (One-to-Many) dan penggunaan *Query Builder* bawaan CodeIgniter 4 untuk menggabungkan data (*Join*) serta sedikit Improvisasi. 

### Tujuan
- Memahami konsep relasi antar tabel dalam database.
- Mengimplementasikan relasi *One-to-Many* (Satu Kategori memiliki Banyak Artikel).
- Melakukan query penggabungan tabel (*Join*) menggunakan *Query Builder* CI4.
- Menampilkan data terintegrasi dari tabel yang berelasi ke dalam antarmuka web.

### Langkah-langkah Praktikum

1. **Pembuatan Tabel Kategori & Relasi Database:**
   - Membuat tabel baru bernama `kategori` (`id_kategori`, `nama_kategori`, `slug_kategori`) 
   - Menambahkan kolom `id_kategori` pada tabel `artikel` dan menjadikannya sebagai *Foreign Key* yang merujuk ke tabel `kategori` 

2. **Pembuatan KategoriModel:**
   - Membuat `app/Models/KategoriModel.php` untuk mendefinisikan interaksi dengan tabel `kategori` 

3. **Modifikasi ArtikelModel (Join Query):**
   - Menambahkan `id_kategori` ke dalam properti `$allowedFields` 
   - Membuat metode baru `getArtikelDenganKategori()` yang mengimplementasikan *Query Builder* `join()` untuk mengambil data artikel beserta nama kategorinya 

4. **Modifikasi Controller Artikel:**
   - Memanggil `KategoriModel` di dalam `Artikel.php`
   - [cite_start]Mengubah metode `index()` dan `admin_index()` untuk memuat data kategori dan menerapkannya pada *dropdown* filter 
   - [cite_start]Memodifikasi metode `add()` dan `edit()` agar dapat menangkap dan menyimpan data `id_kategori` ke dalam database 

5. **Modifikasi View (Antarmuka Admin):**
   - **Tabel Admin (`admin_index.php`):** Menambahkan *dropdown* untuk memfilter artikel berdasarkan kategori dan menambahkan kolom "Kategori" di dalam tabel data .
   - **Formulir (`form_add.php` & `form_edit.php`):** Menambahkan elemen `<select>` yang di- *looping* dari data tabel kategori, memungkinkan pengguna untuk memilih kategori saat membuat atau mengubah artikel 

### Screenshot Hasil Praktikum 6
<img width="1919" height="952" alt="image" src="https://github.com/user-attachments/assets/82afcb8c-5490-4941-bc8a-b4d313aaa1e1" />


### Pengembangan Fitur (Tugas Mandiri, Opsional dan Improvisasi)

Selain implementasi dasar relasi tabel, berikut adalah fitur tambahan yang telah diimplementasikan sesuai instruksi tugas:

#### 1. Implementasi Kategori pada Detail Artikel (Tugas 2)
Modifikasi pada file `app/Views/artikel/detail.php` dan `app/Controllers/Artikel.php` untuk menampilkan label kategori pada setiap artikel yang dibaca
- **Teknis:** Menggunakan *Query Builder* `join` pada method `view($slug)` untuk menarik data `nama_kategori` berdasarkan `id_kategori`.

#### 2. Widget Daftar Kategori di Halaman Depan (Tugas 3)
Menambahkan *sidebar* dinamis pada halaman index pengunjung yang menampilkan seluruh daftar kategori yang tersedia di database
- **Teknis:** Memanggil `KategoriModel` di dalam method `index()` dan mengirimkan datanya ke view `artikel/index.php`.

#### 3. Fitur Filter Artikel per Kategori (Tugas 4)
Memungkinkan pengunjung untuk memfilter daftar artikel hanya berdasarkan kategori tertentu yang dipilih melalui sidebar
- **Teknis:** - Menambahkan rute baru: `$routes->get('/artikel/kategori/(:any)', 'Artikel::category/$1');`.
  - Membuat method `category($slug)` di Controller untuk melakukan filtering data artikel menggunakan `where` clause pada kolom `slug_kategori`.

### Struktur Perubahan Kode (Update)

**Routes (`app/Config/Routes.php`):**
Mendaftarkan rute kategori di atas rute detail artikel untuk menghindari *route collision* (404 error).

**Controller (`app/Controllers/Artikel.php`):**
Penambahan logika pada method `view()`, `index()`, dan pembuatan method baru `category()`.

**View (`app/Views/artikel/index.php`):**
Implementasi layout *sidebar* menggunakan sistem grid Bootstrap 5.

### Screenshot Hasil Tugas Tambahan
<img width="1919" height="953" alt="image" src="https://github.com/user-attachments/assets/24633f91-2de3-4270-962d-1bb4c63629c1" />

<img width="1919" height="949" alt="image" src="https://github.com/user-attachments/assets/365d5f3a-04bb-409e-9869-fcc703a8e246" />

---

### Praktikum 7: Upload File Gambar

Pada praktikum ini, dilakukan penambahan fitur unggah (*upload*) file gambar yang difungsikan sebagai sampul (*cover* / *thumbnail*) utama untuk setiap artikel. Fitur ini diimplementasikan baik pada saat menambahkan artikel baru maupun saat mengubah artikel lama.

#### 1. Persiapan Direktori
Sesuai dengan instruksi modul, file gambar yang diunggah oleh pengguna akan disimpan ke dalam folder proyek lokal.
- **Teknis:** Membuat folder baru bernama `gambar` di dalam direktori `public/` sebagai tempat penyimpanan akhir file fisik gambar.

#### 2. Konfigurasi Controller (`Artikel.php`)
Melakukan modifikasi pada method `add()` dan `edit()` untuk menangani proses penangkapan file dari form dan memindahkannya ke folder tujuan
- **Teknis:** 
  - Menggunakan fungsi `$this->request->getFile('gambar')` untuk mengambil data file
  - Menggunakan `$file->move(ROOTPATH . 'public/gambar')` untuk memindahkan file ke direktori publik
  - Menyimpan nama asli file gambar ke dalam array data untuk dimasukkan ke kolom `gambar` pada database
  - Pada method `edit()`, ditambahkan logika untuk mengecek apakah user mengunggah gambar baru atau tetap menggunakan gambar lama.

#### 3. Penyesuaian View (Form HTML)
Mengubah struktur form pada file `form_add.php` dan `form_edit.php` agar dapat memproses pengiriman file
- **Teknis:** 
  - Menambahkan atribut wajib `enctype="multipart/form-data"` pada tag `<form>`[cite: 1].
  - Menambahkan input field `<input type="file" name="gambar">` yang sudah dikustomisasi menggunakan desain Bootstrap

### Screenshot Hasil Praktikum 7
<img width="1919" height="944" alt="image" src="https://github.com/user-attachments/assets/eb9d49dd-23d9-4cc6-8aa0-61d9791ff5fe" />

<img width="1919" height="952" alt="image" src="https://github.com/user-attachments/assets/0690c3e4-0b4b-49ca-886c-49693b42babe" />

<img width="1919" height="1010" alt="image" src="https://github.com/user-attachments/assets/dc3b74fa-257e-4c0c-94dd-7486d08c46e2" />


---
© 2026 - Abdi Putra Perdana - Universitas Pelita Bangsa


