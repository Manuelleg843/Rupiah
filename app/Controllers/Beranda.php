<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use function App\Helpers\is_logged_in;

class Beranda extends BaseController
{
    public function index()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

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
}
