<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
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
            'subTajuk' => 'User Administrator'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/administrator');
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
}
