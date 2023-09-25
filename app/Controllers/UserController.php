<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WilayahModel;
use App\Models\RoleModel;
use App\Models\RoleHasPermissionModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $WilayahModel;
    protected $RoleModel;
    protected $RoleHasPermissionModel;

    public function __construct()
    {
        helper(['form']);

        $this->userModel = new UserModel();
        $this->WilayahModel = new WilayahModel();
        $this->RoleModel = new RoleModel();
        $this->RoleHasPermissionModel = new RoleHasPermissionModel();
    }

    public function index()
    {
        helper(['form']);

        if ($this->request->getPost()) {

            // Rules Validasi input
            $rules = [
                'email' => [
                    'rules' => 'required|is_not_unique[master_pegawai.email]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'is_not_unique' => '{field} belum terdaftar.'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'password harus diisi.'
                    ]
                ]
            ];

            // Validasi input
            if (!$this->validate($rules)) {
                return view('login', [
                    'validation' => $this->validator
                ]);
            } else {
                return $this->login();
            }
        }

        echo view('login');
    }

    private function login()
    {
        // Mendapatkan input username dan password
        $login = [];
        $login['email'] = $this->request->getPost('email');
        $login['password'] = $this->request->getPost('password');

        // Cek username dan password dengan password_verify()
        $user = $this->userModel->where('email', $login['email'])->first();
        if ($user && password_verify($login['password'], $user['password'])) {

            // Jika ada, maka login berhasil
            $user = $this->userModel->where('email', $this->request->getVar('email'))->first();
            $this->setUserSession($user);
            return redirect()->to('/beranda');
        } else {

            // Jika tidak ada, maka login gagal
            return redirect()->to('login')->with('error', 'Email pengguna atau kata sandi salah.');
        }
    }

    private function setUserSession($user)
    {

        // Closure untuk mengambil satker
        $setSatker = function ($id_satker) {
            $satker = $this->WilayahModel->where('id_wilayah', $id_satker)->first();
            return $satker['wilayah'];
        };

        // Closure untuk mengambil nama id_permission
        $setPermission = function ($nip_lama) {

            // Query untuk mengambil permission
            $customQuery = "SELECT rp.id_permission FROM master_pegawai mp 
            INNER JOIN role r ON mp.id_role = r.id_role 
            INNER JOIN role_has_permission rp ON r.id_role = rp.id_role 
            WHERE mp.niplama = $nip_lama";

            $permissions = $this->userModel->db->query($customQuery)->getResultArray();
            $result = [];
            foreach ($permissions as $permission) {
                array_push($result, $permission['id_permission']);
            }
            return $result;
        };

        // Closure untuk mengambil nama role
        $setRole = function ($id_role) {
            $customQuery = "SELECT r.nama_role FROM master_pegawai mp
            INNER JOIN role r ON mp.id_role = r.id_role
            WHERE r.id_role = $id_role";

            $role = $this->userModel->db->query($customQuery)->getResultArray();
            foreach ($role as $role) {
                $role = $role['nama_role'];
            }
            return $role;
        };

        $data = [
            'niplama' => $user['niplama'],
            'nama' => $user['gelar_depan'] . ' ' . $user['nama'] . ' ' . $user['gelar_belakang'],
            'email' => $user['email'],
            'id_satker' => $user['id_satker'],
            'satker' => $setSatker($user['id_satker']),
            'id_role' => $user['id_role'],
            'role' => $setRole($user['id_role']),
            'isLoggedIn' => true,
            'permission' => $setPermission($user['niplama'])
        ];

        session()->set($data);
        return true;
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
