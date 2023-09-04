<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        //
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar');
        // echo view('dashboard/index');
        echo view('layouts/footer');
    }
}
