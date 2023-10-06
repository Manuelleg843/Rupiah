<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\DiskrepansiModel;
use App\Models\StatusModel;

use function App\Helpers\is_logged_in;

class Beranda extends BaseController
{
    protected $komponen;
    protected $putaran;
    protected $request;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
    }

    public function Chart()
    {
        $kom = [1, 2, 3, 4, 6, 7];
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK
        // Bar
        // Y ON Y
        $arrayBarY_ON_Y = array();
        foreach ($kom as $item) {
            $Y_ON_Y = $this->putaran->getDataKomponen(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            $Y_ON_Y_minus1 = $this->putaran->getDataKomponenMinusYear(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            // Perhitungan Y ON Y -> (2023 {Q} - 2022 {Q})/MUTLAK(2022 {Q})*100
            $hasil_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;
            array_push($arrayBarY_ON_Y, $hasil_Y_ON_Y);
        };

        // Q TO Q
        $arrayBarQ_TO_Q = array();
        foreach ($kom as $item) {
            $Q_TO_Q = $this->putaran->getDataKomponen(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            $Q_TO_Q_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            // Perhitungan Y ON Y -> (2023 {Q} - 2023 {Q-1})/MUTLAK(2023 {Q-1})*100
            $hasil_Q_TO_Q = ($Q_TO_Q[0]->nilai - $Q_TO_Q_minus1[0]->nilai) / abs($Q_TO_Q_minus1[0]->nilai) * 100;
            array_push($arrayBarQ_TO_Q, $hasil_Q_TO_Q);
        };

        // C TO C
        $arrayBarC_TO_C = array();
        foreach ($kom as $item) {
            $C_TO_C = $this->putaran->getDataKomponen(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            $C_TO_C_Year = $this->putaran->getDataKomponenMinusYear(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
            // Perhitungan C TO C -> (sum2023 {Q} - sum2022 {Q})/MUTLAK(sum2022 {Q})*100 -> sum = total Q-awal {Q1} hingga Q-akhir {Q}
            if ($C_TO_C[0]->nilai = 1) {
                $BarC_TO_C = ($C_TO_C[0]->nilai) - ($C_TO_C_Year[0]->nilai) / abs($C_TO_C_Year[0]->nilai) * 100;
            } elseif ($C_TO_C[0]->nilai = 2) {
                // Atas
                $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                // Bawah
                $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                //Perhitungan
                $BarC_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai) * 100;
            } elseif ($C_TO_C[0]->nilai = 3) {
                // Atas
                $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_minus2 = $this->putaran->getDataKomponenMinus2Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                // Bawah
                $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_Year_minus2 = $this->putaran->getDataKomponenMinusYear2Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                // Perhitungan
                $BarC_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai + $C_TO_C_minus2[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai) * 100;
            } elseif ($C_TO_C[0]->nilai = 4) {
                // Atas
                $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_minus2 = $this->putaran->getDataKomponenMinus2Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_minus3 = $this->putaran->getDataKomponenMinus3Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                // Bawah
                $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_Year_minus2 = $this->putaran->getDataKomponenMinusYear2Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                $C_TO_C_Year_minus3 = $this->putaran->getDataKomponenMinusYear3Kuartal(3100, $JenisPDRB, $item); // cara ganti kota otomatis sesuai satker
                // Perhitungan
                $BarC_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai + $C_TO_C_minus2[0]->nilai + $C_TO_C_minus3[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai + $C_TO_C_Year_minus3[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai + $C_TO_C_Year_minus3[0]->nilai) * 100;
            }
            array_push($arrayBarC_TO_C, $BarC_TO_C);
        };

        // Line

        // Data untuk JS
        $dataBuatJs = [
            'arrayBarY_ON_Y' => $arrayBarY_ON_Y,
            'arrayBarQ_TO_Q' => $arrayBarQ_TO_Q,
            'arrayBarC_TO_C' => $arrayBarC_TO_C,
        ];

        echo json_encode($dataBuatJs);
    }

    public function index()
    {

        if (!session()->get('email')) {
            return redirect()->to('/login');
        }

        //Untuk Diskrepansi ADHB
        $KS_adhb = $this->putaran->getDataKomponenDiskrepansi(3101, 1, 9); //Kepulauan Seribu
        $JS_adhb = $this->putaran->getDataKomponenDiskrepansi(3171, 1, 9); //Jakarta Selatan
        $JT_adhb = $this->putaran->getDataKomponenDiskrepansi(3172, 1, 9); //Jakarta Timur
        $JP_adhb = $this->putaran->getDataKomponenDiskrepansi(3173, 1, 9); //Jakarta Pusat
        $JB_adhb = $this->putaran->getDataKomponenDiskrepansi(3174, 1, 9); //Jakarta Barat
        $JU_adhb = $this->putaran->getDataKomponenDiskrepansi(3175, 1, 9); //Jakarta Utara
        $adhb = $this->putaran->getDataKomponenDiskrepansi(3100, 1, 9); //DKI
        //Untuk Diskrepansi ADHK
        $KS_adhk = $this->putaran->getDataKomponenDiskrepansi(3101, 2, 9); //Kepulauan Seribu
        $JS_adhk = $this->putaran->getDataKomponenDiskrepansi(3171, 2, 9); //Jakarta Selatan
        $JT_adhk = $this->putaran->getDataKomponenDiskrepansi(3172, 2, 9); //Jakarta Timur
        $JP_adhk = $this->putaran->getDataKomponenDiskrepansi(3173, 2, 9); //Jakarta Pusat
        $JB_adhk = $this->putaran->getDataKomponenDiskrepansi(3174, 2, 9); //Jakarta Barat
        $JU_adhk = $this->putaran->getDataKomponenDiskrepansi(3175, 2, 9); //Jakarta Utara
        $adhk = $this->putaran->getDataKomponenDiskrepansi(3100, 2, 9); //DKI
        // Total adhb dan adhk kabkot
        $kabkot_adhb = $KS_adhb[0]->nilai + $JS_adhb[0]->nilai + $JT_adhb[0]->nilai + $JP_adhb[0]->nilai + $JB_adhb[0]->nilai + $JU_adhb[0]->nilai;
        $kabkot_adhk = $KS_adhk[0]->nilai + $JS_adhk[0]->nilai + $JT_adhk[0]->nilai + $JP_adhk[0]->nilai + $JB_adhk[0]->nilai + $JU_adhk[0]->nilai;
        // Perhitungan Diskrepansi -> DKI/KABKOT * 100
        $Diskrepansi_adhb = $adhb[0]->nilai / $kabkot_adhb * 100;
        $Diskrepansi_adhk = $adhk[0]->nilai / $kabkot_adhk * 100;

        // Pertumbuhan Y ON Y
        $JenisPDRB = 2; // Untuk Perhitungan pakai ADHK
        $Y_ON_Y = $this->putaran->getDataKomponen(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        $Y_ON_Y_minus1 = $this->putaran->getDataKomponenMinusYear(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        // Perhitungan Y ON Y -> (2023 {Q} - 2022 {Q})/MUTLAK(2022 {Q})*100
        $hasil_Y_ON_Y = ($Y_ON_Y[0]->nilai - $Y_ON_Y_minus1[0]->nilai) / abs($Y_ON_Y_minus1[0]->nilai) * 100;

        // Pertumbuhan Q TO Q
        $Q_TO_Q = $this->putaran->getDataKomponen(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        $Q_TO_Q_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        // Perhitungan Y ON Y -> (2023 {Q} - 2023 {Q-1})/MUTLAK(2023 {Q-1})*100
        $hasil_Q_TO_Q = ($Q_TO_Q[0]->nilai - $Q_TO_Q_minus1[0]->nilai) / abs($Q_TO_Q_minus1[0]->nilai) * 100;

        // Pertumbuhan C TO C
        $C_TO_C = $this->putaran->getDataKomponen(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        $C_TO_C_Year = $this->putaran->getDataKomponenMinusYear(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
        // Perhitungan C TO C -> (sum2023 {Q} - sum2022 {Q})/MUTLAK(sum2022 {Q})*100 -> sum = total Q-awal {Q1} hingga Q-akhir {Q}
        if ($C_TO_C[0]->nilai = 1) {
            $hasil_C_TO_C = ($C_TO_C[0]->nilai) - ($C_TO_C_Year[0]->nilai) / abs($C_TO_C_Year[0]->nilai) * 100;
        } elseif ($C_TO_C[0]->nilai = 2) {
            // Atas
            $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            // Bawah
            $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            //Perhitungan
            $hasil_C_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai) * 100;
        } elseif ($C_TO_C[0]->nilai = 3) {
            // Atas
            $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_minus2 = $this->putaran->getDataKomponenMinus2Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            // Bawah
            $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_Year_minus2 = $this->putaran->getDataKomponenMinusYear2Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            // Perhitungan
            $hasil_C_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai + $C_TO_C_minus2[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai) * 100;
        } elseif ($C_TO_C[0]->nilai = 4) {
            // Atas
            $C_TO_C_minus1 = $this->putaran->getDataKomponenMinusKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_minus2 = $this->putaran->getDataKomponenMinus2Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_minus3 = $this->putaran->getDataKomponenMinus3Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            // Bawah
            $C_TO_C_Year_minus1 = $this->putaran->getDataKomponenMinusYearKuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_Year_minus2 = $this->putaran->getDataKomponenMinusYear2Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            $C_TO_C_Year_minus3 = $this->putaran->getDataKomponenMinusYear3Kuartal(3100, $JenisPDRB, 9); // cara ganti kota otomatis sesuai satker
            // Perhitungan
            $hasil_C_TO_C = ($C_TO_C[0]->nilai + $C_TO_C_minus1[0]->nilai + $C_TO_C_minus2[0]->nilai + $C_TO_C_minus3[0]->nilai) - ($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai + $C_TO_C_Year_minus3[0]->nilai) / abs($C_TO_C_Year[0]->nilai + $C_TO_C_Year_minus1[0]->nilai + $C_TO_C_Year_minus2[0]->nilai + $C_TO_C_Year_minus3[0]->nilai) * 100;
        }

        $statusModel = new StatusModel();
        $data = [
            'title' => 'Beranda',
            'tajuk' => 'Beranda',
            'subTajuk' => '',
            'adhb' => $this->putaran->getDataKomponen(3100, 1, 9), // cara ganti kota otomatis sesuai satker
            'adhk' => $this->putaran->getDataKomponen(3100, 2, 9), // cara ganti kota otomatis sesuai satker
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
