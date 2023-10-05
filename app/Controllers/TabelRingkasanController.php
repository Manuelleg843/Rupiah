<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PDO;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;
use function PHPSTORM_META\type;

class TabelRingkasanController extends BaseController
{

    protected $nilaiDiskrepansi;
    protected $komponen;
    protected $putaran;
    protected $wilayah;
    protected $revisiModel;
    protected $tumbal;
    protected $allData;
    protected $allTahunQ;
    protected $allTahunQ_OnlyQ;
    protected $allTahunQ_OnlyTahun;
    protected $allTahunQ_OnlyTahunForFilterTahunan;
    protected $allKomponen;

    public function __construct()
    {
        $this->nilaiDiskrepansi = new DiskrepansiModel();
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->wilayah = new WilayahModel();
        $this->revisiModel = new RevisiModel();
        $allData = $this->putaran->findAll();

        // periode UNIQUE
        // $periodeUnik =  array_unique(array_column($allData, 'periode'));
        // $this->allTahunQ = $periodeUnik;
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
        // $this->allTahunQ_OnlyTahunForFilterTahunan = array_unique(array_column($obj, 'tahun'));
        // $tahunUnik = array_unique(array_column($allData, 'tahun'));
        // $this->allTahunQ_OnlyTahunForFilterTahunan = $tahunUnik;
        $this->allTahunQ_OnlyTahunForFilterTahunan = array_unique(array_column($allData, 'tahun'));

        // ID_KOMPONEN UNIQUE
        // $this->allKomponen = array_unique(array_column($obj, 'id_komponen'));
        // $komponenUnik = array_unique(array_column($allData, 'id_komponen'));
        // $this->allKomponen = $komponenUnik;
        // sort($this->allKomponen);
        $this->allKomponen = array_unique(array_column($allData, 'id_komponen'));
        sort($this->allKomponen);
    }

    public function index()
    {
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'Tabel Ringkasan'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/diskrepansi-ADHB');
        echo view('layouts/footer');
    }

