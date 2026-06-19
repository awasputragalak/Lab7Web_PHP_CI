<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use App\Models\ArtikelModel;

class AjaxController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Manajemen Artikel (AJAX)'
        ];
        return view('ajax/index', $data);
    }

    public function getData()
    {
        $model = new ArtikelModel();
        $data = $model->findAll();
        
        return $this->response->setJSON($data);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        
        $data = [
            'status' => 'OK'
        ];
        
        return $this->response->setJSON($data);
    }

// Fungsi untuk ngambil data 1 artikel spesifik buat dilempar ke form Edit
    public function getDetail($id)
    {
        $model = new ArtikelModel();
        $data = $model->find($id);
        
        return $this->response->setJSON($data);
    }

    // Fungsi sakti untuk nge-Handle Tambah Data sekaligus Edit Data
    public function save()
    {
        $model = new ArtikelModel();

        // Tangkap semua inputan dari form
        $data = [
            'judul'       => $this->request->getPost('judul'),
            'isi'         => $this->request->getPost('isi'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'slug'        => url_title($this->request->getPost('judul'), '-', true)
        ];

        $id = $this->request->getPost('id'); // Ambil ID yang disembunyikan

        // Logika simpel: Kalau ada ID berarti Edit, kalau kosong berarti Tambah Baru
        if ($id) {
            $model->update($id, $data);
            $msg = 'Data artikel berhasil diperbarui! ✨';
        } else {
            $model->insert($data);
            $msg = 'Artikel baru berhasil diterbitkan! 🚀';
        }

        // Kirim status sukses dan pesan ke Javascript
        return $this->response->setJSON(['status' => 'OK', 'pesan' => $msg]);
    }
}