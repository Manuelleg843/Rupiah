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
    private function ringkasan_tabel3($obj, $kota, $periode)
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

            $dataNew = $data;
            $dataNew->nilai = $dataNew->nilai / $nilaiKomp9[$j];
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // ini masih belum bener 
    // 4. Distribusi PDRB kota terhadap provinsi 
    private function ringkasan_tabel4($obj, $kota, $periode)
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

        // return $dataKota;

        // menghitung nilai distribusi 
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataKota as $data) {

            $dataNew = $data;
            $dataNew->nilai = $dataNew->nilai / $nilaiProv[$i] * 100;
            $dataOutput[] = $dataNew;


            $i == sizeof($nilaiProv) - 1 ? $i = 0 :  $i++;;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 5. Pertumbuhan PDRB ADHK (Q-TO-Q)
    private function ringkasan_tabel5($obj, $kota, $periode)
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
        $i = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {
            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($dataBefore[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 6. Pertumbuhan PDRB ADHK (Y-ON-Y)
    private function ringkasan_tabel6($obj, $kota, $periode)
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
        $i = 0;
        $dataNew = [];
        foreach ($dataCurrent as $data) {
            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($dataBefore[$i]->nilai);
            $dataOutput[] = $dataNew;
            $i++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 8. Indeks Implisit PDRB 
    private function ringkasan_tabel8($adhb, $adhk, $kota, $periode)
    {
        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3, true);
        $dataADHK = $this->sortData($adhk, 3, true);

        // menghitung nilai indeks implisit
        $dataOutput = [];
        $i = 0;
        $dataNew = [];
        foreach ($dataADHB as $data) {
            $dataNew = $data;
            $dataNew->nilai = $dataNew->nilai / $dataADHK[$i]->nilai;
            $dataOutput[] = $dataNew;
            $i++;
        }
        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 9. Pertumbuhan indeks implisit (Q-T-Q)
    private function ringkasan_tabel9($adhb, $adhk, $kota, $periode)
    {
        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3, true);
        $dataADHK = $this->sortData($adhk, 3, true);

        // membuat array untuk periode sebelumnya 
        $periodeBeforeADHB = [];
        $periodeBeforeADHK = [];
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
                array_push($periodeBeforeADHB, $QBefore);
                array_push($periodeBeforeADHK, $QBefore);
            }
        }

        // get data by periodeBefore 
        $dataBeforeADHB = $this->getAllData($periodeBeforeADHB, '1', $kota);
        $dataBeforeADHK = $this->getAllData($periodeBeforeADHK, '2', $kota);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 3, true);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 3, true);

        // Menghitung indeks implisit tiap periode
        $periodeCurrent = [];
        $periodeBefore = [];
        $i = 0;
        $dataNew = [];
        $dataNewPeriodeBefore = [];
        foreach ($dataADHB as $data) {
            // menghitung indeks implisit current periode 
            $dataNew = $data;
            $dataNew->nilai = $dataNew->nilai / $dataADHK[$i]->nilai;
            $periodeCurrent[] = $dataNew;

            // menghitung indeks implisit periode sebelumnya 
            $dataNewPeriodeBefore = $dataBeforeADHB[$i];
            $dataNewPeriodeBefore->nilai = $dataNewPeriodeBefore->nilai / $dataBeforeADHK[$i]->nilai;
            $periodeBefore[] = $dataNewPeriodeBefore;

            $i++;
        }

        // menghitung nilai pertumbuhan indeks implisit
        $dataOutput = [];
        $i = 0;
        $temp = [];
        foreach ($periodeCurrent as $data) {
            $temp = $data;
            $temp->nilai = (($temp->nilai  - $periodeBefore[$i]->nilai) * 100) / abs($periodeBefore[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

        $dataOutput = $this->sortData($dataOutput, 3);
        $dataOutput = $this->sortData($dataOutput, 1);
        $dataOutput = $this->sortData($dataOutput, 2);

        return $dataOutput;
    }

    // 10. Pertumbuhan indeks implisit (Y-ON-Y)
    private function ringkasan_tabel10($adhb, $adhk, $kota, $periode)
    {
        // sort data by periode ascending
        $dataADHB = $this->sortData($adhb, 3, true);
        $dataADHK = $this->sortData($adhk, 3, true);

        // membuat array untuk periode sebelumnya 
        $periodeBeforeADHB = [];
        $periodeBeforeADHK = [];
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

            array_push($periodeBeforeADHB, $QBefore);
            array_push($periodeBeforeADHK, $QBefore);
        }

        // get data by periodeBefore 
        $dataBeforeADHB = $this->getAllData($periodeBeforeADHB, '1', $kota);
        $dataBeforeADHK = $this->getAllData($periodeBeforeADHK, '2', $kota);
        $dataBeforeADHB = $this->sortData($dataBeforeADHB, 3, true);
        $dataBeforeADHK = $this->sortData($dataBeforeADHK, 3, true);

        // Menghitung indeks implisit tiap periode
        $periodeCurrent = [];
        $periodeBefore = [];
        $i = 0;
        $dataNew = [];
        $dataNewPeriodeBefore = [];
        foreach ($dataADHB as $data) {
            // menghitung indeks implisit current periode 
            $dataNew = $data;
            $dataNew->nilai = $dataNew->nilai / $dataADHK[$i]->nilai;
            $periodeCurrent[] = $dataNew;

            // menghitung indeks implisit periode sebelumnya 
            $dataNewPeriodeBefore = $dataBeforeADHB[$i];
            $dataNewPeriodeBefore->nilai = $dataNewPeriodeBefore->nilai / $dataBeforeADHK[$i]->nilai;
            $periodeBefore[] = $dataNewPeriodeBefore;

            $i++;
        }

        // menghitung nilai pertumbuhan indeks implisit
        $dataOutput = [];
        $i = 0;
        $temp = [];
        foreach ($periodeCurrent as $data) {
            $temp = $data;
            $temp->nilai = (($temp->nilai  - $periodeBefore[$i]->nilai) * 100) / abs($periodeBefore[$i]->nilai);
            $dataOutput[] = $temp;
            $i++;
        }

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

        // return $dataCurrent;
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

            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai) * 100) / abs($nilaiKomp9[$j]);
            $dataOutput[] = $dataNew;
            $i++;
        }

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

            $dataNew = $data;
            $dataNew->nilai = (($dataNew->nilai  - $dataBefore[$i]->nilai)  * 100) / abs($nilaiKomp9[$j]);
            $dataOutput[] = $dataNew;
            $i++;
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
        $wilayah = [];
        // $kota_tabel14 = $this->wilayah->whereNotIn('id_wilayah', [3100])->findAll();

        if ($jenisTabel == "14") {
            $wilayah = $this->wilayah->whereNotIn('id_wilayah', [3100])->findAll();
        } else {
            $wilayah = $this->wilayah->getAll();
        }


        switch ($jenisTabel) {
            case "11":
                // $dataRingkasan = $this->ringkasan_tabel1($periode, $komponen);
                break;
            case "13":
                $dataRingkasan = $this->ringkasan_tabel3($this->getAllData($periode, '1', $kota), $komponen, $kota, $periode);
                break;
            case "14":
                $dataRingkasan = $this->ringkasan_tabel4($this->getAllData($periode, '1', $kota), $komponen, $kota, $periode);
                break;
            case "15":
                $dataRingkasan = $this->ringkasan_tabel5($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "16":
                $dataRingkasan = $this->ringkasan_tabel6($this->getAllData($periode, '2', $kota), $kota, $periode);
                break;
            case "18":
                $dataRingkasan = $this->ringkasan_tabel8($this->getAllData($periode, '1', $kota), $this->getAllData($periode, '2', $kota), $kota, $periode);
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
        };
        $data = [
            'komponen' => $this->komponen->get_data(),
            'dataRingkasan' => $dataRingkasan,
            'selectedPeriode' => $periode,
            'wilayah' => $wilayah,
        ];

        echo json_encode($data);
    }
}
