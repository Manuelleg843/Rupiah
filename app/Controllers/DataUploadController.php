<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DataUploadController extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Upload Data',
            'tajuk' => 'Upload Data'
        ];
        
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('uploadData/uploadAngkaPDRB');
        echo view('layouts/footer');
    }
    
    public function viewUploadAngkaPDRB()
    {
        //
        $data = [
            'title' => 'Rupiah | Upload Data',
            'tajuk' => 'Upload Data',
            'subTajuk' => 'Angka PDRB'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('uploadData/uploadAngkaPDRB');
        echo view('layouts/footer');
    }

    
}
