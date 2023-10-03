<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\KuartalModel;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\StatusModel;
use App\Models\WilayahModel;

class UploadExcelController extends BaseController
{
    protected $kuartalModel;
    protected $putaranModel;
    protected $revisiModel;
    protected $statusModel;
    protected $wilayahModel;
    protected $jumlahKomponen;

    public function __construct()
    {
        $komponen7Model = new Komponen7Model();
        $this->kuartalModel = new KuartalModel();
        $this->putaranModel = new PutaranModel();
        $this->revisiModel = new RevisiModel();
        $this->statusModel = new StatusModel();
        $this->wilayahModel = new WilayahModel();

        // Atur waktu server: jakarta
        date_default_timezone_set('Asia/Jakarta');

        // Mendapatkan jumlah komponen PDRB & sub-nya
        $this->jumlahKomponen = $komponen7Model->countAllResults();
    }

    // Fungsi untuk meng-upload data
    public function upload()
    {
        // Cek apakah status putaran sudah dibuka
        $statusModel = new StatusModel();
        if ($statusModel->where('id_status', 1)->first()['is_active'] == 0) {
            return redirect()->back()->with('msg', 'Status putaran belum dibuka. Hubungi administrator untuk membuka status putaran.');
        }

        // Aturan validasi
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

        // Cek validasi
        if ($this->validate($rules)) {
            $filePath = ROOTPATH . 'public\uploads';
            $file = $this->request->getFile('inputFile');
            $file->move($filePath);
            $errorMsg = $this->process_file($file, $filePath);
            unlink($filePath . '\\' . $file->getName()); // hapus file setelah diproses agar tidak memenuhi storage
        } else {
            $errorMsg = $this->validator->getErrors();
        }

        return redirect()->back()->with('errorMsg', $errorMsg);
    }

