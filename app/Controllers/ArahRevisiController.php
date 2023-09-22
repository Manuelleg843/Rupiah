<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\RevisiModel;

class ArahRevisiController extends BaseController
{
    protected $komponen;
    protected $putaran;
    protected $request;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new RevisiModel();
    }

    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Arah Revisi',
            'tajuk' => 'Arah Revisi',
            'subTajuk' => 'Arah Revisi Kota',
            'komponen' => $this->putaran->get_data(),
            'nilai' => $this->putaran->getDataGabungan(),
            'selectedperiode' => $this->request->getVar('colums'),
            'rilis' => $this->putaran->getPutaranTerakhir(),

            'pdrbq1' => $this->putaran->get_data_2023Q1(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/arahRevisi/arahRevisi', $data);
        echo view('layouts/footer');
    }
}
