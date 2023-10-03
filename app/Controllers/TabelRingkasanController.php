<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PDO;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\WilayahModel;

use function PHPSTORM_META\type;

class TabelRingkasanController extends BaseController
{

    protected $nilaiDiskrepansi;
    protected $komponen;
    protected $putaran;
    protected $wilayah;
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
        $allData = $this->putaran->findAll();

        // ID_PUTARAN UNIQUE
        $periodeUnik =  array_unique(array_column($allData, 'periode'));
        $this->allTahunQ = $periodeUnik;

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
        $tahunUnik = array_unique(array_column($allData, 'tahun'));
        $this->allTahunQ_OnlyTahunForFilterTahunan = $tahunUnik;

        // ID_KOMPONEN UNIQUE
        // $this->allKomponen = array_unique(array_column($obj, 'id_komponen'));
        $komponenUnik = array_unique(array_column($allData, 'id_komponen'));
        $this->allKomponen = $komponenUnik;
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

    public function getAllData()
    {
        $data = $this->putaran->findAll();
        return $data;
    }

    // sort data 
    public function sortData($data, $kode)
    {
        usort($data, function ($a, $b) use ($kode) {
            if ($kode == 1) { // 1 IS SORT BY periode
                return strcmp($a['periode'], $b['periode']);
            } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                return strcmp($a['id_putaran'], $b['id_putaran']);
            }
        });

