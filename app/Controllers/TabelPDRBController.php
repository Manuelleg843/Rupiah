<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Dompdf\Dompdf;
use Dompdf\Options;

class TabelPDRBController extends BaseController
{
    protected $nilaiDiskrepansi;
    protected $komponen;
    protected $putaran;
    protected $revisi;
    protected $wilayah;

    public function __construct()
    {
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->revisi = new RevisiModel();
        $this->wilayah = new WilayahModel();
    }

    public function viewTabelRingkasan()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota',
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelRingkasan');
        echo view('layouts/footer');
    }

    public function viewTabelPerKota()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        //
        $data = [
            'title' => 'Rupiah | Tabel Per Kota',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel PDRB Per Kota (PKRT 7 Komponen)',
            'komponen' => $this->komponen->get_data(),
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelPerKota', $data);
        echo view('layouts/footer');
    }

    private function countTable2($periodes, $jenisPDRB, $kota)
    {
        // Melakukan looping untuk mengambil data PDRB berdasarkan periode
        foreach ($periodes as $periode) {

            // pengecekan apakah data sudah ada di tabel revisi atau belum
            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                $dataPDRB[] = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);
            } else {

                // pengecekan apakah data sudah ada di tabel putaran atau belum
                if ($this->putaran->getDataFinal($jenisPDRB, $kota, $periode)) {
                    $dataPDRB[] = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);
                } else {
                    $dataPDRB[] = [];
                }
            }
        }

        return $dataPDRB;
    }

    private function countTable3($periodes, $jenisPDRB, $kota)
    {
        // Tempat data disimpan
        $dataPDRBDistribusiPersentase = [];

        // looping untuk mengambil data PDRB berdasarkan periode
        foreach ($periodes as $periode) {

            // Tempat data periode i disimpan
            $dataPDRBDistribusiPersentasePeriodei = [];

            // pengecekan apakah data sudah ada di tabel revisi atau belum
            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {

                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);

                // menghitung total PDRB
                $totalPDRB = $dataPDRB[17]->nilai;

                // looping untuk menghitung persentase
                foreach ($dataPDRB as $komponen) {
                    $komponen->nilai = $komponen->nilai / $totalPDRB;
                    array_push($dataPDRBDistribusiPersentasePeriodei, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRBDistribusiPersentase, $dataPDRBDistribusiPersentasePeriodei);
            } else {

                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                if ($this->putaran->getDataFinal($jenisPDRB, $kota, $periode)) {
                    $dataPDRB = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);

                    // menghitung total PDRB
                    $totalPDRB = $dataPDRB[17]->nilai;

                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $komponen) {
                        $komponen->nilai = $komponen->nilai / $totalPDRB;
                        array_push($dataPDRBDistribusiPersentasePeriodei, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataPDRBDistribusiPersentase, $dataPDRBDistribusiPersentasePeriodei);
            }
        }

        return $dataPDRBDistribusiPersentase;
    }

    private function countTable4($periodes, $jenisPDRB, $kota)
    {
        // Tempat data disimpan
        $dataPertumbuhanPDRBDADHK = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPertumbuhanPDRBDADHKi = [];

            // membuat variabel periode sebelumnya 
            $periodeSebelumnya = 0;

            // pengecekan apakah periode merupakan periode tahunan atau kuartalan
            if (strlen($periode) == 6) {
                $Q = substr($periode, -1);
                $tahun =  substr($periode, 0, 4);
                if ($Q == 1) {
                    $tahunBefore = $tahun - 1;
                    $periodeSebelumnya = $tahunBefore . 'Q4';
                } else {
                    $Qmin1 = $Q - 1;
                    $periodeSebelumnya = $tahun . 'Q' . $Qmin1;
                }

                // pengecekan apakah data sudah ada di tabel revisi atau belum
                if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                    // mengambil data final (mengambil dari tabel revisi)
                    $dataPDRB = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);
                    $dataPDRBBefore = $this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);

                    if ($dataPDRBBefore) {
                        // looping untuk menghitung persentase
                        foreach ($dataPDRB as $index => $komponen) {
                            $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                            array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                        }
                    }

                    // memasukkan data ke array
                    array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
                } else {
                    // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                    $dataPDRB = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);
                    if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya)) {
                        $dataPDRBBefore = $this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);
                    } else {
                        $dataPDRBBefore = $this->putaran->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);
                    }

                    if ($dataPDRBBefore) {
                        // looping untuk menghitung persentase
                        foreach ($dataPDRB as $index => $komponen) {
                            $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                            array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                        }
                    }

                    // memasukkan data ke array
                    array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
                }
            } else {

                // memasukkan array kosong ke array agar tidak error
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            }
        }

        return $dataPertumbuhanPDRBDADHK;
    }

    private function countTable5($periodes, $jenisPDRB, $kota)
    {
        // Tempat data disimpan
        $dataPertumbuhanPDRBDADHK = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPertumbuhanPDRBDADHKi = [];

            // membuat variabel periode sebelumnya 
            $periodeSebelumnya = 0;

            // pengecekan apakah periode merupakan periode tahunan atau kuartalan
            if (strlen($periode) == 6) {
                $Q = substr($periode, -2);
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore . $Q;
            } else {
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore;
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);
                $dataPDRBBefore = $this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);

                if ($dataPDRBBefore) {
                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }
                }


                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                $dataPDRB = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);

                // penge
                if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya)) {
                    $dataPDRBBefore = $this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);
                } else {
                    $dataPDRBBefore = $this->putaran->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);
                }

                if ($dataPDRBBefore) {
                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            }
        }

        return $dataPertumbuhanPDRBDADHK;
    }

    private function countTable6($periodes, $jenisPDRB, $kota)
    {
        // Tempat data disimpan
        $dataPertumbuhanPDRBDADHK = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPertumbuhanPDRBDADHKi = [];

            $tahun =  substr($periode, 0, 4);
            $tahunBefore = $tahun - 1;

            $arrayForCumulative = [];
            $arrayForCumulativeSebelumnya = [];
            if (strlen($periode) == 6) {
                for ($i = 0; $i < substr($periode, -1); $i++) {
                    # code...
                    array_push($arrayForCumulative, $tahun . 'Q' . ($i + 1));
                    array_push($arrayForCumulativeSebelumnya, $tahunBefore . 'Q' . ($i + 1));
                }
            } else {
                for ($i = 0; $i < 4; $i++) {
                    # code...
                    array_push($arrayForCumulative, $tahun . 'Q' . ($i + 1));
                    array_push($arrayForCumulativeSebelumnya, $tahunBefore . 'Q' . ($i + 1));
                }
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);

                if ($this->revisi->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya)) {
                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $nilaiKumulatifKomponeni = 0;
                        $nilaiKumulatifSebelumnyaKomponeni = 0;
                        foreach ($arrayForCumulative as $key => $value) {
                            $nilaiKumulatifKomponeni += $this->revisi->getDataFinal($jenisPDRB, $kota, $value)[$index]->nilai;
                            $nilaiKumulatifSebelumnyaKomponeni += $this->revisi->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                        }
                        $komponen->nilai = ($nilaiKumulatifKomponeni - $nilaiKumulatifSebelumnyaKomponeni) * 100 / abs($nilaiKumulatifSebelumnyaKomponeni);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            } else {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);

                if ($this->putaran->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya)) {
                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $nilaiKumulatifKomponeni = 0;
                        $nilaiKumulatifSebelumnyaKomponeni = 0;
                        foreach ($arrayForCumulative as $key => $value) {
                            $nilaiKumulatifKomponeni += $this->putaran->getDataFinal($jenisPDRB, $kota, $value)[$index]->nilai;
                            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai) {
                                $nilaiKumulatifSebelumnyaKomponeni += $this->revisi->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                            } else {
                                $nilaiKumulatifSebelumnyaKomponeni += $this->putaran->getDataFinal($jenisPDRB, $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                            }
                        }
                        $komponen->nilai = ($nilaiKumulatifKomponeni - $nilaiKumulatifSebelumnyaKomponeni) * 100 / abs($nilaiKumulatifSebelumnyaKomponeni);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            }
        }

        return $dataPertumbuhanPDRBDADHK;
    }

    private function countTable7($periodes, $kota)
    {
        // Tempat data disimpan
        $dataPDRB = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPDRBi = [];

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('1', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB1 = $this->revisi->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->revisi->getDataFinal('2', $kota, $periode);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $komponen->nilai = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                $dataPDRB1 = $this->putaran->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->putaran->getDataFinal('2', $kota, $periode);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $komponen->nilai = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            }
        }

        return $dataPDRB;
    }

    private function countTable8($periodes, $kota)
    {
        // Tempat data disimpan
        $dataPDRB = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPDRBi = [];

            if (strlen($periode) == 6) {
                $Q = substr($periode, -1);
                $tahun =  substr($periode, 0, 4);
                if ($Q == 1) {
                    $tahunBefore = $tahun - 1;
                    $periodeSebelumnya = $tahunBefore . 'Q4';
                } else {
                    $Qmin1 = $Q - 1;
                    $periodeSebelumnya = $tahun . 'Q' . $Qmin1;
                }
            } else {
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore;
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('1', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB1 = $this->revisi->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->revisi->getDataFinal('2', $kota, $periode);
                $dataPDRB1Before = $this->revisi->getDataFinal('1', $kota, $periodeSebelumnya);
                $dataPDRB2Before = $this->revisi->getDataFinal('2', $kota, $periodeSebelumnya);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $indexImplisitKomponeni = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    $indexImplisitSebelumnyaKomponeni = $dataPDRB1Before[$index]->nilai / $dataPDRB2Before[$index]->nilai;
                    $komponen->nilai = ($indexImplisitKomponeni - $indexImplisitSebelumnyaKomponeni) * 100 / abs($indexImplisitSebelumnyaKomponeni);
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                $dataPDRB1 = $this->putaran->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->putaran->getDataFinal('2', $kota, $periode);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $komponen->nilai = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            }
        }

        return $dataPDRB;
    }

    private function countTable9($periodes, $kota)
    {
        // Tempat data disimpan
        $dataPDRB = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPDRBi = [];

            // membuat variabel periode sebelumnya 
            $periodeSebelumnya = 0;

            if (strlen($periode) == 6) {
                $Q = substr($periode, -2);
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore . $Q;
            } else {
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore;
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('1', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB1 = $this->revisi->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->revisi->getDataFinal('2', $kota, $periode);
                $dataPDRB1Before = $this->revisi->getDataFinal('1', $kota, $periodeSebelumnya);
                $dataPDRB2Before = $this->revisi->getDataFinal('2', $kota, $periodeSebelumnya);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $indexImplisitKomponeni = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    $indexImplisitSebelumnyaKomponeni = $dataPDRB1Before[$index]->nilai / $dataPDRB2Before[$index]->nilai;
                    $komponen->nilai = ($indexImplisitKomponeni - $indexImplisitSebelumnyaKomponeni) * 100 / abs($indexImplisitSebelumnyaKomponeni);
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                $dataPDRB1 = $this->putaran->getDataFinal('1', $kota, $periode);
                $dataPDRB2 = $this->putaran->getDataFinal('2', $kota, $periode);
                $dataPDRB1Before = $this->putaran->getDataFinal('1', $kota, $periodeSebelumnya);
                $dataPDRB2Before = $this->putaran->getDataFinal('2', $kota, $periodeSebelumnya);

                // looping untuk menghitung persentase
                foreach ($dataPDRB1 as $index => $komponen) {
                    $indexImplisitKomponeni = $komponen->nilai / $dataPDRB2[$index]->nilai;
                    $indexImplisitSebelumnyaKomponeni = $dataPDRB1Before[$index]->nilai / $dataPDRB2Before[$index]->nilai;
                    $komponen->nilai = ($indexImplisitKomponeni - $indexImplisitSebelumnyaKomponeni) * 100 / abs($indexImplisitSebelumnyaKomponeni);
                    array_push($dataPDRBi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPDRB, $dataPDRBi);
            }
        }

        return $dataPDRB;
    }

    private function countTable10($periodes, $kota)
    {
        // Tempat data disimpan
        $dataSumberPertumbuhanQ2Q = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataSumberPertumbuhanQ2Qi = [];

            if (strlen($periode) == 6) {
                $Q = substr($periode, -1);
                $tahun =  substr($periode, 0, 4);
                if ($Q == 1) {
                    $tahunBefore = $tahun - 1;
                    $periodeSebelumnya = $tahunBefore . 'Q4';
                } else {
                    $Qmin1 = $Q - 1;
                    $periodeSebelumnya = $tahun . 'Q' . $Qmin1;
                }
            } else {
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore;
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('2', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal('2', $kota, $periode);
                $dataPDRBBefore = $this->revisi->getDataFinal('2', $kota, $periodeSebelumnya);

                // looping untuk menghitung persentase
                foreach ($dataPDRB as $index => $komponen) {
                    $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[17]->nilai);
                    array_push($dataSumberPertumbuhanQ2Qi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataSumberPertumbuhanQ2Q, $dataSumberPertumbuhanQ2Qi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                if ($this->putaran->getDataFinal('2', $kota, $periode)) {
                    $dataPDRB = $this->putaran->getDataFinal('2', $kota, $periode);
                    $dataPDRBBefore = $this->putaran->getDataFinal('2', $kota, $periodeSebelumnya);

                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[17]->nilai);
                        array_push($dataSumberPertumbuhanQ2Qi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataSumberPertumbuhanQ2Q, $dataSumberPertumbuhanQ2Qi);
            }
        }

        return $dataSumberPertumbuhanQ2Q;
    }

    private function countTable11($periodes, $kota)
    {
        // Tempat data disimpan
        $dataSumberPertumbuhanYOY = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataSumberPertumbuhanYOYi = [];

            if (strlen($periode) == 6) {
                $Q = substr($periode, -2);
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore . $Q;
            } else {
                $tahun =  substr($periode, 0, 4);
                $tahunBefore = $tahun - 1;
                $periodeSebelumnya = $tahunBefore;
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('2', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal('2', $kota, $periode);
                $dataPDRBBefore = $this->revisi->getDataFinal('2', $kota, $periodeSebelumnya);

                // looping untuk menghitung persentase
                foreach ($dataPDRB as $index => $komponen) {
                    $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[17]->nilai);
                    array_push($dataSumberPertumbuhanYOYi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataSumberPertumbuhanYOY, $dataSumberPertumbuhanYOYi);
            } else {
                // mengambil data final berdasarkan putaran yang telah di ambil (mengambil dari tabel putaran)
                if ($this->putaran->getDataFinal('2', $kota, $periode)) {
                    $dataPDRB = $this->putaran->getDataFinal('2', $kota, $periode);
                    $dataPDRBBefore = $this->putaran->getDataFinal('2', $kota, $periodeSebelumnya);

                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[17]->nilai);
                        array_push($dataSumberPertumbuhanYOYi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataSumberPertumbuhanYOY, $dataSumberPertumbuhanYOYi);
            }
        }

        return $dataSumberPertumbuhanYOY;
    }

    private function countTable12($periodes, $kota)
    {
        // Tempat data disimpan
        $dataPertumbuhanPDRBDADHK = [];

        foreach ($periodes as $periode) {
            // Tempat data periode i disimpan
            $dataPertumbuhanPDRBDADHKi = [];

            $tahun =  substr($periode, 0, 4);
            $tahunBefore = $tahun - 1;

            $arrayForCumulative = [];
            $arrayForCumulativeSebelumnya = [];
            if (strlen($periode) == 6) {
                for ($i = 0; $i < substr($periode, -1); $i++) {
                    # code...
                    array_push($arrayForCumulative, $tahun . 'Q' . ($i + 1));
                    array_push($arrayForCumulativeSebelumnya, $tahunBefore . 'Q' . ($i + 1));
                }
            } else {
                for ($i = 0; $i < 4; $i++) {
                    # code...
                    array_push($arrayForCumulative, $tahun . 'Q' . ($i + 1));
                    array_push($arrayForCumulativeSebelumnya, $tahunBefore . 'Q' . ($i + 1));
                }
            }

            // pengecekan apakah data sudah final atau belum
            if ($this->revisi->getDataFinal('2', $kota, $periode)) {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->revisi->getDataFinal('2', $kota, $periode);

                // looping untuk menghitung persentase
                foreach ($dataPDRB as $index => $komponen) {
                    $nilaiKumulatifKomponeni = 0;
                    $nilaiKumulatifSebelumnyaKomponeni = 0;
                    $nilaiKumulatifSebelumnyaKomponenPDRB = 0;
                    foreach ($arrayForCumulative as $key => $value) {
                        $nilaiKumulatifSebelumnyaKomponenPDRB += $this->revisi->getDataFinal('2', $kota, $arrayForCumulativeSebelumnya[$key])[17]->nilai;
                    }
                    foreach ($arrayForCumulative as $key => $value) {
                        $nilaiKumulatifKomponeni += $this->revisi->getDataFinal('2', $kota, $value)[$index]->nilai;
                        $nilaiKumulatifSebelumnyaKomponeni += $this->revisi->getDataFinal('2', $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                    }
                    $komponen->nilai = ($nilaiKumulatifKomponeni - $nilaiKumulatifSebelumnyaKomponeni) * 100 / abs($nilaiKumulatifSebelumnyaKomponenPDRB);
                    array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                }

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            } else {
                // mengambil data final (mengambil dari tabel revisi)
                if ($this->putaran->getDataFinal('2', $kota, $periode)) {
                    # code...
                    $dataPDRB = $this->putaran->getDataFinal('2', $kota, $periode);

                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $nilaiKumulatifKomponeni = 0;
                        $nilaiKumulatifSebelumnyaKomponeni = 0;
                        foreach ($arrayForCumulative as $key => $value) {
                            $nilaiKumulatifKomponeni += $this->putaran->getDataFinal('2', $kota, $value)[$index]->nilai;
                            if ($this->revisi->getDataFinal('2', $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai) {
                                $nilaiKumulatifSebelumnyaKomponeni += $this->revisi->getDataFinal('2', $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                            } else {
                                $nilaiKumulatifSebelumnyaKomponeni += $this->putaran->getDataFinal('2', $kota, $arrayForCumulativeSebelumnya[$key])[$index]->nilai;
                            }
                        }
                        $komponen->nilai = ($nilaiKumulatifKomponeni - $nilaiKumulatifSebelumnyaKomponeni) * 100 / abs($nilaiKumulatifSebelumnyaKomponeni);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }
                }

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            }
        }

        return $dataPertumbuhanPDRBDADHK;
    }

    private function countTable13($periodes, $kota)
    {
        $dataPDRB = [];

        $pertumbuhanYoy = $this->countTable5($periodes, '2', $kota);
        $PertumbuhanQ2Q = $this->countTable4($periodes, '2', $kota);
        $PertumbuhanC2C = $this->countTable6($periodes, '2', $kota);
        $ImplisitYoY = $this->countTable9($periodes, $kota);
        $ImplisitQ2Q = $this->countTable8($periodes, $kota);

        array_push($dataPDRB, $pertumbuhanYoy);
        array_push($dataPDRB, $PertumbuhanQ2Q);
        array_push($dataPDRB, $PertumbuhanC2C);
        array_push($dataPDRB, $ImplisitYoY);
        array_push($dataPDRB, $ImplisitQ2Q);
        return $dataPDRB;
    }

    // Fungsi untuk download excel sesuai dengan checkbox yang dipilih
    public function download()
    {
        // Jika tidak ada wilayah atau checkbox yang dipilih, maka akan diarahkan kembali ke halaman upload data
        $postData = $this->request->getPost();
        if ($postData['kotaJudulModal'] == "") {
            return redirect()->to('/uploadData/angkaPDRB')->with('msg', 'Pilih wilayah dan periode untuk download template.');
        } else if (count($postData) <= 1) {
            return redirect()->to('/uploadData/angkaPDRB')->with('msg', 'Pilih wilayah dan periode untuk download template.');
        }

        // Mengambil wilayah terpilih dari array postData
        $wilayahTerpilih = $this->wilayah->where('id_wilayah', array_pop($postData))->first()['wilayah'];

        // Konfigurasi untuk generate excel
        require_once ROOTPATH . 'vendor/autoload.php';
        $filename = 'Template Upload PDRB - ' . $wilayahTerpilih . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $mySpreadsheet = new Spreadsheet();
        $mySpreadsheet->removeSheetByIndex(0);

        // Sheet 1 untuk adhb & 2 untuk adhk
        $worksheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "ADHB");
        $mySpreadsheet->addSheet($worksheet1, 0);
        $worksheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "ADHK");
        $mySpreadsheet->addSheet($worksheet2, 1);

        // judul sheet
        $title = ["Template Upload PDRB - Juta Rupiah"];
        $sheet1Data = [];
        $sheet2Data = [];

        // Membuat header kolom sesuai checkbox periode yang dipilih
        $columnHeaders = ['Komponen'];
        foreach ($postData as $name => $value) {
            array_push($columnHeaders, $name);
        }
        array_push($sheet1Data, $columnHeaders);
        array_push($sheet2Data, $columnHeaders);

        // Mengambil komponen PDRB serta deskripsinya dari database
        $komponen7DataObj = $this->komponen->get_data();
        $komponen7Data = [];
        foreach ($komponen7DataObj as $row) {
            $komponen7Data[] = (array)$row;
        }

        // Menambahkan deskripsi komponen sebagai baris pada excel
        foreach ($komponen7Data as $komponen) {
            $deskripsi = "";
            foreach ($komponen as $kolom) {
                $deskripsi .= $kolom;
                if (key($komponen) != array_key_last($komponen)) {
                    $deskripsi .= ". ";
                }
                next($komponen);
            }
            array_push($sheet1Data, [$deskripsi]);
            array_push($sheet2Data, [$deskripsi]);
            next($komponen7Data);
        }

        // Mengisi data pada excel
        $worksheet1->fromArray([$title]);
        $worksheet1->fromArray([['ADHB']], null, 'A2');
        $worksheet1->fromArray([[$wilayahTerpilih]], null, 'A3');
        $worksheet1->fromArray($sheet1Data, null, 'A5');
        $worksheet2->fromArray([$title]);
        $worksheet2->fromArray([['ADHK']], null, 'A2');
        $worksheet2->fromArray([[$wilayahTerpilih]], null, 'A3');
        $worksheet2->fromArray($sheet2Data, null, 'A5');

        // Menebalkan judul tabel
        $cellAddresses = ['A1', 'A2', 'A3'];
        foreach ($cellAddresses as $cellAddress) {
            $cell = $worksheet1->getCell($cellAddress);
            $cell->getStyle()->getFont()->setBold(true);

            $cell = $worksheet2->getCell($cellAddress);
            $cell->getStyle()->getFont()->setBold(true);
        }

        // Mengatur lebar kolom
        $worksheets = [$worksheet1, $worksheet2];
        foreach ($worksheets as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        // Download file excel
        $writer = new Xlsx($mySpreadsheet);
        $writer->save('php://output');
        die;
    }

    public function getDataTabelPerKota()
    {
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $kota = $this->request->getPost('kota');
        $periodes = $this->request->getPost('selectedPeriode');
        if (empty($periodes)) {
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

            $periodes = [$getPeriode()];
        }
        sort($periodes);


        switch ($jenisPDRB) {
            case '1':
                // Menghitung Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah) di fungsi countTable2
                $dataPDRB = $this->countTable2($periodes, '1', $kota);
                break;
            case '2':
                // Menghitung Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah) di fungsi countTable2
                $dataPDRB = $this->countTable2($periodes, '2', $kota);
                break;
            case '3':
                // Menghitung Tabel 3.3. Tabel Distribusi Persentase PDRB ADHB di fungsi countTable3
                $dataPDRB = $this->countTable3($periodes, '1', $kota);
                break;
            case '4':
                // Menghitung Tabel 3.4. Pertumbuhan PDRB ADHK (Q-TO-Q) di fungsi countTable4
                $dataPDRB = $this->countTable4($periodes, '2', $kota);
                break;
            case '5':
                // Menghitung Tabel 3.5. Pertumbuhan PDRB ADHK (Y-ON-Y) di fungsi countTable5
                $dataPDRB = $this->countTable5($periodes, '2', $kota);
                break;
            case '6':
                // Menghitung Tabel 3.6. Pertumbuhan PDRB ADHK (C-TO-C) di fungsi countTable6
                $dataPDRB = $this->countTable6($periodes, '2', $kota);
                break;
            case '7':
                // Menghitung Tabel 3.7. Indeks Implisit PDRB (Persen) di fungsi countTable7
                $dataPDRB = $this->countTable7($periodes, $kota);
                break;
            case '8':
                // Menghitung Tabel 3.8. Pertumbuhan Indeks Implisit (Q-TO-Q) di fungsi countTable8
                $dataPDRB = $this->countTable8($periodes, $kota);
                break;
            case '9':
                // Menghitung Tabel 3.9. Pertumbuhan Indeks Implisit (Y-ON-Y) di fungsi countTable9
                $dataPDRB = $this->countTable9($periodes, $kota);
                break;
            case '10':
                // Menghitung Tabel 3.10. Sumber Pertumbuhan Ekonomi (Q-TO-Q) di fungsi countTable10
                $dataPDRB = $this->countTable10($periodes, $kota);
                break;
            case '11':
                // Menghitung Tabel 3.11. Sumber Pertumbuhan Ekonomi (Y-ON-Y) di fungsi countTable11
                $dataPDRB = $this->countTable11($periodes, $kota);
                break;
            case '12':
                // Menghitung Tabel 3.12. Sumber Pertumbuhan Ekonomi (C-TO-C) di fungsi countTable12
                $dataPDRB = $this->countTable12($periodes, $kota);
                break;
            case '13':
                // Menghitung Tabel Tabel 3.13. Ringkasan Pertumbuhan Ekstrem Provinsi di fungsi countTable13
                $dataPDRB = $this->countTable13($periodes, $kota);
                break;
            default:
                // Menghitung Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah) di fungsi countTable2
                $dataPDRB = $this->countTable2($periodes, '1', $kota);
                break;
        }



        $data = [
            'dataPDRB' => $dataPDRB,
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periodes,
            'wilayah' => $kota,
        ];


        echo json_encode($data);
    }

    // sort data 
    public function sortData($data, $kode, $desc = false)
    {

        if ($desc) {
            usort($data, function ($a, $b) use ($kode) {
                if ($kode == 1) { // 1 IS SORT BY putaran
                    return strcmp($b->putaran, $a->putaran);
                } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                    return strcmp($b->id_komponen, $a->id_komponen);
                }
            });
        }

        usort($data, function ($a, $b) use ($kode) {
            if ($kode == 1) { // 1 IS SORT BY putaran
                return strcmp($a->putaran, $b->putaran);
            } else if ($kode == 2) { // 2 IS SORT BY ID_KOMPONEN
                return strcmp($a->id_komponen, $b->id_komponen);
            }
        });

        return $data;
    }

    public function getAllDataHistory($periode, $jenisPDRB, $kota, $putaran)
    {
        // get data dari database 
        $dataPDRB = [];
        foreach ($putaran as $p) {
            $dataObj = [];
            if ($this->putaran->getDataHistory($jenisPDRB, $kota, $p, $periode)) {
                $dataObj = $this->putaran->getDataHistory($jenisPDRB, $kota, $p, $periode);
            } else {
                foreach ($this->putaran->getDataHistory($jenisPDRB, $kota, $p - 1, $periode) as $komponeni) {
                    $komponeni->nilai = null;
                    array_push($dataObj, $komponeni);
                }
            }
            $dataPDRB = array_merge($dataPDRB, $dataObj);
        }

        return $dataPDRB;
    }

    public function getDataHistory()
    {
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $periode = $this->request->getPost('periode');
        $putaran = $this->request->getPost('putaran');
        $kota = $this->request->getPost('kota');

        if (empty($this->request->getPost('putaran'))) {
            $putaran = ['1'];
        }

        $dataHistory = $this->getAllDataHistory($periode, $jenisPDRB, $kota, $putaran);
        $dataHistory = $this->sortData($dataHistory, 1);
        $dataHistory = $this->sortData($dataHistory, 2);

        $data = [
            'dataHistory' => $dataHistory,
            'komponen' => $this->komponen->get_data(),
            'putaran' => $putaran,
        ];

        echo json_encode($data);
    }


    public function getData()
    {
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $kota = $this->request->getPost('kota');
        $putaran = $this->request->getPost('putaran');
        $periode = $this->request->getPost('periode');

        $data = [
            'dataPDRB' => $this->putaran->getDataHistory($jenisPDRB, $kota, $putaran, $periode),
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periode
        ];

        echo json_encode($data);
    }

    public function viewTabelHistoryPutaran()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $currentYear = date('Y');
        $currentMonth = date('n');
        $currentQuarter = ceil($currentMonth / 3);
        switch ($currentQuarter) {
            case 1:
                $currentYear = $currentYear - 1;
                $periode = [$currentYear . 'Q1', $currentYear . 'Q2', $currentYear . 'Q3', $currentYear . 'Q4'];
                break;
            case 2:
                $periode = [$currentYear . 'Q1'];
                break;
            case 3:
                $periode = [$currentYear . 'Q1', $currentYear . 'Q2'];
                break;
            case 4:
                $periode = [$currentYear . 'Q1', $currentYear . 'Q2', $currentYear . 'Q3'];
                break;
        }
        $putaran = array_map('current', $this->putaran->getAllPutaranByPeriode(end($periode)));

        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'putaran' => $putaran,
            'periode' => $periode,
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }

    // Fungsi untuk mengambil semua putaran dari periode
    public function getPutaranPeriode($periode)
    {
        $putaran = array_map('current', $this->putaran->getAllPutaranByPeriode($periode));

        echo json_encode($putaran);
    }

    public function getAllPeriode()
    {
        $allPeriode = $this->putaran->getAllPeriode();

        return $allPeriode;
    }

    public function exportExcelHistory($jenisPDRB, $kota, $putaran, $periode, $nama, $all = false)
    {
        // kalo $all = true (export semua putaran), selected periode adalah semua periode 
        if ($all) {
            $putaran = $this->putaran->getAllPutaran($kota, $periode);
            $putaranArr = [];
            foreach ($putaran as $val) {
                array_push($putaranArr, $val->putaran);
            }
        } else {
            // ubah putaran dari string jadi array 
            $putaranArr = explode(",", $putaran);
            sort($putaranArr);
        }

        // get komponen
        $komponen = $this->komponen->findAll();
        sort($komponen);

        // judul tabel 
        $title = [$nama];

        // get data 
        $dataPDRB = $this->getAllDataHistory($periode, $jenisPDRB, $kota, $putaranArr);
        $dataPDRB = $this->sortData($dataPDRB, 2);

        // get current time 
        $currentDateTime = date("Y-m-d H_i_s"); // Format "2023-09-30 14_37_31"

        // Konfigurasi untuk generate excel
        require_once ROOTPATH . 'vendor/autoload.php';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $dataSheet = [];
        // header table 
        $columnHeader = ['Komponen', $periode];

        array_push($dataSheet, $columnHeader); //memasukkan data columnheader ke dalam sheet 

        // menambahkan putaran sebagai header kolom 
        $columnHeader2 = [];
        foreach ($putaranArr as $col) {
            $namaCol = "Putaran " . $col;
            array_push($columnHeader2, $namaCol);
        }
        array_push($dataSheet, $columnHeader2);

        // isi tabel 
        $komponenData = [];
        $dataTemp = [];
        $temp = -1;
        foreach ($komponen as $rows) {
            for ($col = 0; $col < sizeof($columnHeader2) + 1; $col++) {
                if ($col == 0) {
                    if ($rows['id_komponen'] == 1 || $rows['id_komponen'] == 2 || $rows['id_komponen'] == 3 || $rows['id_komponen'] == 4 || $rows['id_komponen'] == 5 || $rows['id_komponen'] == 6 || $rows['id_komponen'] == 7 || $rows['id_komponen'] == 8) {
                        $komponen = $rows['id_komponen'] . ". " . $rows['komponen'];
                    } elseif ($rows['id_komponen'] == 9) {
                        $komponen = $rows['komponen'];
                    } else {
                        $komponen = "     " . $rows['id_komponen'] . ". " . $rows['komponen'];
                    };
                    array_push($dataTemp, $komponen);
                } else if ($col != 0) {
                    $temp++;
                    array_push($dataTemp, $dataPDRB[$temp]->nilai);
                }
            }
            array_push($komponenData, $dataTemp);
            $dataTemp = [];
        }
        array_push($dataSheet, $komponenData);

        // masukin data ke excel dan masukin header tabel
        $sheet->fromArray([$title]);
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // pengaturan merge cell kolom 1
        $sheet->mergeCells('A3:A4');    // merge cell header kolom 1
        // merge cell header periode
        $jumlahSelGabung  = count($putaranArr);
        $endColumn = chr(65 + $jumlahSelGabung) . "3";
        $sheet->mergeCells('B3:' . $endColumn);

        // masukin data ke sheet 
        $sheet->fromArray($dataSheet[0], null, 'A3');
        $sheet->fromArray($dataSheet[1], null, 'B4');
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

        // table border`
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ]
            ]
        ];

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
