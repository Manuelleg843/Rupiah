<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;

class TabelHistoryController extends BaseController
{
    protected $komponen;
    protected $putaran;
    protected $wilayah;
    protected $revisiModel;
    protected $allData;
    protected $allTahunQ;
    protected $allTahunQ_OnlyQ;
    protected $allTahunQ_OnlyTahun;
    protected $allTahunQ_OnlyTahunForFilterTahunan;
    protected $allKomponen;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->wilayah = new WilayahModel();
        $this->revisiModel = new RevisiModel();
        $allData = $this->putaran->findAll();

        // periode UNIQUE
        $this->allTahunQ = array_unique(array_column($allData, 'periode'));

        // periode  ONLY TAHUN OR ONLY QUARTAL
        $this->allTahunQ_OnlyTahun = [];
        $this->allTahunQ_OnlyQ = [];
        foreach ($this->allTahunQ as $value) {
            if (strlen($value) == 4) {
                array_push($this->allTahunQ_OnlyTahun, $value);
            } else {
                array_push($this->allTahunQ_OnlyQ, $value);
            }
        }
        sort($this->allTahunQ_OnlyTahun);
        sort($this->allTahunQ_OnlyQ);

        // TAHUN FOR FILTER TAHUNAN
        $this->allTahunQ_OnlyTahunForFilterTahunan = array_unique(array_column($allData, 'tahun'));

        // ID_KOMPONEN UNIQUE
        $this->allKomponen = array_unique(array_column($allData, 'id_komponen'));
        sort($this->allKomponen);
    }


    public function index()
    {
        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'putaran' => $this->putaran->getPutaranTerakhir(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }

    // get data from database. JenisPDRB = 1 => ADHB, 2 => ADHK. 
    public function getAllData($periode, $jenisPDRB, $kota)
    {
        // get data dari database 
        $dataPDRB = [];
        $dataObjKota = [];
        foreach ($periode as $p) {
            foreach ($kota as $k) {
                if ($this->revisiModel->where('periode', $p)->where('id_pdrb', $jenisPDRB)->where('id_wilayah', $k['id_wilayah'])->countAllResults() > 0) {
                    $dataObj = $this->revisiModel->getDataFinalMod($jenisPDRB, $k['id_wilayah'], $p);
                } else {
                    $dataObj = $this->putaran->getDataFinalMod($jenisPDRB, $k['id_wilayah'], $p);
                }
                $dataObjKota = array_merge($dataObjKota, $dataObj);
            }
        }
        return $dataObjKota;
    }

    // sort data 
    public function sortData($data, $kode, $desc = false)
    {

        if ($desc) {
            usort($data, function ($a, $b) use ($kode) {
                if ($kode == 1) { // 1 IS SORT BY periode
                    return strcmp($b->periode, $a->periode);
                } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                    return strcmp($b->id_komponen, $a->id_komponen);
                } else if ($kode == 3) { // 3 IS SORT BY ID_WILAYAH
                    return strcmp($b->id_wilayah, $a->id_wilayah);
                }
            });
        }

        usort($data, function ($a, $b) use ($kode) {
            if ($kode == 1) { // 1 IS SORT BY periode
                return strcmp($a->periode, $b->periode);
            } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                return strcmp($a->id_komponen, $b->id_komponen);
            } else if ($kode == 3) { // 3 IS SORT BY ID_WILAYAH
                return strcmp($a->id_wilayah, $b->id_wilayah);
            }
        });

        return $data;
    }

    private function filter_periode($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->periode != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item->periode == $id);
        });
    }


    // FILTER id_kuartal (PERIODE KUARTAL BERAPA) : MISAL 1 , 5(TAHUN KESELURUHAN)
    private function filter_id_kuartal($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->id_kuartal != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item->id_kuartal == $id);
        });
    }
}
