<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'User Administrator'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/administrator');
        echo view('layouts/footer');
    }
    public function viewAdministrator()
    {
        //
        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'User Administrator',
            'users' => $this->userModel->paginate(10, 'users'),
            'pager' => $this->userModel->pager,
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/administrator', $data);
        echo view('layouts/footer');
    }

    public function viewRoleAndPermission()
    {
        //
        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'Role and Permission'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/roleAndPermission');
        echo view('layouts/footer');
    }

    public function deleteUser($id)
    {
        // method untuk menghapus data user

        $this->userModel->delete($id);
        session()->setFlashdata('pesan', 'User berhasil dihapus.');
        return redirect()->to('/admin/administrator');
    }
}
