<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'TEMPORAL | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }

    // get data from database. JenisPDRB = 1 => ADHB, 2 => ADHK. 
    public function getAllData($periode, $jenisPDRB, $kota)
    {
        // get data dari database 
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

    // sort data by multiple criteria
    public function multipleSortData($data, $sortingCriteria, $desc = false)
    {
        usort($data, function ($a, $b) use ($sortingCriteria, $desc) {
            foreach ($sortingCriteria as $criteria) {
                $result = 0;

                switch ($criteria) {
                    case 1: // Sort by periode
                        $result = strcmp($a->periode, $b->periode);
                        break;
                    case 2: // Sort by id_komponen
                        $result = strcmp($a->id_komponen, $b->id_komponen);
                        break;
                    case 3: // Sort by id_wilayah
                        $result = strcmp($a->id_wilayah, $b->id_wilayah);
                        break;
                }

                if ($result !== 0) {
                    return $desc ? -$result : $result;
                }
            }

            return 0; // If all criteria are equal, no change in order
        });

        return $data;
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
            $filteredArray = array_filter($data, function ($item) use ($id) {
                return ($item->id_wilayah != $id);
            });
            return array_values($filteredArray);
        }

        $filteredArray = [];
        foreach ($data as $item) {
            if (isset($item->id_wilayah) && $item->id_wilayah == $id) {
                $filteredArray[] = $item;
            }
        }

        return array_values($filteredArray);
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

    private function get_periodeLeft($data, $allPeriode)
    {
        // get periode di dalem data 
        $periodeData = [];
        foreach ($data as $val) {
            if (!in_array($val->periode, $periodeData)) {
                array_push($periodeData, $val->periode); // Menambahkan nilai baru hanya jika belum ada dalam array
            }
        }

        // get periode yang dipilih yang belum ada di dalam data 
        $periodeLeft = [];
        foreach ($allPeriode as $p) {
            if (!in_array($p, $periodeData)) {
                array_push($periodeLeft, $p);
            }
        }

        return $periodeLeft;
    }

    private function addDataKosong_diskrepansi($data, $allPeriode)
    {
        // get komponen dari database 
        $komponen =  $this->komponen->get_data();

        // get ID wilayah
        $wilayah = $this->wilayah->getAllIDKota();

        $periodeLeft = $this->get_periodeLeft($data, $allPeriode);

        // masukin data 0 ke array data
        if ($periodeLeft) {
            foreach ($periodeLeft as $p) {
                foreach ($komponen as $k) {
                    foreach ($wilayah as $w) {
                        $dataNew = ["id_komponen" => $k->id_komponen, "id_wilayah" => $w->id_wilayah, "nilai" => "", "periode" => $p];
                        array_push($data, json_decode(json_encode($dataNew), false));

                        // kalo id = 3100 atau 3101, di push sekali lagi biar ada 2 data masing2 (buat diskrepansi id 3100, buat total id 3101)
                        if ($w->id_wilayah == '3100' || $w->id_wilayah == '3101') {
                            array_push($data, json_decode(json_encode($dataNew), false));
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function addDataKosong_tabel4($data, $allPeriode)
    {
        // get komponen dari database 
        $komponen =  $this->komponen->get_data();

        // get ID wilayah, kecuali provinsi
        $wilayah = $this->wilayah->getAllIDKota('3100');

        $periodeLeft = $this->get_periodeLeft($data, $allPeriode);

        // masukin data 0 ke array data
        if ($periodeLeft) {
            foreach ($periodeLeft as $p) {
                foreach ($komponen as $k) {
                    foreach ($wilayah as $w) {
                        $dataNew = ["id_komponen" => $k->id_komponen, "id_wilayah" => $w->id_wilayah, "nilai" => "", "periode" => $p];
                        array_push($data, json_decode(json_encode($dataNew), false));
                    }
                }
            }
        }

        return $data;
    }

    private function addDataKosong_ringkasan($data, $allPeriode)
    {
        // get komponen dari database 
        $komponen =  $this->komponen->get_data();

        // get ID wilayah, kecuali provinsi
        $wilayah = $this->wilayah->getAllIDKota();

        $periodeLeft = $this->get_periodeLeft($data, $allPeriode);

        // masukin data 0 ke array data
        if ($periodeLeft) {
            foreach ($periodeLeft as $p) {
                foreach ($komponen as $k) {
                    foreach ($wilayah as $w) {
                        $dataNew = ["id_komponen" => $k->id_komponen, "id_wilayah" => $w->id_wilayah, "nilai" => "", "periode" => $p];
                        array_push($data, json_decode(json_encode($dataNew), false));
                    }
                }
            }
        }

        return $data;
    }

    private function addDataKosong_growth($data, $allPeriode)
    {
        // get komponen dari database 
        $komponen =  $this->komponen->get_data();

        // get ID wilayah, kecuali provinsi
        $wilayah = $this->wilayah->getAllIDKota();

        $periodeLeft = $this->get_periodeLeft($data, $allPeriode);

        // masukin data 0 ke array data
        if ($periodeLeft) {
            foreach ($periodeLeft as $p) {
                foreach ($komponen as $k) {
                    foreach ($wilayah as $w) {
                        $dataNew = ["id_komponen" => $k->id_komponen, "id_wilayah" => $w->id_wilayah, "nilai" => "", "periode" => $p];
                        array_push($data, json_decode(json_encode($dataNew), false));

                        // kalo id = 3100, di push sekali lagi biar ada data kosong untuk total kab/kot
                        if ($w->id_wilayah == '3100') {
                            array_push($data, json_decode(json_encode($dataNew), false));
                        }
                    }
                }
            }
        }

        return $data;
    }

    // mencari index objek dalam array berdasarkan nilai tertentu
    private function getIndexByProperty($arrayOfObjects, $property1Name, $targetValue1, $property2Name, $targetValue2, $property3Name, $targetValue3)
    {
        foreach ($arrayOfObjects as $index => $object) {
            if (
                $object->{$property1Name} === $targetValue1 &&
                $object->{$property2Name} === $targetValue2 &&
                $object->{$property3Name} === $targetValue3
            ) {
                return ['object' => $object, 'index' => $index];
            }
        }
        return ['object' => null, 'index' => -1]; // Return null object and -1 index if not found
    }

    // menghitung kumulatif semua kota tiap komponen
    private function cumulate($dataKota)
    {
        $dataKumulatif = [];
        $i = 0;
        $j = sizeof($dataKota);
        $kumulatif = [];

        foreach ($dataKota as $data) {
            $j--;

            if ($i == 0) {
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
            }

            if ($j == 0) {
                $dataKumulatif[] = $kumulatif;
            }

            // tiap 5 (sejumlah kota - 1) data di push ke aray $kumulatif sebagai total kota untuk komponen tersebut   
            if ($i != 0 && ($i % 6) == 0) {
                $kumulatif->id_wilayah = '3100';
                $dataKumulatif[] = $kumulatif;
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
                $i = 0;
            }

            // menghitung kumulatif semua kota tiap komponen
            $kumulatif->nilai = $kumulatif->nilai + $data->nilai;

            $i++;
        }

        return $dataKumulatif;
    }

    // menghitung indeks implisit
    private function count_implicit($adhb, $adhk, $adhbBefore, $adhkBefore)
    {
        $periodeCurrent = [];
        $periodeBefore = [];
        $i = 0;
        $dataNew = [];
        $dataNewPeriodeBefore = [];
        foreach ($adhb as $data) {
            // menghitung indeks implisit current periode 
            $dataNew = clone $data;
            $dataNew->nilai = $dataNew->nilai / $adhk[$i]->nilai;
            $periodeCurrent[] = $dataNew;

            // menghitung indeks implisit periode sebelumnya 
            $dataNewPeriodeBefore = clone $adhbBefore[$i];
            $dataNewPeriodeBefore->nilai = $dataNewPeriodeBefore->nilai / $adhkBefore[$i]->nilai;
            $periodeBefore[] = $dataNewPeriodeBefore;

            $i++;
        }

        return [$periodeCurrent, $periodeBefore];
    }

    // 1. diskrepansi PDRB ADHB
    private function ringkasan_diskrepansi($obj, $periode)
    {
        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3);
        $dataCurrent = $this->sortData($dataCurrent, 1);
        $dataCurrent = $this->sortData($dataCurrent, 2);

        //   memisahkan data provinsi dan data kota 
        $dataKota = $this->filter_id_wilayah($dataCurrent, '3100', true);
        $dataProv = $this->filter_id_wilayah($dataCurrent, '3100');

        // menghitung kumulatif semua kota tiap komponen
        $dataKumulatif = [];
        $i = 0;
        $j = sizeof($dataKota);
        $kumulatif = [];

        foreach ($dataKota as $data) {
            $j--;

            if ($i == 0) {
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
            }

            if ($j == 0) {
                $dataKumulatif[] = $kumulatif;
            }

            // tiap 5 (sejumlah kota - 1) data di push ke aray $kumulatif sebagai total kota untuk komponen tersebut   
            if ($i != 0 && ($i % 6) == 0) {
                $dataKumulatif[] = $kumulatif;
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
                $i = 0;
            }

            // menghitung kumulatif semua kota tiap komponen
            $kumulatif->nilai = $kumulatif->nilai + $data->nilai;

            $i++;
        }

        // menghitung nilai diskrepansi : PDRB DKI / kumulatif kota 
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataProv as $data) {

            $dataNew = clone $data;
            $dataNew->nilai = ($dataKumulatif[$i]->nilai - $data->nilai) / $data->nilai * 100;
            array_push($dataOutput, $dataNew); // push data diskrepansi ke array dataOutput
            array_push($dataOutput, $dataKumulatif[$i]); // push data total ke array dataOutput

            $i++;
        }

        foreach ($dataCurrent as $data) {
            array_push($dataOutput, $data);
        }

        $dataOutput = $this->addDataKosong_diskrepansi($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3); // sort by wilayah
        $dataOutput = $this->sortData($dataOutput, 1); // sort by periode
        $dataOutput = $this->sortData($dataOutput, 2); // sort by komponen

        return $dataOutput;
    }

    // 3. Distribusi Persentase PDRB ADHB 
    private function ringkasan_tabel3($obj, $periode)
    {
        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3, true);

        // memisahkan data komponen 9 
        $dataKomp9 = $this->filter_id_komponen($dataCurrent, '9');

        $nilaiKomp9 = [];
        foreach ($dataKomp9 as $data) {
            $nilaiKomp9[] = $data->nilai;
        }

        // MENGHITUNG persentase distribusi
        $dataOutput = [];
        $i = 0;
        $j = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {

            // mengecek apakah sudah 18 iterasi, kalo ya maka $j++, karena $dataKomp9Before harus berganti ke next komponen
            if ($i != 0) {
                if (($i % 18) == 0) {
                    $j++;
                }
            }

            $dataNew = clone $data;
            $dataNew->nilai = $dataNew->nilai * 100 / $nilaiKomp9[$j];
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->addDataKosong_ringkasan($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3); // sort by wilayah
        $dataOutput = $this->sortData($dataOutput, 1); // sort by periode
        $dataOutput = $this->sortData($dataOutput, 2); // sort by komponen

        return $dataOutput;
    }

    // 4. Distribusi PDRB kota terhadap provinsi 
    private function ringkasan_tabel4($obj, $periode)
    {
        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3, true);

        //   memisahkan data provinsi dan data kota 
        $dataKota = $this->filter_id_wilayah($dataCurrent, '3100', true);
        $dataProv = $this->filter_id_wilayah($dataCurrent, '3100');


        $nilaiProv = [];
        foreach ($dataProv as $data) {
            $nilaiProv[] = $data->nilai;
        }

        // menghitung nilai distribusi 
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataKota as $data) {

            $dataNew = clone $data;
            $dataNew->nilai = $data->nilai * 100 / $nilaiProv[$i];
            $dataOutput[] = $dataNew;

            $nilaiProv[$i] == end($nilaiProv) ? $i = 0 : $i++;

            // $i == sizeof($nilaiProv) - 1 ? $i = 0 :  $i++;;
        }

        $dataOutput = $this->addDataKosong_tabel4($dataOutput, $periode);

        // $dataOuput = $this->multipleSortData($dataOutput, [3, 1, 2]);
        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 5. Pertumbuhan PDRB ADHK (Q-TO-Q)
    private function ringkasan_tabel5($obj, $kota, $periode)
    {
        $periodeNoData = $this->get_periodeLeft($obj, $periode);
        $periodeWithData = array_diff($periode, $periodeNoData);

        // sort data by periode ascending, by wilayah 
        $dataCurrent = $this->sortData($obj, 3);
        $dataCurrent = $this->sortData($dataCurrent, 1);
        $dataCurrent = $this->sortData($dataCurrent, 2);

        // memisahkan data provinsi dan data kota (data current)
        $dataKota = $this->filter_id_wilayah($dataCurrent, '3100', true);

        // menghitung kumulatif semua kota tiap komponen (data current)
        $dataKumulatif = [];
        $i = 0;
        $j = sizeof($dataKota);
        $kumulatif = [];

        foreach ($dataKota as $data) {
            $j--;

            if ($i == 0) {
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
                $kumulatif->id_wilayah = '3100';
            }

            if ($j == 0) {
                $dataKumulatif[] = $kumulatif;
            }

            // tiap 5 (sejumlah kota - 1) data di push ke aray $kumulatif sebagai total kota untuk komponen tersebut   
            if ($i != 0 && ($i % 6) == 0) {
                $dataKumulatif[] = $kumulatif;
                $kumulatif = clone $data;
                $kumulatif->nilai = 0;
                $kumulatif->id_wilayah = '3100';
                $i = 0;
            }

            // menghitung kumulatif semua kota tiap komponen
            $kumulatif->nilai = $kumulatif->nilai + $data->nilai;

            $i++;
        }

        // membuat array untuk periode sebelumnya 
        $periodeBefore = [];
        $QBefore = 0;
        foreach ($periodeWithData as $value) {
            if (strlen($value) == 6) {
                $Q = substr($value, -1); // ngambil Q berapa (huruf terakhir di periode)
                $tahun =  substr($value, 0, 4); // ngambil tahun berapa (4 huruf pertama di periode)
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
        $dataBefore = $this->sortData($dataBefore, 3);
        $dataBefore = $this->sortData($dataBefore, 1);
        $dataBefore = $this->sortData($dataBefore, 2);

        // memisahkan data provinsi dan data kota (data before)
        $dataKotaBefore = $this->filter_id_wilayah($dataBefore, '3100', true);

        // menghitung kumulatif semua kota tiap komponen (data before)
        $dataKumulatifBefore = [];
        $i = 0;
        $j = sizeof($dataKotaBefore);
        $kumulatifBefore = [];

        foreach ($dataKotaBefore as $data) {
            $j--;

            if ($i == 0) {
                $kumulatifBefore = clone $data;
                $kumulatifBefore->nilai = 0;
                $kumulatifBefore->id_wilayah = '3100';
            }

            if ($j == 0) {
                $dataKumulatifBefore[] = $kumulatifBefore;
            }

            // tiap 5 (sejumlah kota - 1) data di push ke aray $kumulatif sebagai total kota untuk komponen tersebut   
            if ($i != 0 && ($i % 6) == 0) {
                $dataKumulatifBefore[] = $kumulatifBefore;
                $kumulatifBefore = clone $data;
                $kumulatifBefore->nilai = 0;
                $kumulatifBefore->id_wilayah = '3100';
                $i = 0;
            }

            // menghitung kumulatif semua kota tiap komponen
            $kumulatifBefore->nilai = $kumulatifBefore->nilai + $data->nilai;

            $i++;
        }

        // gabungin data perkota dan data kumulatif (current), sort ulang by periode
        foreach ($dataKumulatif as $data) {
            array_push($dataCurrent, $data);
        }
        $dataCurrent = $this->sortData($dataCurrent, 3, true);

        // gabungin data perkota dan data kumulatif (before), sort ulang by periode
        foreach ($dataKumulatifBefore as $data) {
            array_push($dataBefore, $data);
        }
        $dataBefore = $this->sortData($dataBefore, 3, true);

        // MENGHITUNG NILAI PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {
            $dataNew = clone $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($dataBefore[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->addDataKosong_growth($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 6. Pertumbuhan PDRB ADHK (Y-ON-Y)
    private function ringkasan_tabel6($obj, $kota, $periode)
    {
        $periodeNoData = $this->get_periodeLeft($obj, $periode);
        $periodeWithData = array_diff($periode, $periodeNoData);

        // sort data by periode ascending
        $dataCurrent = $this->sortData($obj, 3);
        $dataCurrent = $this->sortData($dataCurrent, 1);
        $dataCurrent = $this->sortData($dataCurrent, 2);

        // membuat array untuk periode sebelumnya 
        $periodeBefore = [];
        $QBefore = 0;
        foreach ($periodeWithData as $value) {
            if (strlen($value) == 6) {
                $Q = substr($value, -1);
                $tahun =  substr($value, 0, 4);
                $tahunBefore = $tahun - 1;
                $QBefore = $tahunBefore . 'Q' . $Q;
            } else {
                $QBefore = $value - 1 . "";
            }
            array_push($periodeBefore, $QBefore);
        }

        // get data by periodeBefore 
        $dataBefore = $this->getAllData($periodeBefore, '2', $kota);
        $dataBefore = $this->sortData($dataBefore, 3);
        $dataBefore = $this->sortData($dataBefore, 1);
        $dataBefore = $this->sortData($dataBefore, 2);

        // memisahkan data provinsi dan data kota
        $dataKota = $this->filter_id_wilayah($dataCurrent, '3100', true);
        $dataKotaBefore = $this->filter_id_wilayah($dataBefore, '3100', true);

        // menghitung kumulatif semua kota tiap komponen
        $dataKumulatif = $this->cumulate($dataKota);
        $dataKumulatifBefore = $this->cumulate($dataKotaBefore);

        // gabungin data perkota dan data kumulatif (current), sort ulang by periode
        foreach ($dataKumulatif as $data) {
            array_push($dataCurrent, $data);
        }
        $dataCurrent = $this->sortData($dataCurrent, 3, true);

        // gabungin data perkota dan data kumulatif (before), sort ulang by periode
        foreach ($dataKumulatifBefore as $data) {
            array_push($dataBefore, $data);
        }
        $dataBefore = $this->sortData($dataBefore, 3, true);

        // MENGHITUNG NILAI PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {
            $dataNew = clone $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($dataBefore[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }
        $dataOutput = $this->addDataKosong_growth($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 7. Pertumbuhan PDRB ADHK (C-TO-C)
    private function ringkasan_tabel7($obj, $kota, $periode)
    {
        $periodeNoData = $this->get_periodeLeft($obj, $periode);
        $periodeWithData = array_diff($periode, $periodeNoData);

        // membuat array untuk periode kumulatif tahun ini
        $periodeKumCurrent = [];
        foreach ($periodeWithData as $value) {
            $QBefore = [];
            $Q = substr($value, -1);
            $tahun =  substr($value, 0, 4);
            switch ($Q) {
                case '1':
                    $QBefore[] = $tahun . 'Q1';
                    break;
                case '2':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    break;
                case '3':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    $QBefore[] = $tahun . 'Q3';
                    break;
                case '4':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    $QBefore[] = $tahun . 'Q3';
                    $QBefore[] = $tahun . 'Q4';
                    break;
            }
            foreach ($QBefore as $quarters) {
                in_array($quarters, $periodeKumCurrent) ? null : array_push($periodeKumCurrent, $quarters);
            }
        }

        // membuat array untuk periode kumulatif tahun lalu
        $periodeKumBefore = [...$periodeKumCurrent];
        foreach ($periodeKumBefore as &$periodes) {
            $Q = substr($periodes, -1);
            $tahun =  intval(substr($periodes, 0, 4));
            $periodes = $tahun - 1 . 'Q' . $Q;
        }

        // mengambil data kumulatif tahun ini dan tahun lalu
        $dataKumCurrent = $this->getAllData($periodeKumCurrent, '2', $kota);
        $dataKumBefore = $this->getAllData($periodeKumBefore, '2', $kota);
        // sort data
        $dataKumCurrent = $this->multipleSortData($dataKumCurrent, [2, 3, 1]);
        $dataKumBefore = $this->multipleSortData($dataKumBefore, [2, 3, 1]);

        $dataSumCurrent = $this->multipleSortData($obj, [2, 3, 1]);
        $dataSumBefore = $this->multipleSortData($obj, [2, 3, 1]);
        $dataSumBefore = array_map(function ($object) {
            return clone $object;
        }, $dataSumBefore);

        // mengagregasi nilai kumulatif
        foreach ($dataSumCurrent as $dataSum) {
            $idKomponenData = $dataSum->id_komponen;
            $idWilayahData = $dataSum->id_wilayah;
            $quarterData = substr($dataSum->periode, -1);
            $tahunData = substr($dataSum->periode, 0, 4);
            $periodeData = $tahunData . 'Q' . $quarterData;
            $periodeDataBefore = $tahunData - 1 . 'Q' . $quarterData;
            $indexTarget = $this->getIndexByProperty($dataKumCurrent, 'periode', $periodeData, 'id_komponen', $idKomponenData, 'id_wilayah', $idWilayahData)['index'];
            $indexTargetBefore = $this->getIndexByProperty($dataKumBefore, 'periode', $periodeDataBefore, 'id_komponen', $idKomponenData, 'id_wilayah', $idWilayahData)['index'];

            current($dataSumBefore)->periode = $periodeDataBefore;
            switch ($quarterData) {
                case '1':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai;
                    break;
                case '2':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai;
                    break;
                case '3':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai + $dataKumCurrent[$indexTarget - 2]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai + $dataKumBefore[$indexTargetBefore - 2]->nilai;
                    break;
                case '4':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai + $dataKumCurrent[$indexTarget - 2]->nilai + $dataKumCurrent[$indexTarget - 3]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai + $dataKumBefore[$indexTargetBefore - 2]->nilai + $dataKumBefore[$indexTargetBefore - 3]->nilai;
                    break;
            }
            next($dataSumBefore);
        }

        // memisahkan data provinsi dan data kab/kot
        $dataSumCurrentKota = $this->filter_id_wilayah($dataSumCurrent, '3100', true);
        $dataSumBeforeKota = $this->filter_id_wilayah($dataSumBefore, '3100', true);

        $dataSumCurrentProv = $this->filter_id_wilayah($dataSumCurrent, '3100');
        $dataSumBeforeProv = $this->filter_id_wilayah($dataSumBefore, '3100');

        // menghitung kumulatif semua kota tiap komponen
        $dataSumCurrentKum = $this->cumulate($dataSumCurrentKota);
        $dataSumBeforeKum = $this->cumulate($dataSumBeforeKota);

        // MENGHITUNG NILAI PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $i = 0;
        foreach ($dataSumCurrentKota as $data) {
            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai - $dataSumBeforeKota[$i]->nilai) * 100) / abs($dataSumBeforeKota[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $i = 0;
        foreach ($dataSumCurrentProv as $data) {
            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai - $dataSumBeforeProv[$i]->nilai) * 100) / abs($dataSumBeforeProv[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $i = 0;
        foreach ($dataSumCurrentKum as $data) {
            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai - $dataSumBeforeKum[$i]->nilai) * 100) / abs($dataSumBeforeKum[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }
        $dataOutput = $this->addDataKosong_growth($dataOutput, $periode);

        $dataOutput = $this->multipleSortData($dataOutput, [2, 1, 3]);
        return $dataOutput;
    }

    // 8. Indeks Implisit PDRB 
    private function ringkasan_tabel8($adhb, $adhk, $periode)
    {
        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3, true);
        $dataADHK = $this->sortData($adhk, 3, true);

        // menghitung nilai indeks implisit
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataADHB as $data) {
            $dataNew = clone $data;
            $dataNew->nilai = $dataNew->nilai * 100 / $dataADHK[$i]->nilai;
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->addDataKosong_ringkasan($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 9. Pertumbuhan indeks implisit (Q-T-Q)
    private function ringkasan_tabel9($adhb, $adhk, $kota, $periode)
    {
        $periodeNoData = $this->get_periodeLeft($adhb, $periode);
        $periodeWithData = array_diff($periode, $periodeNoData);

        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3);
        $dataADHB = $this->sortData($dataADHB, 1);
        $dataADHB = $this->sortData($dataADHB, 2);
        $dataADHK = $this->sortData($adhk, 3);
        $dataADHK = $this->sortData($dataADHK, 1);
        $dataADHK = $this->sortData($dataADHK, 2);

        // membuat array untuk periode sebelumnya 
        $periodeBeforeADHB = [];
        $periodeBeforeADHK = [];
        $QBefore = 0;
        foreach ($periodeWithData as $value) {
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
                array_push($periodeBeforeADHB, $QBefore);
                array_push($periodeBeforeADHK, $QBefore);
            }
        }

        // get data by periodeBefore 
        $dataBeforeADHB = $this->getAllData($periodeBeforeADHB, '1', $kota);
        $dataBeforeADHK = $this->getAllData($periodeBeforeADHK, '2', $kota);

        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 3);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 1);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 2);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 3);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 1);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 2);

        // memisahkan data provinsi dan kab/kot (untuk menghitung total kab/kot)
        $dataADHBKota = $this->filter_id_wilayah($dataADHB, '3100', true);
        $dataADHKKota = $this->filter_id_wilayah($dataADHK, '3100', true);
        $dataBeforeADHBKota = $this->filter_id_wilayah($dataBeforeADHB, '3100', true);
        $dataBeforeADHKKota = $this->filter_id_wilayah($dataBeforeADHK, '3100', true);

        $dataADHBProv = $this->filter_id_wilayah($dataADHB, '3100');
        $dataADHKProv = $this->filter_id_wilayah($dataADHK, '3100');
        $dataBeforeADHBProv = $this->filter_id_wilayah($dataBeforeADHB, '3100');
        $dataBeforeADHKProv = $this->filter_id_wilayah($dataBeforeADHK, '3100');

        // menghitung kumulatif semua kota tiap komponen
        $dataADHBKum = $this->cumulate($dataADHBKota);
        $dataADHKKum = $this->cumulate($dataADHKKota);
        $dataBeforeADHBKum = $this->cumulate($dataBeforeADHBKota);
        $dataBeforeADHKKum = $this->cumulate($dataBeforeADHKKota);

        // Menghitung indeks implisit tiap periode
        [$periodeCurrentKota, $periodeBeforeKota] = $this->count_implicit($dataADHBKota, $dataADHKKota, $dataBeforeADHBKota, $dataBeforeADHKKota);
        [$periodeCurrentProv, $periodeBeforeProv] = $this->count_implicit($dataADHBProv, $dataADHKProv, $dataBeforeADHBProv, $dataBeforeADHKProv);
        [$periodeCurrentKum, $periodeBeforeKum] = $this->count_implicit($dataADHBKum, $dataADHKKum, $dataBeforeADHBKum, $dataBeforeADHKKum);

        // menghitung nilai pertumbuhan indeks implisit
        $dataOutput = [];

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentKota as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeKota[$i]->nilai) * 100) / abs($periodeBeforeKota[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentProv as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeProv[$i]->nilai) * 100) / abs($periodeBeforeProv[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentKum as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeKum[$i]->nilai) * 100) / abs($periodeBeforeKum[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $dataOutput = $this->addDataKosong_growth($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 10. Pertumbuhan indeks implisit (Y-ON-Y)
    private function ringkasan_tabel10($adhb, $adhk, $kota, $periode)
    {
        $periodeNoData = $this->get_periodeLeft($adhb, $periode);
        $periodeWithData = array_diff($periode, $periodeNoData);

        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3);
        $dataADHB = $this->sortData($dataADHB, 1);
        $dataADHB = $this->sortData($dataADHB, 2);
        $dataADHK = $this->sortData($adhk, 3);
        $dataADHK = $this->sortData($dataADHK, 1);
        $dataADHK = $this->sortData($dataADHK, 2);

        // membuat array untuk periode sebelumnya 
        $periodeBeforeADHB = [];
        $periodeBeforeADHK = [];
        $QBefore = 0;
        foreach ($periodeWithData as $value) {
            if (strlen($value) == 6) {
                $Q = substr($value, -1);
                $tahun =  substr($value, 0, 4);
                $tahunBefore = $tahun - 1;
                $QBefore = $tahunBefore . 'Q' . $Q;
            } else {
                $QBefore = $value - 1 . "";
            }

            array_push($periodeBeforeADHB, $QBefore);
            array_push($periodeBeforeADHK, $QBefore);
        }

        // get data by periodeBefore 
        $dataBeforeADHB = $this->getAllData($periodeBeforeADHB, '1', $kota);
        $dataBeforeADHK = $this->getAllData($periodeBeforeADHK, '2', $kota);

        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 3);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 1);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 2);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 3);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 1);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 2);

        // memisahkan data provinsi dan kab/kot (untuk menghitung total kab/kot)
        $dataADHBKota = $this->filter_id_wilayah($dataADHB, '3100', true);
        $dataADHKKota = $this->filter_id_wilayah($dataADHK, '3100', true);
        $dataBeforeADHBKota = $this->filter_id_wilayah($dataBeforeADHB, '3100', true);
        $dataBeforeADHKKota = $this->filter_id_wilayah($dataBeforeADHK, '3100', true);

        $dataADHBProv = $this->filter_id_wilayah($dataADHB, '3100');
        $dataADHKProv = $this->filter_id_wilayah($dataADHK, '3100');
        $dataBeforeADHBProv = $this->filter_id_wilayah($dataBeforeADHB, '3100');
        $dataBeforeADHKProv = $this->filter_id_wilayah($dataBeforeADHK, '3100');

        // menghitung kumulatif semua kota tiap komponen
        $dataADHBKum = $this->cumulate($dataADHBKota);
        $dataADHKKum = $this->cumulate($dataADHKKota);
        $dataBeforeADHBKum = $this->cumulate($dataBeforeADHBKota);
        $dataBeforeADHKKum = $this->cumulate($dataBeforeADHKKota);

        // Menghitung indeks implisit tiap periode
        [$periodeCurrentKota, $periodeBeforeKota] = $this->count_implicit($dataADHBKota, $dataADHKKota, $dataBeforeADHBKota, $dataBeforeADHKKota);
        [$periodeCurrentProv, $periodeBeforeProv] = $this->count_implicit($dataADHBProv, $dataADHKProv, $dataBeforeADHBProv, $dataBeforeADHKProv);
        [$periodeCurrentKum, $periodeBeforeKum] = $this->count_implicit($dataADHBKum, $dataADHKKum, $dataBeforeADHBKum, $dataBeforeADHKKum);

        // menghitung nilai pertumbuhan indeks implisit
        $dataOutput = [];

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentKota as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeKota[$i]->nilai) * 100) / abs($periodeBeforeKota[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentProv as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeProv[$i]->nilai) * 100) / abs($periodeBeforeProv[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $i = 0;
        $temp = [];
        foreach ($periodeCurrentKum as $data) {
            $temp = clone $data;
            $temp->nilai = (($temp->nilai - $periodeBeforeKum[$i]->nilai) * 100) / abs($periodeBeforeKum[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $dataOutput = $this->addDataKosong_growth($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 11. Sumber pertumbuhan (Q-T-Q) 
    private function ringkasan_tabel11($obj, $kota, $periode)
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

        // memisahkan data komponen 9 
        $dataKomp9Before = $this->filter_id_komponen($dataBefore, '9');

        $nilaiKomp9 = [];
        foreach ($dataKomp9Before as $data) {
            $nilaiKomp9[] = $data->nilai;
        }

        // menghitung sumber pertumbuhan 
        $dataOutput = [];
        $i = 0;
        $j = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {

            // mengecek apakah sudah 18 iterasi, kalo ya maka $j++, karena $dataKomp9Before harus berganti ke next komponen
            if ($i != 0) {
                if (($i % 18) == 0) {
                    $j++;
                }
            }

            $dataNew = clone $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($nilaiKomp9[$j]);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->addDataKosong_ringkasan($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 12. Sumber pertumbuhan (Y-ON-Y) 
    public function ringkasan_tabel12($obj, $kota, $periode)
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
                $QBefore = $value - 1 . "";
            }
            array_push($periodeBefore, $QBefore);
        }
        // get data by periodeBefore 
        $dataBefore = $this->getAllData($periodeBefore, '2', $kota);
        $dataBefore = $this->sortData($dataBefore, 3, true);

        // memisahkan data komponen 9 
        $dataKomp9Before = $this->filter_id_komponen($dataBefore, '9');

        $nilaiKomp9 = [];
        foreach ($dataKomp9Before as $data) {
            $nilaiKomp9[] = $data->nilai;
        }

        // menghitung sumber pertumbuhan 
        $dataOutput = [];
        $i = 0;
        $j = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {

            // mengecek apakah sudah 18 iterasi, kalo ya maka $j++, karena $dataKomp9Before harus berganti ke next komponen
            if ($i != 0) {
                if (($i % 18) == 0) {
                    $j++;
                }
            }

            $dataNew = clone $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai)  * 100) / abs($nilaiKomp9[$j]);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->addDataKosong_ringkasan($dataOutput, $periode);

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 12. Sumber Pertumbuhan (C-TO-C) 
    private function ringkasan_tabel13($obj, $kota, $periode)
    {
        // membuat array untuk periode kumulatif tahun ini
        $periodeKumCurrent = [];
        foreach ($periode as $value) {
            $QBefore = [];
            $Q = substr($value, -1);
            $tahun =  substr($value, 0, 4);
            switch ($Q) {
                case '1':
                    $QBefore[] = $tahun . 'Q1';
                    break;
                case '2':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    break;
                case '3':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    $QBefore[] = $tahun . 'Q3';
                    break;
                case '4':
                    $QBefore[] = $tahun . 'Q1';
                    $QBefore[] = $tahun . 'Q2';
                    $QBefore[] = $tahun . 'Q3';
                    $QBefore[] = $tahun . 'Q4';
                    break;
            }
            foreach ($QBefore as $quarters) {
                in_array($quarters, $periodeKumCurrent) ? null : array_push($periodeKumCurrent, $quarters);
            }
        }

        // membuat array untuk periode kumulatif tahun lalu
        $periodeKumBefore = [...$periodeKumCurrent];
        foreach ($periodeKumBefore as &$periodes) {
            $Q = substr($periodes, -1);
            $tahun =  intval(substr($periodes, 0, 4));
            $periodes = $tahun - 1 . 'Q' . $Q;
        }

        // mengambil data kumulatif tahun ini dan tahun lalu
        $dataKumCurrent = $this->getAllData($periodeKumCurrent, '2', $kota);
        $dataKumBefore = $this->getAllData($periodeKumBefore, '2', $kota);
        // sort data
        $dataKumCurrent = $this->multipleSortData($dataKumCurrent, [2, 3, 1]);
        $dataKumBefore = $this->multipleSortData($dataKumBefore, [2, 3, 1]);

        $dataSumCurrent = $this->multipleSortData($obj, [2, 3, 1]);
        $dataSumBefore = $this->multipleSortData($obj, [2, 3, 1]);

        $dataSumBefore = array_map(function ($object) {
            return clone $object;
        }, $dataSumBefore);

        // mengagregasi nilai kumulatif
        foreach ($dataSumCurrent as $dataSum) {
            $idKomponenData = $dataSum->id_komponen;
            $idWilayahData = $dataSum->id_wilayah;
            $quarterData = substr($dataSum->periode, -1);
            $tahunData = substr($dataSum->periode, 0, 4);
            $periodeData = $tahunData . 'Q' . $quarterData;
            $periodeDataBefore = $tahunData - 1 . 'Q' . $quarterData;
            $indexTarget = $this->getIndexByProperty($dataKumCurrent, 'periode', $periodeData, 'id_komponen', $idKomponenData, 'id_wilayah', $idWilayahData)['index'];
            $indexTargetBefore = $this->getIndexByProperty($dataKumBefore, 'periode', $periodeDataBefore, 'id_komponen', $idKomponenData, 'id_wilayah', $idWilayahData)['index'];

            current($dataSumBefore)->periode = $periodeDataBefore;
            switch ($quarterData) {
                case '1':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai;
                    break;
                case '2':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai;
                    break;
                case '3':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai + $dataKumCurrent[$indexTarget - 2]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai + $dataKumBefore[$indexTargetBefore - 2]->nilai;
                    break;
                case '4':
                    $dataSum->nilai = $dataKumCurrent[$indexTarget]->nilai + $dataKumCurrent[$indexTarget - 1]->nilai + $dataKumCurrent[$indexTarget - 2]->nilai + $dataKumCurrent[$indexTarget - 3]->nilai;
                    current($dataSumBefore)->nilai = $dataKumBefore[$indexTargetBefore]->nilai + $dataKumBefore[$indexTargetBefore - 1]->nilai + $dataKumBefore[$indexTargetBefore - 2]->nilai + $dataKumBefore[$indexTargetBefore - 3]->nilai;
                    break;
            }
            next($dataSumBefore);
        }

        // MENGHITUNG NILAI SUMBER PERTUMBUHAN (DATA OUTPUT)
        $dataOutput = [];
        $i = 0;
        foreach ($dataSumCurrent as $data) {
            $PDRBIndex = $this->getIndexByProperty($dataSumBefore, 'periode', $dataSumBefore[$i]->periode, 'id_komponen', '9', 'id_wilayah', $dataSumBefore[$i]->id_wilayah)['index'];
            $data->nilai = ($data->nilai - $dataSumBefore[$i]->nilai) * 100 / $dataSumBefore[$PDRBIndex]->nilai;
            $dataOutput[] = $data;
            $i++;
        }

        $dataOutput = $this->addDataKosong_ringkasan($dataOutput, $periode);

        $dataOutput = $this->multipleSortData($dataOutput, [2, 1, 3]);
        return $dataOutput;
    }

    public function getData()
    {
        $jenisTabel = $this->request->getPost('jenisTable');
        $periode = $this->request->getPost('periode');
        if (empty($periode)) {
            $getPeriode = function () {
                $month = date('n');
                $quarter = ceil($month / 3);

                $year = date('Y');

                if ($quarter == 1) {
                    $previousQuarter = 4;
                    $year = $year - 1;
                } else {
                    $previousQuarter = $quarter - 1;
                }
                return $year . 'Q' . $previousQuarter;
            };

            $periode = [$getPeriode()];
        }
        sort($periode);
        $komponen = $this->komponen->findAll();
        $kota = $this->wilayah->findAll();
        $wilayah = [];
        if ($jenisTabel == "14") {
            $wilayah = $this->wilayah->whereNotIn('id_wilayah', [3100])->findAll();
        } else {
            $wilayah = $this->wilayah->getAll();
        }

        switch ($jenisTabel) {
            case "11":
                $dataRingkasan = $this->ringkasan_diskrepansi($this->getAllData($periode, '1', $kota), $periode);
                break;
            case "12":
                $dataRingkasan = $this->ringkasan_diskrepansi($this->getAllData($periode, '2', $kota), $periode);
                break;
            case "13":
                $dataRingkasan = $this->ringkasan_tabel3($this->getAllData($periode, '1', $kota), $periode);
                break;
            case "14":
                $dataRingkasan = $this->ringkasan_tabel4($this->getAllData($periode, '1', $kota), $periode);
                break;
            case "15":
                $dataRingkasan = $this->ringkasan_tabel5($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "16":
                $dataRingkasan = $this->ringkasan_tabel6($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "17":
                $dataRingkasan = $this->ringkasan_tabel7($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "18":
                $dataRingkasan = $this->ringkasan_tabel8($this->getAllData($periode, '1', $kota), $this->getAllData($periode, '2', $kota), $periode);
                break;
            case "19":
                $dataRingkasan = $this->ringkasan_tabel9($this->getAllData($periode, '1', $kota), $this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "20":
                $dataRingkasan = $this->ringkasan_tabel10($this->getAllData($periode, '1', $kota), $this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "21":
                $dataRingkasan = $this->ringkasan_tabel11($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "22":
                $dataRingkasan = $this->ringkasan_tabel12($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "23":
                $dataRingkasan = $this->ringkasan_tabel13($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
        };

        $data = [
            'komponen' => $this->komponen->get_data(),
            'dataRingkasan' => $dataRingkasan,
            'selectedPeriode' => $periode,
            'wilayah' => $wilayah,
            'jenisTabel' => $jenisTabel,
        ];

        if ($export = $this->request->getPost('export')) {
            $namaTabel = $this->request->getPost('namaTable');
            $data['namaTabel'] = $namaTabel;
            echo json_encode($data);
        } else {
            echo json_encode($data);
        };
    }

    public function exportExcel($jenisTabel, $periode, $nama)
    {
        $periodeArr = explode(",", $periode);
        sort($periodeArr);
        $komponen = $this->komponen->findAll();
        sort($komponen);
        $kota = $this->wilayah->findAll();
        $wilayah = [];
        if ($jenisTabel == "14") {
            $wilayah = $this->wilayah->whereNotIn('id_wilayah', [3100])->findAll();
            sort($wilayah);
        } else {
            $wilayah = $this->wilayah->getAll();
        }

        $title = [$nama];

        switch ($jenisTabel) {
            case "11":
                $dataRingkasan = $this->ringkasan_diskrepansi($this->getAllData($periodeArr, '1', $kota), $periodeArr);
                break;
            case "12":
                $dataRingkasan = $this->ringkasan_diskrepansi($this->getAllData($periodeArr, '2', $kota), $periodeArr);
                break;
            case "13":
                $dataRingkasan = $this->ringkasan_tabel3($this->getAllData($periodeArr, '1', $kota), $periodeArr);
                break;
            case "14":
                $dataRingkasan = $this->ringkasan_tabel4($this->getAllData($periodeArr, '1', $kota), $periodeArr);
                break;
            case "15":
                $dataRingkasan = $this->ringkasan_tabel5($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "16":
                $dataRingkasan = $this->ringkasan_tabel6($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "17":
                $dataRingkasan = $this->ringkasan_tabel7($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "18":
                $dataRingkasan = $this->ringkasan_tabel8($this->getAllData($periodeArr, '1', $kota), $this->getAllData($periodeArr, '2', $kota), $periodeArr);
                break;
            case "19":
                $dataRingkasan = $this->ringkasan_tabel9($this->getAllData($periodeArr, '1', $kota), $this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "20":
                $dataRingkasan = $this->ringkasan_tabel10($this->getAllData($periodeArr, '1', $kota), $this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "21":
                $dataRingkasan = $this->ringkasan_tabel11($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "22":
                $dataRingkasan = $this->ringkasan_tabel12($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
            case "23":
                $dataRingkasan = $this->ringkasan_tabel13($this->getAllData($periodeArr, '2', $kota), $kota, $periodeArr);
                break;
        };

        $currentDateTime = date("Y-m-d H_i_s"); // Format "2023-09-30 14_37_31"

        // Konfigurasi untuk generate excel
        require_once ROOTPATH . 'vendor/autoload.php';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $dataSheet = [];
        // header table 
        $columnHeader = ['Komponen'];
        $columnHeader2 = [];
        foreach ($periodeArr as $col) {
            array_push($columnHeader, $col);
        }
        array_push($dataSheet, $columnHeader);
        if ($jenisTabel == "11" || $jenisTabel == "12") {
            array_push($columnHeader2, 'Diskrepansi');
            array_push($columnHeader2, 'Provinsi DKI Jakarta');
            array_push($columnHeader2, 'Total Kab/Kota');

            foreach ($wilayah as $col) {
                if ($col['id_wilayah'] != "3100") {
                    array_push($columnHeader2, $col['wilayah']);
                }
            }
        } else {
            foreach ($wilayah as $col) {
                array_push($columnHeader2, $col['wilayah']);
            }
        }

        array_push($dataSheet, $columnHeader2);

        // isi tabel 
        $komponenData = [];
        $dataPDRB = [];
        $temp = -1;

        foreach ($komponen as $rows) {
            for ($i = 0; $i < sizeof($periodeArr); $i++) {
                for ($col = 0; $col < sizeof($columnHeader2) + 1; $col++) {
                    if ($col == 0 && $i == 0) {
                        if ($rows['id_komponen'] == 1 || $rows['id_komponen'] == 2 || $rows['id_komponen'] == 3 || $rows['id_komponen'] == 4 || $rows['id_komponen'] == 5 || $rows['id_komponen'] == 6 || $rows['id_komponen'] == 7 || $rows['id_komponen'] == 8) {
                            $komponen = $rows['id_komponen'] . ". " . $rows['komponen'];
                        } elseif ($rows['id_komponen'] == 9) {
                            $komponen = $rows['komponen'];
                        } else {
                            $komponen = "     " . $rows['id_komponen'] . ". " . $rows['komponen'];
                        };
                        array_push($dataPDRB, $komponen);
                    } else if ($col != 0) {
                        $temp++;
                        array_push($dataPDRB, $dataRingkasan[$temp]->nilai);
                    }
                }
            }
            array_push($komponenData, $dataPDRB);
            $dataPDRB = [];
        }
        array_push($dataSheet, $komponenData);

        // masukin data ke excel dan masukin header tabel
        $sheet->fromArray([$title]);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // pengaturan merge cell kolom 1
        $sheet->mergeCells('A3:A4');    // merge cell header kolom 1      
        $jumlahSelGabung  = count($wilayah);
        if ($jenisTabel == "11" || $jenisTabel == "12") {
            $jumlahSelGabung += 2;
        }
        // $colIndex = 65;
        $colIndex = 1;
        $startRow1 = 3;
        $startRow2 = 4;
        $i = 0;
        foreach ($dataSheet[0] as $value) {
            if ($i == 0) {
                $sheet->setCellValue('A3', $value);
            } else {
                $startColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $colIndex = $colIndex + $jumlahSelGabung;
                $endColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $cell1Start = $startColumn . $startRow1;
                $cell1End = $endColumn . $startRow1;
                $cell2Start = $startColumn . $startRow2;
                $range1 = $cell1Start . ":" . $cell1End;

                // merge cell 
                $sheet->mergeCells($range1);

                // set cell value untuk header 
                $sheet->setCellValue($cell1Start, $value);
                $sheet->fromArray($dataSheet[1], null, $cell2Start);
            }
            $i++;
        };

        $sheet->fromArray($dataSheet[2], null, 'A5');

        // bold dan center table header 
        foreach ($sheet->getColumnIterator() as $column) {
            $row = $column->getColumnIndex() . '3';
            $row2 = $column->getColumnIndex() . '4';
            $sheet->getStyle($row)->getFont()->setBold(true);
            $sheet->getStyle($row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($row2)->getFont()->setBold(true);
            $sheet->getStyle($row2)->getAlignment()->setHorizontal('center');
        }

        // table border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ]
            ]
        ];

        // setting column width, border, number format 
        foreach ($sheet->getColumnIterator() as $column) {
            foreach ($sheet->getRowIterator() as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $cell->getStyle()->applyFromArray($styleArray);
                    if ($row->getRowIndex() != 3 && $row->getRowIndex() != 4) {
                        if ($column->getColumnIndex() != 'A') {
                            $cell->getStyle()->getNumberFormat()->setFormatCode('#,##0.00');
                        }
                    }
                }
            }
            if ($column->getColumnIndex() == 'A') {
                $sheet->getColumnDimension($column->getColumnIndex())->setWidth(45);
            } else {
                $sheet->getColumnDimension($column->getColumnIndex())->setWidth(20);
            }
        }

        // download file excel 
        $filename = $nama . " " . $currentDateTime . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
}
