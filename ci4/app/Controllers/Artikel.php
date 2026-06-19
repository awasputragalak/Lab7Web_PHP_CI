<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $data = [
            'title' => $title,
            'artikel' => $model->getArtikelDenganKategori(),
            'kategori' => $kategoriModel->findAll(),
        ];
        return view('artikel/index', $data);
    }

    public function admin_index()
    {
        $title = 'Daftar Artikel (Admin)';
        $model = new \App\Models\ArtikelModel();
        $kategoriModel = new \App\Models\KategoriModel();

        // 1. Tangkap semua parameter dari request
        $q = $this->request->getVar('q') ?? '';
        $kategori_id = $this->request->getVar('kategori_id') ?? '';
        $sort = $this->request->getVar('sort') ?? 'terbaru'; // Parameter Sorting (Tugas 4)
        $page = $this->request->getVar('page') ?? 1;

        $builder = $model->select('artikel.*, kategori.nama_kategori')
                         ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left');

        // 2. Filter Pencarian & Kategori
        if ($q != '') {
            $builder->like('artikel.judul', $q);
        }
        if ($kategori_id != '') {
            $builder->where('artikel.id_kategori', $kategori_id);
        }

        // 3. Logic Fitur Sorting (Tugas 4)
        if ($sort == 'judul_asc') {
            $builder->orderBy('artikel.judul', 'ASC');
        } elseif ($sort == 'judul_desc') {
            $builder->orderBy('artikel.judul', 'DESC');
        } else {
            $builder->orderBy('artikel.id', 'DESC'); // Default: Artikel Terbaru
        }

        // Eksekusi data (Tetap 5 artikel per halaman)
        $artikel = $builder->paginate(5, 'default', $page);
        $pager = $model->pager;

        // 4. Jika request dari AJAX, kirim JSON
        if ($this->request->isAJAX()) {
            sleep(1); // Simulasi delay untuk testing loading (Tugas 3)
            return $this->response->setJSON([
                'artikel' => $artikel,
                // Kita kirim render HTML pagination langsung biar UI Bootstrap lu tetap rapi (Tugas 2)
                'pager'   => $pager->links('default', 'bootstrap_pagination')
            ]);
        }

        // 5. Jika load halaman pertama kali (Bukan AJAX)
        $data = [
            'title'       => $title,
            'kategori'    => $kategoriModel->findAll(),
            'q'           => $q,
            'kategori_id' => $kategori_id,
            'sort'        => $sort
        ];

        return view('artikel/admin_index', $data);
    }

    public function category($slug)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();
        
        $category = $kategoriModel->where('slug_kategori', $slug)->first();
        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Kategori: ' . $category['nama_kategori'],
            'artikel' => $model->db->table('artikel')
                               ->select('artikel.*, kategori.nama_kategori')
                               ->join('kategori', 'kategori.id_kategori = artikel.id_kategori')
                               ->where('kategori.slug_kategori', $slug)
                               ->get()->getResultArray(),
            'kategori' => $kategoriModel->findAll(),
        ];
        return view('artikel/index', $data);
    }

    public function add()
    {
        $kategoriModel = new \App\Models\KategoriModel();
        
        // 1. Validasi data (Sesuai modul)
        $validation = \Config\Services::validation();
        $validation->setRules([
            'judul' => 'required',
            'isi' => 'required',
            'id_kategori' => 'required'
        ]);
        $isDataValid = $validation->withRequest($this->request)->run(); //

        if ($this->request->getMethod() == 'POST' && $isDataValid) {
            
            // 2. Proses Ambil File Gambar
            $file = $this->request->getFile('gambar'); //
            $namaGambar = ''; // Default string kosong kalau user gak upload gambar

            // Cek apakah ada file gambar yang diupload dan valid
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $namaGambar = $file->getName(); // Ambil nama asli filenya[cite: 1]
                $file->move(ROOTPATH . 'public/gambar', $namaGambar); // Pindahkan ke public/gambar[cite: 1]
            }

            // 3. Simpan data ke database
            $model = new \App\Models\ArtikelModel();
            $model->insert([
                'judul'       => $this->request->getPost('judul'), //[cite: 1]
                'isi'         => $this->request->getPost('isi'), //[cite: 1]
                'slug'        => url_title($this->request->getPost('judul'), '-', true), //[cite: 1]
                'id_kategori' => $this->request->getPost('id_kategori'),
                'gambar'      => $namaGambar // Masukkan nama file ke kolom gambar[cite: 1]
            ]);
            
            session()->setFlashdata('pesan', 'Artikel baru + gambar berhasil diterbitkan! 🚀');
            return redirect()->to('/admin/artikel'); //[cite: 1]
        }

        $data = [
            'title'    => "Tambah Artikel", //[cite: 1]
            'kategori' => $kategoriModel->findAll()
        ];
        return view('artikel/form_add', $data); //[cite: 1]
    }

   public function edit($id)
    {
        $model = new \App\Models\ArtikelModel();
        $kategoriModel = new \App\Models\KategoriModel();

        if ($this->request->getMethod() == 'POST') {
            // Ambil file gambar dari form
            $file = $this->request->getFile('gambar');
            
            // Ambil nama gambar lama dari input hidden
            $gambarLama = $this->request->getPost('gambarLama');
            $namaGambar = $gambarLama; // Set default ke gambar lama

            // Cek apakah user mengupload gambar baru
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $namaGambar = $file->getName(); // Ambil nama file baru
                $file->move(ROOTPATH . 'public/gambar', $namaGambar); // Simpan ke folder
            }

            $model->update($id, [
                'judul'       => $this->request->getPost('judul'),
                'isi'         => $this->request->getPost('isi'),
                'slug'        => url_title($this->request->getPost('judul'), '-', true), // Slug diupdate sekalian
                'id_kategori' => $this->request->getPost('id_kategori'),
                'gambar'      => $namaGambar // Simpan nama gambar (baru atau tetap yang lama)
            ]);
            
            session()->setFlashdata('pesan', 'Perubahan artikel berhasil disimpan! ✨');
            return redirect()->to('/admin/artikel');
        }

        $data = [
            'title'    => "Edit Artikel",
            'artikel'  => $model->find($id),
            'kategori' => $kategoriModel->findAll()
        ];
        return view('artikel/form_edit', $data);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        
        // Set Flashdata buat animasi Sukses Hapus
        session()->setFlashdata('pesan', 'Artikel sukses dihapus selamanya! 🗑️');
        return redirect()->to('/admin/artikel');
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        $artikel = $model->select('artikel.*, kategori.nama_kategori')
                         ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
                         ->where('slug', $slug)
                         ->first();

        if (!$artikel) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $artikel['judul'],
            'artikel' => $artikel,
            'kategori' => $kategoriModel->findAll()
        ];
        return view('artikel/detail', $data);
    }
}