    // Fungsi untuk mengolah file yang telah diupload
    public function process_file($theFile, $theFilePath)
    {
        // Konfigurasi untuk membaca file excel
        require_once ROOTPATH . 'vendor/autoload.php';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($theFilePath . '\\' . $theFile->getName());

        // Memilih worksheet dan mengambil data
        $worksheetADHB = $spreadsheet->getSheetByName('ADHB');
        $worksheetADHK = $spreadsheet->getSheetByName('ADHK');
        $dataADHB = $worksheetADHB->toArray();
        $dataADHK = $worksheetADHK->toArray();

        // Menyiapkan data ADHB dan ADHK untuk di-insert
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

        $messages = []; // array untuk menampung pesan error

        // Cek konsistensi data ADHB dan ADHK serta buat pesan error
        $nilaiADHB = $this->check_validate($periodeADHB, $komponenDanNilaiADHB, 'ADHB')['nilai'];
        $messages = array_merge($messages, $this->check_validate($periodeADHB, $komponenDanNilaiADHB, 'ADHB')['messages']);
        $nilaiADHK = $this->check_validate($periodeADHK, $komponenDanNilaiADHK, 'ADHK')['nilai'];
        $messages = array_merge($messages, $this->check_validate($periodeADHK, $komponenDanNilaiADHK, 'ADHK')['messages']);

        // Insert/update data jika tidak ada pesan error
        if (count($messages) == 0) {
            $insertBatchPutaran = [];
            $insertBatchRevisi = [];
            $updateBatchPutaran = [];
            $updateBatchRevisi = [];

            // ADHB
            $dataSemuaPeriodeADHB = array_chunk($nilaiADHB, $this->jumlahKomponen, true); // mengelompokkan data berdasarkan periode
            foreach ($dataSemuaPeriodeADHB as $data1PeriodeADHB) {
                // Mengambil nilai-nilai (field) yang akan di-insert ke dalam tabel putaran/revisi
                $firstKey = array_keys($data1PeriodeADHB)[0];
                [$periode, $kuartal, $tahun] = $this->extractNilaiKey($firstKey);
                $id_kuartal = $this->kuartalModel->where('kuartal', $kuartal)->first()['id_kuartal'];
                $cek_id_kuartal = ($id_kuartal == 5) ? 4 : $id_kuartal;
                $id_wilayah = $this->wilayahModel->where('wilayah', $wilayah)->first()['id_wilayah'];

                // Cek apakah data masuk ke dalam tabel putaran atau tabel revisi
                if (($tahun == $this->statusModel->where('id_status', 1)->first()['tahun']) &&
                    ($cek_id_kuartal == $this->statusModel->where('id_status', 1)->first()['id_kuartal'])
                ) {
                    $putaran = $this->statusModel->where('id_status', 1)->first()['putaran'];
                    // Cek apakah ada data dengan (periode, wilayah, jenis pdrb, dan putaran) tertentu, maka update, else insert
                    if ($this->putaranModel->where('periode', $periode)->where('id_wilayah', $id_wilayah)->where('id_pdrb', 1)->where('putaran', $putaran)->countAllResults() > 0) {
                        $this->batchData($data1PeriodeADHB, $periode, $id_kuartal, $id_wilayah, 1, $tahun, $putaran, $updateBatchPutaran);
                    } else {
                        $this->batchData($data1PeriodeADHB, $periode, $id_kuartal, $id_wilayah, 1, $tahun, $putaran, $insertBatchPutaran);
                    }
                } else {
                    $putaran = -1;
                    // Cek apakah ada data dengan (periode, wilayah, dan jenis pdrb) tertentu, maka update, else insert
                    if ($this->revisiModel->where('periode', $periode)->where('id_wilayah', $id_wilayah)->where('id_pdrb', 1)->countAllResults() > 0) {
                        $this->batchData($data1PeriodeADHB, $periode, $id_kuartal, $id_wilayah, 1, $tahun, $putaran, $updateBatchRevisi);
                    } else {
                        $this->batchData($data1PeriodeADHB, $periode, $id_kuartal, $id_wilayah, 1, $tahun, $putaran, $insertBatchRevisi);
                    }
                }
            }

            // ADHK
            $dataSemuaPeriodeADHK = array_chunk($nilaiADHK, $this->jumlahKomponen, true); // mengelompokkan data berdasarkan periode
            foreach ($dataSemuaPeriodeADHK as $data1PeriodeADHK) {
                // Mengambil nilai-nilai (field) yang akan di-insert ke dalam tabel putaran/revisi
                $firstKey = array_keys($data1PeriodeADHK)[0];
                [$periode, $kuartal, $tahun] = $this->extractNilaiKey($firstKey);
                $id_kuartal = $this->kuartalModel->where('kuartal', $kuartal)->first()['id_kuartal'];
                $cek_id_kuartal = ($id_kuartal == 5) ? 4 : $id_kuartal;
                $id_wilayah = $this->wilayahModel->where('wilayah', $wilayah)->first()['id_wilayah'];

                // Cek apakah data masuk ke dalam tabel putaran atau tabel revisi
                if (($tahun == $this->statusModel->where('id_status', 1)->first()['tahun']) &&
                    ($cek_id_kuartal == $this->statusModel->where('id_status', 1)->first()['id_kuartal'])
                ) {
                    $putaran = $this->statusModel->where('id_status', 1)->first()['putaran'];
                    // Cek apakah data dengan (periode, wilayah, jenis pdrb, dan putaran) tertentu, maka update, else insert
                    if ($this->putaranModel->where('periode', $periode)->where('id_wilayah', $id_wilayah)->where('id_pdrb', 2)->where('putaran', $putaran)->countAllResults() > 0) {
                        $this->batchData($data1PeriodeADHK, $periode, $id_kuartal, $id_wilayah, 2, $tahun, $putaran, $updateBatchPutaran);
                    } else {
                        $this->batchData($data1PeriodeADHK, $periode, $id_kuartal, $id_wilayah, 2, $tahun, $putaran, $insertBatchPutaran);
                    }
                } else {
                    $putaran = -1;
                    // Cek apakah data dengan (periode, wilayah, dan jenis pdrb) tertentu, maka update, else insert
                    if ($this->revisiModel->where('periode', $periode)->where('id_wilayah', $id_wilayah)->where('id_pdrb', 2)->countAllResults() > 0) {
                        $this->batchData($data1PeriodeADHK, $periode, $id_kuartal, $id_wilayah, 2, $tahun, $putaran, $updateBatchRevisi);
                    } else {
                        $this->batchData($data1PeriodeADHK, $periode, $id_kuartal, $id_wilayah, 2, $tahun, $putaran, $insertBatchRevisi);
                    }
                }
            }
            !empty($insertBatchPutaran) ? $this->putaranModel->insertBatch($insertBatchPutaran) : null;
            !empty($insertBatchRevisi) ? $this->revisiModel->insertBatch($insertBatchRevisi) : null;
            !empty($updateBatchPutaran) ? $this->putaranModel->batchUpdate($updateBatchPutaran) : null;
            !empty($updateBatchRevisi) ? $this->revisiModel->batchUpdate($updateBatchRevisi) : null;
        }
        return $messages;
    }

