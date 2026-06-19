<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Auth extends ResourceController
{
    protected $format = 'json';

    // ========================================================
    // FUNGSI CONSTRUCT: Buat ngatasin Error CORS Preflight
    // ========================================================
    public function __construct()
    {
        // Izinkan semua domain dan method (CORS)
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        
        // Hentikan proses jika ini hanya request preflight OPTIONS dari browser
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            die();
        }
    }

    // ========================================================
    // FUNGSI LOGIN: Buat ngecek email & password ke Database
    // ========================================================
    public function login()
    {
        // 1. Menerima data input dari request body JSON Frontend
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $model = new UserModel();
        
        // 2. Cari user berdasarkan username atau email di database
        // PENTING JON: Kalau kolom di database lu namanya cuma 'email', ganti kata 'useremail' di bawah jadi 'email'
        $user = $model->where('username', $username)
                      ->orWhere('useremail', $username)
                      ->first();

        if ($user) {
            // 3. Verifikasi password
            // PENTING JON: Sama kayak email, kalau di database lu namanya 'password', ganti kata 'userpassword' di bawah.
            if ($password === $user['userpassword'] || password_verify($password, $user['userpassword'])) {
                
                // Jika sukses, kirim status data dan token respon ke VueJS
                return $this->respond([
                    'status'   => 200,
                    'error'    => null,
                    'messages' => 'Login Berhasil',
                    'data'     => [
                        'id'       => $user['id'],
                        'username' => $user['username'],
                        'token'    => base64_encode("TOKEN-SECRET-" . $user['username'])
                    ]
                ], 200);
            }
        }
        
        // 4. Jika gagal/data ga ketemu, kirim status error 401 (Unauthorized)
        return $this->failUnauthorized('Username atau Password yang Anda masukkan salah.');
    }
}