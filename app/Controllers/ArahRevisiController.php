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
    public function getData()
    {
        $jenisTable = $this->request->getPost('jenisTable');
        $kota = $this->request->getPost('kota');
        $periode = $this->request->getPost('periode');

        switch ($jenisTable) {
            case '1':
                $dataarahrevisi = $this->arahrevisi_tabel1($kota, $periode);
                break;
                // case '2':
                //     $dataarahrevisi = $this->arahrevisi_tabel2($kota, $periode);
                //     break;
                // case '3':
                //     $dataarahrevisi = $this->arahrevisi_tabel3($kota, $periode);
                //     break;
                // case '4':
                //     $dataarahrevisi = $this->arahrevisi_tabel4($kota, $periode);
                //     break;
                // case '5':
                //     $dataarahrevisi = $this->arahrevisi_tabel5($kota, $periode);
                //     break;
                // case '6':
                //     $dataarahrevisi = $this->arahrevisi_tabel6($kota, $periode);
                //     break;
                // case '7':
                //     $dataarahrevisi = $this->arahrevisi_tabel7($kota, $periode);
                //     break;
        };

        $data = [
            'jenisTable' => $jenisTable,
            'periode' => $periode,
            'dataarahrevisi' => $dataarahrevisi,
        ];

        echo json_encode($data);
    }

    // PDRB ADHB
    private function arahrevisi_tabel1($kota, $periode)
    {
        // ADHB
        $jenisPDRB = "1";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        // Untuk Revisi
        $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        foreach ($rilis as $key) {
            //Rilis
            $hasil_ADHB_Rilis = $key->nilai;
            array_push($array_Rilis, $hasil_ADHB_Rilis);
            //Revisi
            $hasil_ADHB_Revisi = $revisi[$pointer]->nilai;
            array_push($array_Revisi, $hasil_ADHB_Revisi);
            //Perbedaan
            $arah_ADHB = $hasil_ADHB_Revisi - $hasil_ADHB_Rilis;
            array_push($array_Arah, $arah_ADHB);
            $pointer++;
        }

        $data = [
            'array_Rilis' => $array_Rilis,
            'array_Revisi' => $array_Revisi,
            'array_Arah' => $array_Arah,
        ];

        return $data;
    }

    // PDRB ADHK
    private function arahrevisi_tabel2($kota, $periode)
    {
        // Gunakan ADHB
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        // Untuk Revisi
        $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
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

    // Pertunbuhan Y ON Y
    private function arahrevisi_tabel3($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        $tahunRilisMin1 = ($rilis[0]->tahun) - 1; // tahun - 1
        $kuartalRilisMin1 = $rilis[0]->id_kuartal; // periode
        $periodeMin1 = $tahunRilisMin1 . 'Q' . $kuartalRilisMin1;
        $rilisMin1 = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin1);

        // Untuk Revisi
        $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);
        $tahunRevisiMin1 = ($revisi[0]->tahun) - 1; // tahun - 1
        $kuartalRevisiMin1 = $revisi[0]->id_kuartal; // periode
        $periodeRevisiMin1 = $tahunRevisiMin1 . 'Q' . $kuartalRevisiMin1;
        $revisiMin1 = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeRevisiMin1);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
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

    // Pertumbuhan Q TO Q
    private function arahrevisi_tabel4($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        $tahunRilisMin1 = $rilis[0]->tahun; // tahun
        $kuartalRilisMin1 = ($rilis[0]->id_kuartal) - 1; // periode - 1
        $periodeMin1 = $tahunRilisMin1 . 'Q' . $kuartalRilisMin1;
        $rilisMin1 = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin1);

        // Untuk Revisi
        $revisi = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode);
        $tahunRevisiMin1 = $revisi[0]->tahun; // tahun
        $kuartalRevisiMin1 = ($revisi[0]->id_kuartal) - 1; // periode - 1
        $periodeRevisiMin1 = $tahunRevisiMin1 . 'Q' . $kuartalRevisiMin1;
        $revisiMin1 = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeRevisiMin1);

        // Perhitungan
        $pointer = 0;
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        foreach ($rilis as $key) {
            //Rilis
            $hasil_Q_TO_Q_Rilis = ($key->nilai - $rilisMin1[$pointer]->nilai) / $rilisMin1[$pointer]->nilai * 100;
            array_push($array_Rilis, $hasil_Q_TO_Q_Rilis);
            //Revisi
            $hasil_Q_TO_Q_Revisi = ($revisi[$pointer]->nilai - $revisiMin1[$pointer]->nilai) / $revisiMin1[$pointer]->nilai * 100;
            array_push($array_Revisi, $hasil_Q_TO_Q_Revisi);
            //Perbedaan
            $arah_Q_TO_Q = $hasil_Q_TO_Q_Revisi - $hasil_Q_TO_Q_Rilis;
            array_push($array_Arah, $arah_Q_TO_Q);
            $pointer++;
        }
    }

    // Pertumbuhan C TO C
    private function arahrevisi_tabel5($kota, $periode)
    {
        // Gunakan ADHK
        $jenisPDRB = "2";
        // Untuk Rilis
        $rilis = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periode);
        $array_Rilis = array();
        $array_Revisi = array();
        $array_Arah = array();
        foreach ($rilis as $key) {
            $tahunRilis = $key->tahun; // tahun
            $tahunRilisMin = $tahunRilis - 1; // tahun - 1
            $kuartalRilis = $key->id_kuartal; //Kuartal
            $KumulatifRilis = 0;
            $KumulatifRevisi = 0;
            $KumulatifRilisMin = 0;
            $KumulatifRevisiMin = 0;
            // Kumulatif
            for ($i = 1; $i = $kuartalRilis; $i++) {
                // Atas
                $periodeMin = $tahunRilis . 'Q' . $i;
                $rilisKum = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRilis = $KumulatifRilis + $rilisKum[0]->nilai;
                $revisiKum = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRevisi = $KumulatifRevisi + $revisiKum[0]->nilai;
                // Bawah
                $periodeMin = $tahunRilisMin . 'Q' . $i;
                $rilisKum = $this->putaran->get_data_rilis_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRilisMin = $KumulatifRilisMin + $rilisKum[0]->nilai;
                $revisiKum = $this->revisi->get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periodeMin);
                $KumulatifRevisiMin = $KumulatifRevisiMin + $revisiKum[0]->nilai;
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
        }
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
    }

    // belum selesai
    // Pertumbuhan Indeks Implisit Y ON Y
    private function arahrevisi_tabel7($kota, $periode)
    {
        // Gunakan ADHK
        $ADHB = "1";
        $ADHK = "2";
        // Untuk Rilis
        $rilisADHB = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHB, $periode);
        $rilisADHK = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHK, $periode);
        $tahunRilisMin1 = ($rilisADHB[0]->tahun) - 1; // tahun - 1
        $kuartalRilisMin1 = $rilisADHB[0]->id_kuartal; // periode
        $periodeRilisMin1 = $tahunRilisMin1 . 'Q' . $kuartalRilisMin1;
        $rilisADHBMin1 = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHB, $periodeRilisMin1);
        $rilisADHKMin1 = $this->putaran->get_data_rilis_wilayah_periode($kota, $ADHK, $periodeRilisMin1);
        // Untuk Revisi
        $revisiADHB = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHB, $periode);
        $revisiADHK = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHK, $periode);
        $tahunRevisiMin1 = ($revisiADHB[0]->tahun) - 1; // tahun - 1
        $kuartalRevisiMin1 = $revisiADHB[0]->id_kuartal; // periode
        $periodeRevisiMin1 = $tahunRevisiMin1 . 'Q' . $kuartalRevisiMin1;
        $revisiADHBMin1 = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHB, $periodeRevisiMin1);
        $revisiADHKMin1 = $this->revisi->get_data_revisi_wilayah_periode($kota, $ADHK, $periodeRevisiMin1);


        // Perhitungan
        $pointer = 0;
        $arrayIndeksImplisit_Y_ON_Y_Rilis = array();
        $arrayIndeksImplisit_Y_ON_Y_Revisi = array();
        $arrayIndeksImplisit_Y_ON_Y_Arah = array();
    }
}
