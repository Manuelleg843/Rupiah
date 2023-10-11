<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;

class ArahRevisiController extends BaseController
{
    protected $komponen;
    protected $putaran;
    protected $revisi;
    protected $allQ_1Tahun;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->revisi = new RevisiModel();
    }

    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Arah Revisi',
            'tajuk' => 'Arah Revisi',
            'subTajuk' => 'Arah Revisi Kota',
        ];
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/arahRevisi/arahRevisi');
        echo view('layouts/footer');
    }

    // Ambil Data dari Json

    // PDRB ADHB
    private function arahrevisi_tabel1($kota, $periode)
    {
        $array['rilis']['revisi']['arah'] = array();
        // ADHB
        $jenisPDRB = "1";
        // Tiap Periode
        foreach ($periode as $item) {
            // Untuk Rilis
            $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $item);
            // Untuk Revisi
            $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $item);
            // Perhitungan
            // Tiap Komponen
            foreach ($rilis as $index => $nilai) {
                //Rilis
                $hasil_ADHB_Rilis = $nilai;
                //Revisi
                $hasil_ADHB_Revisi = $revisi[$index];
                //Perbedaan
                $arah_ADHB = $hasil_ADHB_Revisi->nilai - $hasil_ADHB_Rilis->nilai;
                // Push
                array_push($array['rilis']['revisi']['arah'], [
                    'rilis' => $hasil_ADHB_Rilis,
                    'revisi' => $hasil_ADHB_Revisi,
                    'arah' => $arah_ADHB,
                ]);
            }
        }
        return $array;
    }

    // PDRB ADHK
    private function arahrevisi_tabel2($kota, $periode)
    {
        // Gunakan ADHB
        $jenisPDRB = "2";
        // Tiap Periode
        foreach ($periode as $periode) {
            // Untuk Rilis
            $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, "$periode");
            // Untuk Revisi
            $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, "$periode");

            // Perhitungan
            $pointer = 0;
            $array_Rilis = array();
            $array_Revisi = array();
            $array_Arah = array();
            // Tiap Komponen
            foreach ($rilis as $key) {
                //Rilis
                $hasil_ADHK_Rilis = $key->nilai;
                array_push($array_Rilis, $hasil_ADHK_Rilis);
                //Revisi
                $hasil_ADHK_Revisi = $revisi[$pointer]->nilai;
                array_push($array_Revisi, $hasil_ADHK_Revisi);
                //Perbedaan
                $arah_ADHK = $hasil_ADHK_Revisi - $hasil_ADHK_Rilis;
                array_push($array_Arah, $arah_ADHK);
                $pointer++;
            }
        }
        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];
        return $data;
    }

    // Pertunbuhan Y ON Y
    private function arahrevisi_tabel3($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Tiap Periode
        foreach ($periode as $periode) {
            // Untuk Rilis
            $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, "$periode");
            $tahunMin = ($rilis[0]->tahun) - 1; // tahun - 1
            $kuartalMin = $rilis[0]->id_kuartal; // kuartal
            $periodeMin = $tahunMin . 'Q' . $kuartalMin;
            $rilisMin1 = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, "$periodeMin");

            // Untuk Revisi
            $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, "$periode");
            $revisiMin1 = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, "$periodeMin");

            // Perhitungan
            $pointer = 0;
            $array_Rilis = array();
            $array_Revisi = array();
            $array_Arah = array();
            // Tiap Komponen
            foreach ($rilis as $key) {
                //Rilis
                $hasil_Y_ON_Y_Rilis = ($key->nilai - $rilisMin1[$pointer]->nilai) / $rilisMin1[$pointer]->nilai * 100;
                array_push($array_Rilis, $hasil_Y_ON_Y_Rilis);
                //Revisi
                $hasil_Y_ON_Y_Revisi = ($revisi[$pointer]->nilai - $revisiMin1[$pointer]->nilai) / $revisiMin1[$pointer]->nilai * 100;
                array_push($array_Revisi, $hasil_Y_ON_Y_Revisi);
                //Perbedaan
                $arah_Y_ON_Y = $hasil_Y_ON_Y_Revisi - $hasil_Y_ON_Y_Rilis;
                array_push($array_Arah, $arah_Y_ON_Y);
                $pointer++;
            }
        }
        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];
        return $data;
    }

    // Pertumbuhan Q TO Q
    private function arahrevisi_tabel4($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        $tahun = $rilis[0]->tahun; // tahun
        if ($rilis[0]->id_kuartal == 1) {
            $kuartal = 4;
            $tahun = $tahun - 1; // tahun - 1 {Q4}
        } else {
            $kuartal = ($rilis[0]->id_kuartal) - 1; // periode - 1
        }
        $periodeMin = $tahun . 'Q' . $kuartal;
        $rilisMin = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin);

        // Untuk Revisi
        $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);
        $revisiMin = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeMin);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        // Tiap Komponen
        foreach ($rilis as $key) {
            //Rilis
            $hasil_Q_TO_Q_Rilis = ($key->nilai - $rilisMin[$pointer]->nilai) / abs($rilisMin[$pointer]->nilai) * 100;
            array_push($array_Rilis, $hasil_Q_TO_Q_Rilis);
            //Revisi
            $hasil_Q_TO_Q_Revisi = ($revisi[$pointer]->nilai - $revisiMin[$pointer]->nilai) / abs($revisiMin[$pointer]->nilai) * 100;
            array_push($array_Revisi, $hasil_Q_TO_Q_Revisi);
            //Perbedaan
            $arah_Q_TO_Q = $hasil_Q_TO_Q_Revisi - $hasil_Q_TO_Q_Rilis;
            array_push($array_Arah, $arah_Q_TO_Q);
            $pointer++;
        }
        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];
        return $data;
    }

    // Pertumbuhan C TO C
    private function arahrevisi_tabel5($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        // Tiap Komponen
        foreach ($rilis as $key) {
            $tahun = $key->tahun; // tahun
            $tahunMin = $tahun - 1; // tahun - 1
            $kuartal = $key->id_kuartal; //Kuartal
            $KumulatifRilis = 0;
            $KumulatifRevisi = 0;
            $KumulatifRilisMin = 0;
            $KumulatifRevisiMin = 0;
            // Kumulatif
            for ($i = 1; $i <= $kuartal; $i++) {
                // Atas
                $periode = $tahun . 'Q' . $i;
                $rilisKum = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
                $KumulatifRilis = $KumulatifRilis + $rilisKum[$pointer]->nilai;
                $revisiKum = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);
                $KumulatifRevisi = $KumulatifRevisi + $revisiKum[$pointer]->nilai;
                // Bawah
                $periodeMin = $tahunMin . 'Q' . $i;
                $rilisKum = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRilisMin = $KumulatifRilisMin + $rilisKum[$pointer]->nilai;
                $revisiKum = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRevisiMin = $KumulatifRevisiMin + $revisiKum[$pointer]->nilai;
            }
            // Perhitungan
            // Rilis
            $hasil_C_TO_C_Rilis = ($KumulatifRilis - $KumulatifRilisMin) / $KumulatifRilisMin * 100;
            array_push($array_Rilis, $hasil_C_TO_C_Rilis);
            // Revisi
            $hasil_C_TO_C_Revisi = ($KumulatifRevisi - $KumulatifRevisiMin) / $KumulatifRevisiMin * 100;
            array_push($array_Revisi, $hasil_C_TO_C_Revisi);
            // Perbedaan
            $arah_C_TO_C = $hasil_C_TO_C_Revisi - $hasil_C_TO_C_Rilis;
            array_push($array_Arah, $arah_C_TO_C);
            $pointer++;
        }
        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];
        return $data;
    }

    // Indeks Implisit
    private function arahrevisi_tabel6($kota, $periode)
    {
        // Gunakan ADHK
        $ADHB = "1";
        $ADHK = "2";
        // Untuk Rilis
        $rilisADHB = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHB, $periode);
        $rilisADHK = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHK, $periode);
        // Untuk Revisi
        $revisiADHB = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHB, $periode);
        $revisiADHK = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHK, $periode);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        foreach ($rilisADHB as $key) {
            //Rilis
            $hasil_IndeksImplisit_Rilis = ($key->nilai / $rilisADHK[$pointer]->nilai);
            array_push($array_Rilis, $hasil_IndeksImplisit_Rilis);
            //Revisi
            $hasil_IndeksImplisit_Revisi = ($revisiADHB[$pointer]->nilai / $revisiADHK[$pointer]->nilai);
            array_push($array_Revisi, $hasil_IndeksImplisit_Revisi);
            //Perbedaan
            $arah_IndeksImplisit = $hasil_IndeksImplisit_Revisi - $hasil_IndeksImplisit_Rilis;
            array_push($array_Arah, $arah_IndeksImplisit);
            $pointer++;
        }
        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];
        return $data;
    }

    // belum selesai
    // Pertumbuhan Indeks Implisit Y ON Y
    private function arahrevisi_tabel7($kota, $periode)
    {
        // Gunakan ADHK
        $ADHB = "1";
        $ADHK = "2";
    }

    public function getData()
    {
        $jenisTable = $this->request->getPost('jenisTable');
        $kota = $this->request->getPost('kota');
        $periode = $this->request->getPost('periode');

        switch ($jenisTable) {
            case '1':
                $dataarahrevisi = $this->arahrevisi_tabel1($kota, $periode);
                break;
            case '2':
                $dataarahrevisi = $this->arahrevisi_tabel2($kota, $periode);
                break;
        };

        $data = [
            'dataarahrevisi' => $dataarahrevisi,
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periode,
            // 'wilayah' => $wilayah,
            // 'jenisTabel' => $jenisTabel,
        ];

        echo json_encode($data);
    }
}
