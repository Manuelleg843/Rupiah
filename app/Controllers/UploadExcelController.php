<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\KuartalModel;
use App\Models\PutaranModel;
use App\Models\WilayahModel;

class UploadExcelController extends BaseController
{
    protected $jumlahKomponen;
    protected $kuartalModel;
    protected $putaranModel;
    protected $wilayahModel;

    public function __construct()
    {
        $komponen7Model = new Komponen7Model();
        $this->kuartalModel = new KuartalModel();
        $this->putaranModel = new PutaranModel();
        $this->wilayahModel = new WilayahModel();

        $this->jumlahKomponen = $komponen7Model->countAllResults();
    }

    public function upload()
    {
        $data = [
            'niplama' => '340017406',
            'nama' => 'Mansur',
            'email' => 'mansur@dki.com',
            'id_satker' => '3100',
            'satker' => 'Provinsi DKI Jakarta',
            'id_role' => '3',
            'isLoggedIn' => '1',
            'permission' => [1, 2]
        ];

        session()->set($data);

        $rules = [
            'alasanUpload' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Keterangan upload harus diisi.'
                ]
            ],
            'inputFile' => [
                'rules' => 'uploaded[inputFile]|ext_in[inputFile,xls,xlsx]',
                'errors' => [
                    'uploaded' => 'Pilih file yang akan diupload.',
                    'ext_in' => 'File yang diupload harus berformat .xlsx (sesuai template).'
                ]
            ]
        ];

        if ($this->validate($rules)) {
            $filePath = ROOTPATH . 'public\uploads';

            $file = $this->request->getFile('inputFile');
            $file->move($filePath);

            $this->process_file($file, $filePath);
        } else {
            $msg = $this->validator->listErrors();
            return redirect()->to('/uploadData/angkaPDRB')->with('msg', $msg);
        }
    }

    // Fungsi untuk mengolah file yang telah diupload
    public function process_file($theFile, $theFilePath)
    {
        require_once ROOTPATH . 'vendor/autoload.php';

        // Create a new Spreadsheet object
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($theFilePath . '\\' . $theFile->getName());

        // Select worksheets
        $worksheetADHB = $spreadsheet->getSheetByName('ADHB');
        $worksheetADHK = $spreadsheet->getSheetByName('ADHK');

        // Get all data from the worksheets
        $dataADHB = $worksheetADHB->toArray();
        $dataADHK = $worksheetADHK->toArray();

        $messages = [];

        // Menyiapkan data ADHB untuk di-insert
        $komponenDanNilaiADHB = [];
        foreach ($dataADHB as $rowOrder => $row) {
            switch ($rowOrder) {
                case 0:
                case 1:
                case 3:
                    break;
                case 2:
                    $wilayah = $row[0];
                    break;
                case 4:
                    $periodeADHB = [];
                    foreach ($row as $value) {
                        $periodeADHB[] = $value;
                    }
                    array_shift($periodeADHB);
                    break;
                default:
                    array_push($komponenDanNilaiADHB, $row);
            }
        }

        $nilaiADHB = $this->check_validate($periodeADHB, $komponenDanNilaiADHB, 'ADHB')['nilai'];
        $messages = array_merge($messages, $this->check_validate($periodeADHB, $komponenDanNilaiADHB, 'ADHB')['messages']);

        // Menyiapkan data ADHK untuk di-insert
        $komponenDanNilaiADHK = [];
        foreach ($dataADHK as $rowOrder => $row) {
            switch ($rowOrder) {
                case 0:
                case 1:
                case 3:
                    break;
                case 2:
                    $wilayah = $row[0];
                    break;
                case 4:
                    $periodeADHK = [];
                    foreach ($row as $value) {
                        $periodeADHK[] = $value;
                    }
                    array_shift($periodeADHK);
                    break;
                default:
                    array_push($komponenDanNilaiADHK, $row);
            }
        }

        $nilaiADHK = $this->check_validate($periodeADHK, $komponenDanNilaiADHK, 'ADHK')['nilai'];
        $messages = array_merge($messages, $this->check_validate($periodeADHK, $komponenDanNilaiADHK, 'ADHK')['messages']);

        // Insert data jika tidak ada pesan error
        if (count($messages) !== 0) {
            $dataBatchPutaran = [];
            $dataBatchRevisi = [];

            $dataSemuaPeriodeADHB = array_chunk($nilaiADHB, $this->jumlahKomponen, true);
            foreach ($dataSemuaPeriodeADHB as $data1PeriodeADHB) {
                $firstKey = array_keys($data1PeriodeADHB)[0];
                [$periode, $kuartal, $id_komponen, $tahun] = $this->extractNilaiKey($firstKey);
                $id_kuartal = $this->kuartalModel->where('kuartal', $kuartal)->first()['id_kuartal'];
                $id_wilayah = $this->wilayahModel->where('wilayah', $wilayah)->first()['id_wilayah'];

                if ($tahun == date('Y')) {
                    if ($id_kuartal == (ceil(date('n') / 3) - 1)) {
                        // $dataBatchPutaran
                    } else if ($id_kuartal <= (ceil(date('n') / 3) - 2)) {
                        // $dataBatchRevisi
                    } else if (($id_kuartal == 4) && (ceil(date('n') / 3) == 1)) {
                        // $dataBatchPutaran untuk tahun kemaren pas q1
                    } else if (($id_kuartal == 5) && (ceil(date('n') / 3) == 1)) {
                        // $dataBatchRevisi untuk tahun kemaren pas q1
                    } else {
                        // error
                    }
                } else if ($tahun < date('Y')) {
                    // $dataBatchRevisi
                } else {
                    // error
                }

                echo "<pre>";
                print_r($data1PeriodeADHB);
                echo ($periode) . "<br>";
                echo ($id_kuartal) . "<br>";
                echo ($id_komponen) . "<br>";
                echo ($id_wilayah) . "<br>";
                echo ($tahun) . "<br>";
                echo "</pre>";
            }
            exit();

            // foreach ($nilaiADHB as $index => $value) {
            //     [$periode, $kuartal, $id_komponen, $tahun] = $this->extractNilaiKey($index);
            //     // cek masuk revisi apa putaran

            // $dataBatchPutaran[] = [
            //     'periode' => $periode,
            //     'id_kuartal' => $kuartalModel->where('kuartal', $kuartal)->first()['id_kuartal'],
            //     'id_komponen' => $id_komponen,
            //     'id_wilayah' => $wilayahModel->where('wilayah', $wilayah)->first()['id_wilayah'],
            //     'id_pdrb' => 1,
            //     'tahun' => $tahun,
            //     'putaran' => ,
            //     'nilai' => $value,
            //     'uploaded_at' => date('Y-m-d H:i:s'),
            //     'uploaded_by' => session('niplama')
            // ];


            //     $dataBatchRevisi[] = [
            //         'periode' => $periode,
            //         'id_kuartal' => $kuartalModel->where('kuartal', $kuartal)->first()['id_kuartal'],
            //         'id_komponen' => $id_komponen,
            //         'id_wilayah' => $wilayahModel->where('wilayah', $wilayah)->first()['id_wilayah'],
            //         'id_pdrb' => 1,
            //         'tahun' => $tahun,
            //         'putaran' => ,
            //         'nilai' => $value,
            //         'uploaded_at' => date('Y-m-d H:i:s'),
            //         'uploaded_by' => session('niplama')
            //     ]
            // }
        }

        /*
        periode -> tahun.id_kuartal->getValueYangBener()
        id_kuartal -> header
        id_komponen -> kolom
        id_wilayah -> judul
        id_pdrb -> judul
        tahun -> header
        putaran -> getCurrentPutaran()
        nilai -> yg mau dimasukin
        uploaded_at -> timestamp
        uploaded_by -> session('nip_lama')
        */
        echo "<pre>";
        print_r($nilaiADHB);
        echo "</pre>";
        exit();

        $messages = [];
        return $messages;
    }

    // Fungsi untuk cek konsistensi data
    public function check_validate($periodeArray, $komponenDanNilai, $jenis)
    {
        $messages = [];
        $nilai = [];
        $indexNilaiKomponen = "";
        foreach ($komponenDanNilai as $row) {
            reset($periodeArray); // reset pointer array periode (kembali ke awal)

            foreach ($row as $index => $value) {
                if ($index == 0) {
                    $indexNilaiKomponen = strtok($value, '.');
                } else {
                    $indexNilaiKomponenPeriode = $jenis . '.' . current($periodeArray) . '.' . $indexNilaiKomponen;
                    $nilai[$indexNilaiKomponenPeriode] = (float)$value;
                    next($periodeArray);
                }
            }
        }

        // sort array berdasarkan key
        ksort($nilai);

        // mengelompokkan tiap nilai berdasarkan periodenya
        reset($periodeArray);
        $dataSemuaPeriode = array_chunk($nilai, $this->jumlahKomponen);
        foreach ($dataSemuaPeriode as $data1Periode) {
            $subKomponen1 = $data1Periode[1] + $data1Periode[2] + $data1Periode[3] + $data1Periode[4] + $data1Periode[5] + $data1Periode[6] + $data1Periode[7];
            $subKomponen4 = $data1Periode[11] + $data1Periode[12];
            $komponenPDRB = $data1Periode[0] + $data1Periode[8] + $data1Periode[9] + $data1Periode[10] + $data1Periode[13] + $data1Periode[14] - $data1Periode[15] + $data1Periode[16];

            // Komponen PKRT
            if (round($data1Periode[0], 2) !== round($subKomponen1, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "Komponen (1) tidak konsisten.";
            }

            // Komponen PMTB
            if (round($data1Periode[10], 2) !== round($subKomponen4, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "Komponen (4) tidak konsisten.";
            }

            // Komponen PDRB
            if (round($data1Periode[17], 2) !== round($komponenPDRB, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "PDRB tidak konsisten.";
            }

            next($periodeArray);
        }

        return ['nilai' => $nilai, 'messages' => $messages];
    }

    public function extractNilaiKey($key)
    {
        [$jenis, $periode, $komponen] = explode('.', $key);
        if (substr($periode, 4, 2) !== "") {
            $tahun = substr($periode, 0, 4);
            $kuartal = substr($periode, 4, 2);
        } else {
            $tahun = substr($periode, 0, 4);
            $kuartal = "year";
        }

        return [$periode, $kuartal, $komponen, $tahun];
    }

    public function insertBatch($periode, $id_kuartal, $id_komponen, $id_wilayah, $id_pdrb, $tahun, $putaran, $nilai, $uploaded_at, $uploaded_by) {
        /*
        periode -> tahun.id_kuartal->getValueYangBener()
        id_kuartal -> header
        id_komponen -> kolom
        id_wilayah -> judul
        id_pdrb -> judul
        tahun -> header
        putaran -> getCurrentPutaran()
        nilai -> yg mau dimasukin
        uploaded_at -> timestamp
        uploaded_by -> session('nip_lama')
        */
    }
}
