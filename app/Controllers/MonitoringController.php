<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MonitoringController extends BaseController
{
    public function index()
    {
        // cek apakah sudah login
        if (!session()->get('email')) {
            return redirect()->to('/login');
        };

        //
        $data = [
            'title' => 'Rupiah | Monitoring',
            'tajuk' => 'Monitoring Putaran',
            'subTajuk' => ''
        ];
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('monitoring/monitoringputaran');
        echo view('layouts/footer');
    }
}
