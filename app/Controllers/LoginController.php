<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LoginController extends BaseController
{

    public function index()
    {
        helper(['form']);
        echo view('login');
    }

    public function login()
    {
        $data = [
            'title' => 'Beranda',
            'tajuk' => 'Beranda',
            'subTajuk' => ''
        ];
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('beranda');
        echo view('layouts/footer');
    }

    public function forgotPassword()
    {
        echo view('lupa_password');
    }
}