        return $data;
    }

    // FILTER periode : MISAL 2017, 2018Q3
    private function filter_periode($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['periode'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['periode'] == $id);
        });
    }
    // private function filter_periode($data, $id, $exclude = false)
    // {
    //     if (!is_array($id)) {
    //         $id = [$id];
    //     }

    //     if ($exclude) {
    //         return $filteredArray = array_filter($data, function ($item) use ($id) {
    //             return !in_array($item['periode'], $id);
    //         });
    //     }


    //     return $filteredArray = array_filter($data, function ($item) use ($id) {
    //         return in_array($item['periode'], $id);
    //     });

    //     
    // }

    // FILTER id_kuartal (PERIODE KUARTAL BERAPA) : MISAL 1 , 5(TAHUN KESELURUHAN)
    private function filter_id_kuartal($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['id_kuartal'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['id_kuartal'] == $id);
        });
    }

    // FILTER id_komponen : MISAL 1a
    private function filter_id_komponen($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['id_komponen'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['id_komponen'] == $id);
        });
    }

    // FILTER id_wilayah (TINGKAT KOTA/PROVINSI) : MISAL 3172
    private function filter_id_wilayah($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['id_wilayah'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['id_wilayah'] == $id);
        });
    }

    // FILTER id_pdrb (ADHB / ADHK) : MISAL 1 (adhb),2 (adhk)
    private function filter_id_pdrb($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['id_pdrb'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['id_pdrb'] == $id);
        });
    }

    // FILTER tahun : MISAL 2017
    // private function filter_tahun($data, $id, $exclude = false)
    // {
    //     if (!is_array($id)) {
    //         $id = [$id]; // Ubah $id menjadi array jika belum berupa array
    //     }

    //     if ($exclude) {
    //         return $filteredArray = array_filter($data, function ($item) use ($id) {
    //             return ($item['tahun !'] = $id);
    //         });
    //     }

    //     return $filteredArray = array_filter($data, function ($item) use ($id) {
    //         return in_array($item['tahun'], $id); // Filter data yang ADA dalam array $id
    //     });
    // }

    private function filter_tahun($data, $id, $exclude = false)
    {
        // return $data;
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->tahun != $id);
            });
        }

        return array_filter($data, function ($item) use ($id) {
            return ($item->tahun == $id);
        });
    }

    // FILTER putaran 
    private function filter_putaran($data, $id, $exclude = false)
    {
        if ($exclude) {
            return $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item['putaran'] != $id);
            });
        }

        return $filteredArray = array_filter($data, function ($item) use ($id) {
            return ($item['putaran'] == $id);
        });
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

    // 1. TABEL DISKREPANSI PDRB AHDB 
    private function ringkasan_tabel1($periode, $komponen)
    {
        $idWilayah = $this->wilayah->findAll();
        $selectedKomponen = $this->komponen->getById($komponen);
        $namaKomponen = [];
        $komponenArr = [];
        foreach ($selectedKomponen as $value) {
            array_push($komponenArr, $value['id_komponen']);
            array_push($namaKomponen, $value['komponen']);
        }
        $data = $this->putaran->getTabel1($periode, $komponenArr);

        // hitung diskrepansi 
        // 1. sum total kab/kota
        $totalKabKot = [];
        $temp = 0;
        foreach ($selectedKomponen as $value) {
            $total = 0;
            for ($j = 0; $j < sizeof($idWilayah) - 1; $j++) {
                if ($data[$temp]->id_wilayah == "3100") {
                    $temp++;
                } else {
                    $total += $data[$temp]->nilai;
                    $temp++;
                }
            }

            array_push($totalKabKota, $total);
        }

        return $namaKomponen;
    }

    // 5. Pertumbuhan PDRB ADHK (Y-ON-Y) 
    private function ringkasan_tabel5($obj, $periode)
    {
        $data = $this->filter_id_pdrb($obj, "2");

        // Y-Y artinya hanya menghitung periode kuartal dalam tahunan saja, total tahun di exclude (? Need to be clarify)
        $data = $this->filter_id_kuartal($data, "5", true);

        // $data = $this->filter_periode($data, $periode);

        // DI FOREACH UNTUK TIAP TAHUNQ_OnlyQ (karena kuartal)
        // FOR EACH DIMULAI DARI 1 KARENA YA UNTUK MENGHITUNG TAHUNQ (CORRECTED : REVERSE)
        $dataOutput = [];
        for ($i = sizeof($this->allTahunQ_OnlyQ) - 1; $i > 3; $i--) {

            $dataTahun = $this->filter_periode($data, $this->allTahunQ_OnlyQ[$i]);
            // $dataTahun = $this->data_putaran_terakhir($dataTahun);

            $dataTahunBefore = $this->filter_periode($data, $this->allTahunQ_OnlyQ[$i - 4]);
            // $dataTahunBefore = $this->data_putaran_terakhir($dataTahun);
            return $dataTahun;
            // sort data biar mantap
            // array_multisort($this->allKomponen, SORT_ASC, $dataTahun);
            // array_multisort($this->allKomponen, SORT_ASC, $dataTahunBefore);


            // echo "IN THE DESERT " . $this->allTahunQ_OnlyQ[$i] . "  " . $this->allTahunQ_OnlyQ[$i - 4] . "<br>";
            // d($dataTahun);
            // d($dataTahunBefore);
            $dataTahunUraa = array_map(function ($itemTahun, $itemBefore) {
                $itemTahun['nilai'] =  (($itemTahun['nilai']  - $itemBefore['nilai']) * 100) / abs($itemBefore['nilai']);
                return $itemTahun;
            }, $dataTahun, $dataTahunBefore);
            $dataOutput = array_merge($dataOutput, $dataTahunUraa);
            // return $dataOutput;
            // sort berurutan by periode -> id_komponen -> id_wilayah 
            usort($dataOutput, function ($a, $b) {

                $byTahun = strcmp($a['tahun'], $b['tahun']);
                if ($byTahun !== 0) {
                    return $byTahun;
                }

                $byQuartal = strcmp($a['id_kuartal'], $b['id_kuartal']);
                if ($byQuartal !== 0) {
                    return $byQuartal;
                }

                // $byPeriode = strcmp($a['periode'], $b['periode']);
                // if ($byPeriode !== 0) {
                //     return $byPeriode;
                // }

                $byIdKomponen = strcmp($a['id_komponen'], $b['id_komponen']);
                if ($byIdKomponen !== 0) {
                    return $byIdKomponen;
                }

                $byIdWilayah = strcmp($a['id_wilayah'], $b['id_wilayah']);
                if ($byIdWilayah !== 0) {
                    return $byIdWilayah;
                }
            });

            return $dataOutput;
        }


        $dataOutput = $this->sortData($dataOutput, 1);
        return $dataOutput;
    }

    private function filter_tahun_new($data, $tahun, $exclude = false)
    {
        $filteredData = [];

        if ($exclude) {
            foreach ($data as $item) {
                $item['tahun'] != $tahun ? $filteredData[] = $item : '';
            }
        }

        foreach ($data as $item) {
            $item['tahun'] == $tahun ? $filteredData[] = $item : '';
        }

        return $filteredData;
    }

    private function filter_kuartal_new($data, $kuartal, $exclude = false)
    {
        $filteredData = [];

        if ($exclude) {
            foreach ($data as $item) {
                $item['id_kuartal'] != $kuartal ? $filteredData[] = $item : '';
            }
        }

        foreach ($data as $item) {
            $item['id_kuartal'] == $kuartal ? $filteredData[] = $item : '';
        }

        return $filteredData;
    }

    private function sortData_new($data, $kode)
    {
        usort($data, function ($a, $b) use ($kode) {
            if ($kode == 1) { // 1 IS SORT BY periode
                return strcmp($a['periode'], $b['periode']);
            } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                return strcmp($a['id_komponen'], $b['id_komponen']);
            }
        });

        return $data;
    }

    private function filter_periode_new($data, $periode, $exclude = false)
    {
        $filteredData = [];

        if ($exclude) {
            foreach ($data as $item) {
                $item['periode'] != $periode ? $filteredData[] = $item : '';
            }
        }

        foreach ($data as $item) {
            $item['periode'] == $periode ? $filteredData[] = $item : '';
        }

        return $filteredData;
    }




    public function getData()
    {
        $jenisTabel = $this->request->getPost('jenisPDRB');
        $periode = $this->request->getPost('periode');
        $komponen = $this->request->getPost('komponen');

        $dataRingkasan = [];
        switch ($jenisTabel) {
            case "11":
                $dataRingkasan = $this->ringkasan_tabel1($periode, $komponen);
                break;
            case "15":
                $dataRingkasan = $this->ringkasan_tabel5($this->getAllData(), $periode);
                break;
                // case "17":
                //     $dataRingkasan = $this->ringkasan_tabel7($this->getAllData(), $periode);
        };
        // $data = [
        //     'komponen' => $this->komponen->get_data(),
        //     'dataRingkasan' => $dataRingkasan,
        //     'selectedPeriode' => $periode,
        //     'wilayah' => $this->wilayah->findAll(),
        // ];

        // echo json_encode($data);
        echo json_encode($dataRingkasan);
    }
}