    // Fungsi untuk merapikan struktur data (array), cek konsistensi data, dan mengembalikan pesan error (jika ada)
    public function check_validate($periodeArray, $komponenDanNilai, $jenis)
    {
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
        ksort($nilai); // sort array berdasarkan key

        // Cek konsistensi data dan buat pesan error
        $messages = [];
        reset($periodeArray);
        $dataSemuaPeriode = array_chunk($nilai, $this->jumlahKomponen); // mengelompokkan data berdasarkan periode
        foreach ($dataSemuaPeriode as $data1Periode) {
            // Komponen (1) PKRT, Komponen (4) PMTB, dan PDRB
            $subKomponen1 = $data1Periode[1] + $data1Periode[2] + $data1Periode[3] + $data1Periode[4] + $data1Periode[5] + $data1Periode[6] + $data1Periode[7];
            $subKomponen4 = $data1Periode[11] + $data1Periode[12];
            $komponenPDRB = $data1Periode[0] + $data1Periode[8] + $data1Periode[9] + $data1Periode[10] + $data1Periode[13] + $data1Periode[14] - $data1Periode[15] + $data1Periode[16];

            if (round($data1Periode[0], 2) !== round($subKomponen1, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "Komponen (1) tidak konsisten.";
            }
            if (round($data1Periode[10], 2) !== round($subKomponen4, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "Komponen (4) tidak konsisten.";
            }
            if (round($data1Periode[17], 2) !== round($komponenPDRB, 2)) {
                $messages[] = "{" . $jenis . "." . current($periodeArray) . "} " . "PDRB tidak konsisten.";
            }
            next($periodeArray);
        }
        return ['nilai' => $nilai, 'messages' => $messages];
    }

    // Fungsi untuk mengambil informasi dari key array nilai
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

        unset($jenis, $komponen);
        return [$periode, $kuartal, $tahun];
    }

    // Fungsi untuk memasukkan data ke dalam array batch
    public function batchData($data1Periode, $periode, $id_kuartal, $id_wilayah, $id_pdrb, $tahun, $putaran, &$dataBatch)
    {
        if ($putaran != -1) { 
            foreach ($data1Periode as $index => $value) {
                $dataBatch[] = [
                    'periode' => $periode,
                    'id_kuartal' => $id_kuartal,
                    'id_komponen' => substr($index, strrpos($index, '.') + 1),
                    'id_wilayah' => $id_wilayah,
                    'id_pdrb' => $id_pdrb,
                    'tahun' => $tahun,
                    'putaran' => $putaran,
                    'nilai' => $value,
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'uploaded_by' => session('niplama')
                ];
            }
        } else { // kalau revisi putarannya = -1, maka tidak perlu memasukkan putaran
            foreach ($data1Periode as $index => $value) {
                $dataBatch[] = [
                    'periode' => $periode,
                    'id_kuartal' => $id_kuartal,
                    'id_komponen' => substr($index, strrpos($index, '.') + 1),
                    'id_wilayah' => $id_wilayah,
                    'id_pdrb' => $id_pdrb,
                    'tahun' => $tahun,
                    'nilai' => $value,
                    'uploaded_at' => date('Y-m-d H:i:s'),
                    'uploaded_by' => session('niplama')
                ];
            }
        }
    }
}