    public function redirectPage($segment)
    {
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'tabelPDRB',
            'subTajuk' => 'tabel Ringkasan'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/' . $segment);
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

    // FILTER periode : MISAL 2017, 2018Q3
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

    // FILTER id_komponen : MISAL 1a
    private function filter_id_komponen($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->id_komponen != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item->id_komponen == $id);
        });
    }

    // FILTER id_wilayah (TINGKAT KOTA/PROVINSI) : MISAL 3172
    private function filter_id_wilayah($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->id_wilayah != $id);
            });
        }

        $filteredArray = [];
        foreach ($data as $item) {
            if (isset($item->id_wilayah) && $item->id_wilayah == $id) {
                $filteredArray[] = $item;
            }
        }

        return $filteredArray;
    }

    // FILTER id_pdrb (ADHB / ADHK) : MISAL 1 (adhb),2 (adhk)
    private function filter_id_pdrb($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->id_pdrb != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item->id_pdrb == $id);
        });
    }

    private function filter_tahun($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->tahun != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item->tahun == $id);
        });
    }

    // FILTER putaran 
    private function filter_putaran($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->putaran != $id);
            });
        }

        $filteredArray = [];
        foreach ($data as $item) {
            $key = $item->periode . '_' . $item->id_wilayah;

            // Memeriksa apakah nilai putaran sama dengan nilai maksimum yang sesuai
            if ($item->putaran == $id) {
                // Jika ya, masukkan data ke dalam array hasil filter
                $filteredArray[] = $item;
            }
        }

        return $filteredArray;
    }

    // mencari putaran terakhir di tiap periode 
    private function data_putaran_terakhir($data)
    {
        // get tahun unik 
        $periodeUnik =  array_unique(array_column($data, 'periode'));

        // get putaran maksimal
        $putaranUnik = [];
        foreach ($periodeUnik as $periode) {
            // Cari putaran unik untuk tahun tertentu
            $putaranUnik = array_unique(array_column(array_filter($data, function ($item) use ($periode) {
                return $item['periode'] == $periode;
            }), 'putaran'));
        }
        $putaranMax = max($putaranUnik);

        return $this->filter_putaran($data, $putaranMax);
    }

    private function putaranmax($obj)
    {
        $data = [];
        $putaranMax = [];
        foreach ($obj as $item) {
            $key = $item->periode . '-' . $item->id_wilayah;
            if (!isset($putaranMax[$key])) {
                $putaranMax[$key] = $item->putaran;
            } else {
                $putaranMax[$key] = max($putaranMax[$key], $item->putaran);
            }
        }
        $data = $this->filter_putaran($obj, $putaranMax);
    }

    // 3. Distribusi Persentase PDRB ADHB 
    private function ringkasan_tabel3($obj,  $komponen, $kota, $periode)
    {

        $id_wilayah = [];
        foreach ($kota as $value) {
            array_push($id_wilayah, $value['id_wilayah']);
        }
        sort($id_wilayah);

        $data = [];
        foreach ($id_wilayah as $value) {
            $arr = $this->filter_id_wilayah($obj, $value);
            $data = array_merge($data, $arr);
        }

        $data = $this->filter_id_pdrb($data, "1");

        $dataSelected = [];
        foreach ($periode as $value) {
            $arr = $this->filter_periode($data, $value);
            $dataSelected = array_merge($dataSelected, $arr);
        }

        $dataSelected = $this->sortData($dataSelected, 3, true);
        // UNTUK SETIAP PERIODE, AMBIL DATA PUTARAN MAX NYA AJA
        $dataSelected = $this->putaranmax($dataSelected);

        $pdrb_total = $this->filter_id_komponen($dataSelected, "9");
        $dataOutput = [];
        // return $pdrb_total;

        // DI FOREACH UNTUK TIAP TAHUNQ
        foreach ($this->allTahunQ as $value) {
            $dataTahun = $this->filter_periode($dataSelected, $value);
            $dataTahunTotal = $this->filter_periode($pdrb_total, $value); // GUARANTEE 1 KALAU DATABASENYA BENAR

            // HARUSNYA GINI APPROACH YANG BENER, TAPI GATAU KENAPA SALAH
            // $nilaiTahun = $dataTahunTotal[0]->nilai;

            $nilaiTahun = 1;
            // foreach dikit ga ngaruh, orang cuma 1 nilai
            foreach ($dataTahunTotal as $value) {
                $nilaiTahun = $value->nilai;
            }

            // for each aja 
            $dataUra = [];

            $dataUra = array_filter($dataTahun, function ($item) use ($nilaiTahun) {
                return $item->nilai /= $nilaiTahun;
            });

            $dataOutput = array_merge($dataOutput, $dataUra);
        }

        $dataOutput = $this->sortData($dataOutput, 3, true);
        $dataOutput = $this->sortData($dataOutput, 2);
        $dataOutput = $this->sortData($dataOutput, 1);


        return $dataOutput;
    }

    // 5. Pertumbuhan PDRB ADHK (Q-TO-Q)
    private function ringkasan_tabel5($obj, $komponen, $kota, $periode)
    {
        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3, true);

        // membuat array untuk periode sebelumnya 
        $periodeBefore = [];
        $QBefore = 0;
        foreach ($periode as $value) {
            if (strlen($value) == 6) {
                $Q = substr($value, -1);
                $tahun =  substr($value, 0, 4);
                if ($Q == 1) {
                    $tahunBefore = $tahun - 1;
                    $QBefore = $tahunBefore . 'Q4';
                } else {
                    $Qmin1 = $Q - 1;
                    $QBefore = $tahun . 'Q' . $Qmin1;
                }
                array_push($periodeBefore, $QBefore);
            }
        }

        // get data by periodeBefore 
        $dataBefore = $this->getAllData($periodeBefore, '2', $kota);
        $dataBefore = $this->sortData($dataBefore, 3, true);

        // MENGHITUNG NILAI PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $pointer = 0;
        $dataNew = [];
        foreach ($dataCurrent as $value) {
            $dataNew = $value;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$pointer]->nilai) * 100) / abs($dataBefore[$pointer]->nilai);
            $dataOutput[] = $dataNew;
            $pointer++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 6. Pertumbuhan PDRB ADHK (Y-ON-Y)
    private function ringkasan_tabel6($obj, $komponen, $kota, $periode)
    {
        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3, true);

        // membuat array untuk periode sebelumnya 
        $periodeBefore = [];
        $QBefore = 0;
        foreach ($periode as $value) {
            if (strlen($value) == 6) {
                $Q = substr($value, -1);
                $tahun =  substr($value, 0, 4);
                $tahunBefore = $tahun - 1;
                $QBefore = $tahunBefore . 'Q' . $Q;
            } else {
                $QBefore = (string) $value - 1;
            }
            array_push($periodeBefore, $QBefore);
        }

        // get data by periodeBefore 
        $dataBefore = $this->getAllData($periodeBefore, '2', $kota);
        $dataBefore = $this->sortData($dataBefore, 3, true);

        // MENGHITUNG NILAI PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $pointer = 0;
        $dataNew = [];
        foreach ($dataCurrent as $value) {
            $dataNew = $value;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$pointer]->nilai) * 100) / abs($dataBefore[$pointer]->nilai);
            $dataOutput[] = $dataNew;
            $pointer++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 1. TABEL DISKREPANSI PDRB AHDB 
    // private function ringkasan_tabel1($periode, $komponen)
    // {
    //     $idWilayah = $this->wilayah->findAll();
    //     $selectedKomponen = $this->komponen->getById($komponen);
    //     $namaKomponen = [];
    //     $komponenArr = [];
    //     foreach ($selectedKomponen as $value) {
    //         array_push($komponenArr, $value['id_komponen']);
    //         array_push($namaKomponen, $value['komponen']);
    //     }
    //     $data = $this->putaran->getTabel1($periode, $komponenArr);

    //     // hitung diskrepansi 
    //     // 1. sum total kab/kota
    //     $totalKabKot = [];
    //     $temp = 0;
    //     foreach ($selectedKomponen as $value) {
    //         $total = 0;
    //         for ($j = 0; $j < sizeof($idWilayah) - 1; $j++) {
    //             if ($data[$temp]->id_wilayah == "3100") {
    //                 $temp++;
    //             } else {
    //                 $total += $data[$temp]->nilai;
    //                 $temp++;
    //             }
    //         }
    //         array_push($totalKabKota, $total);
    //     }

    //     return $totalKabKot;
    // }



    public function getData()
    {
        $jenisTabel = $this->request->getPost('jenisTable');
        $periode = $this->request->getPost('periode');
        sort($periode);
        $komponen = $this->komponen->findAll();
        $kota = $this->wilayah->findAll();

        switch ($jenisTabel) {
            case "11":
                // $dataRingkasan = $this->ringkasan_tabel1($periode, $komponen);
                break;
            case "13":
                $dataRingkasan = $this->ringkasan_tabel3($this->getAllData($periode, '1', $kota), $komponen, $kota, $periode);
                break;
            case "15":
                $dataRingkasan = $this->ringkasan_tabel5($this->getAllData($periode, '2', $kota), $komponen, $kota, $periode);
                break;
            case "16":
                $dataRingkasan = $this->ringkasan_tabel6($this->getAllData($periode, '2', $kota), $komponen, $kota, $periode);
                break;
            case "18":
                $dataRingkasan = $this->ringkasan_tabel8($this->getAllData($periode, '2', $kota), $komponen, $kota, $periode);
                break;
        };
        $data = [
            'komponen' => $this->komponen->get_data(),
            'dataRingkasan' => $dataRingkasan,
            'selectedPeriode' => $periode,
            'wilayah' => $this->wilayah->getAll(),
        ];

        echo json_encode($data);
    }
}
