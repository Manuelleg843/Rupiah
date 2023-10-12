<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\DiskrepansiModel;
use App\Models\StatusModel;
use CodeIgniter\Commands\Utilities\Publish;
use PhpParser\Node\Stmt\Return_;

use function App\Helpers\is_logged_in;

class Beranda extends BaseController
{
    protected $komponen;
    protected $putaran;
    protected $revisi;
    protected $request;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->revisi = new RevisiModel();
    }

    public function BarChart()
    {
        $satker = session()->get('id_satker');
        $kom = [1, 2, 3, 4, 6, 7];
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK
        // Bar
        // Y ON Y
        $arrayBarY_ON_Y = array();
        foreach ($kom as $item) {
            $Y_ON_Y = $this->putaran->getDataKomponen($satker, $JenisPDRB, $item);
            $tahunmin = ($Y_ON_Y[0]->tahun) - 1;
            $kuartal = $Y_ON_Y[0]->id_kuartal;
            $periode = $tahunmin . 'Q' . $kuartal;
            $Y_ON_Y_minus1 = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, $item, $periode);
            // Perhitungan Y ON Y -> (2023 {Q} - 2022 {Q})/MUTLAK(2022 {Q})*100
            $hasil_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;
            array_push($arrayBarY_ON_Y, $hasil_Y_ON_Y);
        };

        // Q TO Q
        $arrayBarQ_TO_Q = array();
        foreach ($kom as $item) {
            $Q_TO_Q = $this->putaran->getDataKomponen($satker, $JenisPDRB, $item);
            $tahun = $Q_TO_Q[0]->tahun;
            $kuartalmin = ($Q_TO_Q[0]->id_kuartal) - 1;
            $periode = $tahun . 'Q' . $kuartalmin;
            $Q_TO_Q_minus1 = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, $item, $periode);
            // Perhitungan Y ON Y -> (2023 {Q} - 2023 {Q-1})/MUTLAK(2023 {Q-1})*100
            $hasil_Q_TO_Q = ($Q_TO_Q[0]->nilai - $Q_TO_Q_minus1[0]->nilai) / abs($Q_TO_Q_minus1[0]->nilai) * 100;
            array_push($arrayBarQ_TO_Q, $hasil_Q_TO_Q);
        };

        // C TO C
        $arrayBarC_TO_C = array();
        foreach ($kom as $item) {
            $C_TO_C = $this->putaran->getDataKomponen($satker, $JenisPDRB, $item);
            $tahun = $C_TO_C[0]->tahun;
            $tahunmin = $tahun - 1;
            $kuartal = $C_TO_C[0]->id_kuartal;
            $kumulatif = 0;
            $kumulatifmin = 0;
            // Perhitungan C TO C -> (sum2023 {Q} - sum2022 {Q})/MUTLAK(sum2022 {Q})*100 -> sum = total Q-awal {Q1} hingga Q-akhir {Q}
            for ($i = 1; $i <= $kuartal; $i++) {
                $periode = $tahun . 'Q' . $i;
                $C_TO_C_Year = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, $item, $periode);
                $kumulatif = $kumulatif + $C_TO_C_Year[0]->nilai;
                $periodemin = $tahunmin . 'Q' . $i;
                $C_TO_C_Yearmin = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, $item, $periodemin);
                $kumulatifmin = $kumulatifmin + $C_TO_C_Yearmin[0]->nilai;
            }
            $hasil_C_TO_C = ($kumulatif - $kumulatifmin) / abs($kumulatifmin) * 100;
            array_push($arrayBarC_TO_C, $hasil_C_TO_C);
        };

        // Data untuk JS
        $dataBuatJs = [
            'arrayBarY_ON_Y' => $arrayBarY_ON_Y,
            'arrayBarQ_TO_Q' => $arrayBarQ_TO_Q,
            'arrayBarC_TO_C' => $arrayBarC_TO_C,
        ];

        echo json_encode($dataBuatJs);
    }

    public function getData()
    {
        $kota = session()->get('id_satker');
        // Triwulan (Y ON Y, Q TO Q, C TO C) atau Tahunan (Y ON Y)
        $jenisTable = $this->request->getPost('jenisTable');
        $periode = $this->request->getPost('periode');
        sort($periode); // Sortir periode dari kecil ke besar (untuk line charts
        $jenisKomponen = $this->request->getPost('jenisKomponen');

        switch ($jenisTable) {
            case "11": // Tahunan (Y ON Y)
                $data = $this->LineChartTahunan($kota, $jenisKomponen, $periode);
                break;
            case "21": // Triwulan (Y ON Y)
                $data = $this->LineChartTriwulan_YONY($kota, $jenisKomponen, $periode);
                break;
            case "22": // Triwulan (Q TO Q)
                $data = $this->LineChartTriwulan_QTOQ($kota, $jenisKomponen, $periode);
                break;
            case "23": // Triwulan (C TO C)
                $data = $this->LineChartTriwulan_CTOC($kota, $jenisKomponen, $periode);
                break;
        }
        echo json_encode($data);
    }

    // Line Chart Tahunan
    public function LineChartTahunan($kota, $jenisKomponen, $periode)
    {
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK

        // Tahunan (Y ON Y)
        $arrayLineTahunanY_ON_Y = array();
        foreach ($periode as $tahun) {
            $hasil_Line_Y_ON_Y = null;
            // Cek Putaran Atas bukan null
            if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahun)) {
                $Y_ON_Y = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahun);
                $tahunmin = ($Y_ON_Y[0]->tahun) - 1;
                // Cek Putaran Bawah bukan null
                if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahunmin)) {
                    $Y_ON_Y_minus1 = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahunmin);
                    // Cek Revisi Atas bukan null
                    if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahun)) {
                        $Y_ON_Y = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahun);
                        // Cek Revisi Bawah bukan null
                        if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahunmin)) {
                            $Y_ON_Y_minus1 = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $tahunmin);
                        }
                    }
                    // Perhitungan Y ON Y -> (2023 - 2022)/MUTLAK(2022)*100
                    $hasil_Line_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;
                    $hasil_Line_Y_ON_Y = number_format($hasil_Line_Y_ON_Y, 2, '.', ',');
                }
            }
            array_push($arrayLineTahunanY_ON_Y, $hasil_Line_Y_ON_Y);
        };

        // Data untuk JS
        $dataBuatJs = [
            'periode' => $periode,
            'nilai' => $arrayLineTahunanY_ON_Y,
        ];
        return $dataBuatJs;
    }

    // Line Chart Triwulan Y ON Y
    public function LineChartTriwulan_YONY($kota, $jenisKomponen, $periode)
    {
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK

        // Triwulan (Y ON Y)
        $arrayLineTriwulanY_ON_Y = array();
        foreach ($periode as $item) {
            $hasil_Line_Y_ON_Y = null;
            // Cek Putaran Atas bukan null
            if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item)) {
                $Y_ON_Y = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item);
                $tahunmin = ($Y_ON_Y[0]->tahun) - 1;
                $periodemin = $tahunmin . 'Q' . $Y_ON_Y[0]->id_kuartal;
                // Cek Putaran Bawah bukan null
                if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                    $Y_ON_Y_minus1 = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                    // Cek Revisi Atas bukan null
                    if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item)) {
                        $Y_ON_Y = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item);
                        // Cek Revisi Bawah bukan null
                        if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                            $Y_ON_Y_minus1 = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                        }
                    }
                    // Perhitungan Y ON Y -> (2023 {Q} - 2022 {Q})/MUTLAK(2022 {Q})*100
                    $hasil_Line_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;
                    $hasil_Line_Y_ON_Y = number_format($hasil_Line_Y_ON_Y, 2, '.', ',');
                }
            }
            array_push($arrayLineTriwulanY_ON_Y, $hasil_Line_Y_ON_Y);
        };

        // Data untuk JS
        $dataBuatJs = [
            'periode' => $periode,
            'nilai' => $arrayLineTriwulanY_ON_Y,
        ];
        return $dataBuatJs;
    }

    // Line Chart Triwulan Q TO Q
    public function LineChartTriwulan_QTOQ($kota, $jenisKomponen, $periode)
    {
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK

        // Triwulan (Q TO Q)
        $arrayLineTriwulanQ_TO_Q = array();
        foreach ($periode as $item) {
            $hasil_Line_Q_TO_Q = null;
            // Cek Putaran Atas bukan null
            if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item)) {
                $Q_TO_Q = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item);
                $kuartal = $Q_TO_Q[0]->id_kuartal;
                if ($kuartal = 5) {
                    $tahunmin = ($Q_TO_Q[0]->tahun) - 1;
                    $periodemin = $tahunmin . 'Q4';
                } else {
                    $kuartalmin = $kuartal - 1;
                    $periodemin = $Q_TO_Q[0]->tahun . 'Q' . $kuartalmin;
                }
                // Cek Putaran Bawah bukan null
                if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                    $Q_TO_Q_minus1 = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                    // Cek Revisi Atas bukan null
                    if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item)) {
                        $Q_TO_Q = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item);
                        // Cek Revisi Bawah bukan null
                        if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                            $Q_TO_Q_minus1 = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                        }
                    }
                    // Perhitungan Y ON Y -> (2023 {Q} - 2023 {Q-1})/MUTLAK(2023 {Q-1})*100
                    $hasil_Line_Q_TO_Q = ($Q_TO_Q[0]->nilai - $Q_TO_Q_minus1[0]->nilai) / abs($Q_TO_Q_minus1[0]->nilai) * 100;
                    $hasil_Line_Q_TO_Q = number_format($hasil_Line_Q_TO_Q, 2, '.', ',');
                }
            }
            array_push($arrayLineTriwulanQ_TO_Q, $hasil_Line_Q_TO_Q);
        };

        // Data untuk JS
        $dataBuatJs = [
            'periode' => $periode,
            'nilai' => $arrayLineTriwulanQ_TO_Q,
        ];
        return $dataBuatJs;
    }

    // Line Chart Triwulan C TO C
    public function LineChartTriwulan_CTOC($kota, $jenisKomponen, $periode)
    {
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK

        // Triwulan (C TO C)
        $arrayLineTriwulanC_TO_C = array();
        foreach ($periode as $item) {
            $hasil_Line_C_TO_C = null;
            // Cek Putaran Atas bukan null
            if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item)) {
                $C_TO_C = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $item);
                $tahun = $C_TO_C[0]->tahun;
                $tahunmin = $tahun - 1;
                $kuartal = $C_TO_C[0]->id_kuartal;
                $kumulatif = 0;
                $kumulatifmin = 0;
                $periodemin = $tahunmin . 'Q' . $kuartal;
                // Cek Putaran Bawah bukan null
                if ($this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                    // Perhitungan C TO C -> (sum2023 {Q} - sum2022 {Q})/MUTLAK(sum2022 {Q})*100 -> sum = total Q-awal {Q1} hingga Q-akhir {Q-x}
                    for ($i = 1; $i <= $kuartal; $i++) {
                        $periodesekarang = $tahun . 'Q' . $i;
                        $periodemin = $tahunmin . 'Q' . $i;
                        $C_TO_C = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodesekarang);
                        $C_TO_C_min = $this->putaran->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                        // Cek Revisi Atas bukan null
                        if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodesekarang)) {
                            $C_TO_C = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodesekarang);
                            // Cek Revisi Bawah bukan null
                            if ($this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin)) {
                                $C_TO_C_min = $this->revisi->getDataKomponenPeriode($kota, $JenisPDRB, $jenisKomponen, $periodemin);
                            }
                        }
                        $kumulatif = $kumulatif + $C_TO_C[0]->nilai;
                        $kumulatifmin = $kumulatifmin + $C_TO_C_min[0]->nilai;
                    }
                    $hasil_Line_C_TO_C = ($kumulatif - $kumulatifmin) / abs($kumulatifmin) * 100;
                    $hasil_Line_C_TO_C = number_format($hasil_Line_C_TO_C, 2, '.', ',');
                }
            }
            array_push($arrayLineTriwulanC_TO_C, $hasil_Line_C_TO_C);
        }

        // Data untuk JS
        $dataBuatJs = [
            'periode' => $periode,
            'nilai' => $arrayLineTriwulanC_TO_C,
        ];
        return $dataBuatJs;
    }

    public function index()
    {
        if (!session()->get('email')) {
            return redirect()->to('/login');
        }
        $satker = session()->get('id_satker');

        //Untuk Diskrepansi ADHB
        $KS_adhb = $this->putaran->getDataKomponen(3101, 1, 9); //Kepulauan Seribu
        $JS_adhb = $this->putaran->getDataKomponen(3171, 1, 9); //Jakarta Selatan
        $JT_adhb = $this->putaran->getDataKomponen(3172, 1, 9); //Jakarta Timur
        $JP_adhb = $this->putaran->getDataKomponen(3173, 1, 9); //Jakarta Pusat
        $JB_adhb = $this->putaran->getDataKomponen(3174, 1, 9); //Jakarta Barat
        $JU_adhb = $this->putaran->getDataKomponen(3175, 1, 9); //Jakarta Utara
        $adhb = $this->putaran->getDataKomponen(3100, 1, 9); //DKI
        //Untuk Diskrepansi ADHK
        $KS_adhk = $this->putaran->getDataKomponen(3101, 2, 9); //Kepulauan Seribu
        $JS_adhk = $this->putaran->getDataKomponen(3171, 2, 9); //Jakarta Selatan
        $JT_adhk = $this->putaran->getDataKomponen(3172, 2, 9); //Jakarta Timur
        $JP_adhk = $this->putaran->getDataKomponen(3173, 2, 9); //Jakarta Pusat
        $JB_adhk = $this->putaran->getDataKomponen(3174, 2, 9); //Jakarta Barat
        $JU_adhk = $this->putaran->getDataKomponen(3175, 2, 9); //Jakarta Utara
        $adhk = $this->putaran->getDataKomponen(3100, 2, 9); //DKI
        // Total adhb dan adhk kabkot
        $kabkot_adhb = $KS_adhb[0]->nilai + $JS_adhb[0]->nilai + $JT_adhb[0]->nilai + $JP_adhb[0]->nilai + $JB_adhb[0]->nilai + $JU_adhb[0]->nilai;
        $kabkot_adhk = $KS_adhk[0]->nilai + $JS_adhk[0]->nilai + $JT_adhk[0]->nilai + $JP_adhk[0]->nilai + $JB_adhk[0]->nilai + $JU_adhk[0]->nilai;
        // Perhitungan Diskrepansi -> DKI/KABKOT * 100
        $Diskrepansi_adhb = (($kabkot_adhb - ($adhb[0]->nilai)) / $adhb[0]->nilai) * 100;
        $Diskrepansi_adhk = (($kabkot_adhk - ($adhk[0]->nilai)) / $adhk[0]->nilai) * 100;

        // Pertumbuhan Y ON Y
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK
        $Y_ON_Y = $this->putaran->getDataKomponen($satker, $JenisPDRB, 9);
        $tahunmin = ($Y_ON_Y[0]->tahun) - 1;
        $kuartal = $Y_ON_Y[0]->id_kuartal;
        $periodemin = $tahunmin . 'Q' . $kuartal;
        $Y_ON_Y_minus1 = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, 9, $periodemin);
        // Perhitungan Y ON Y -> (2023 {Q} - 2022 {Q})/MUTLAK(2022 {Q})*100
        $hasil_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;

        // Pertumbuhan Q TO Q
        $Q_TO_Q = $this->putaran->getDataKomponen($satker, $JenisPDRB, 9);
        $tahun = $Q_TO_Q[0]->tahun;
        $kuartalmin = ($Q_TO_Q[0]->id_kuartal) - 1;
        $periodemin = $tahun . 'Q' . $kuartalmin;
        $Q_TO_Q_minus1 = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, 9, $periodemin);
        // Perhitungan Q To Q -> (2023 {Q} - 2023 {Q-1})/MUTLAK(2023 {Q-1})*100
        $hasil_Q_TO_Q = ($Q_TO_Q[0]->nilai - $Q_TO_Q_minus1[0]->nilai) / abs($Q_TO_Q_minus1[0]->nilai) * 100;

        // Pertumbuhan C TO C
        $C_TO_C = $this->putaran->getDataKomponen($satker, $JenisPDRB, 9);
        $tahun = $C_TO_C[0]->tahun;
        $tahunmin = $tahun - 1;
        $kuartal = $C_TO_C[0]->id_kuartal;
        $kumulatif = 0;
        $kumulatifmin = 0;
        for ($i = 1; $i <= $kuartal; $i++) {
            $periode = $tahun . 'Q' . $i;
            $C_TO_C_Year = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, 9, $periode);
            $kumulatif = $kumulatif + $C_TO_C_Year[0]->nilai;
            $periodemin = $tahunmin . 'Q' . $i;
            $C_TO_C_Yearmin = $this->putaran->getDataKomponenPeriode($satker, $JenisPDRB, 9, $periodemin);
            $kumulatifmin = $kumulatifmin + $C_TO_C_Yearmin[0]->nilai;
        }
        // Perhitungan C TO C -> (sum2023 {Q} - sum2022 {Q})/MUTLAK(sum2022 {Q})*100 -> sum = total Q-awal {Q1} hingga Q-akhir {Q}
        $hasil_C_TO_C = ($kumulatif - $kumulatifmin) / abs($kumulatifmin) * 100;

        // Untuk Status *data sementara
        $statusModel = new StatusModel();
        $data = [
            'title' => 'Beranda',
            'tajuk' => 'Beranda',
            'subTajuk' => '',
            'adhb' => $this->putaran->getDataKomponen($satker, 1, 9),
            'adhk' => $this->putaran->getDataKomponen($satker, 2, 9),
            'Diskrepansi_adhb' => $Diskrepansi_adhb,
            'Diskrepansi_adhk' => $Diskrepansi_adhk,
            'hasil_Y_ON_Y' => $hasil_Y_ON_Y,
            'hasil_Q_TO_Q' => $hasil_Q_TO_Q,
            'hasil_C_TO_C' => $hasil_C_TO_C,
            'isActive' => $statusModel->where('id_status', 1)->first()['is_active'],
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('beranda', $data);
        echo view('layouts/footer');
    }
}
