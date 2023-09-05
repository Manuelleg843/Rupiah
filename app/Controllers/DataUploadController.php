<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DataUploadController extends BaseController
{
    public function index()
    {
        //
        $data = [
            'title' => 'Upload Data',
            'tajuk' => 'uploadData'
        ];
        
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('dataUpload/angkaPDRB');
        echo view('layouts/footer');
    }
    
    public function viewUploadAngkaPDRB()
    {
        //
        $data = [
            'title' => 'Upload Data',
            'tajuk' => 'uploadData',
            'subTajuk' => 'angkaPDRB'
        ];

        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('dataUpload/angkaPDRB', $data);
        echo view('layouts/footer');
    }

    
}
