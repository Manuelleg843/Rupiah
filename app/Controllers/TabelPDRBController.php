<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
use App\Models\WilayahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Shared\Trend\Trend;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf as PdfDompdf;
use PhpParser\Node\Stmt\TryCatch;

use function PHPSTORM_META\type;
use function PHPUnit\Framework\countOf;
use function PHPUnit\Framework\isEmpty;

class TabelPDRBController extends BaseController
{
    protected $nilaiDiskrepansi;
    protected $komponen;
    protected $putaran;
    protected $revisi;
    protected $wilayah;

    public function __construct()
    {
        $this->nilaiDiskrepansi = new DiskrepansiModel();
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->revisi = new RevisiModel();
        $this->wilayah = new WilayahModel();
    }

    public function index()
    {
        // //
        // $data = [
        //     'title' => 'Rupiah | Tabel Ringkasan',
        //     'tajuk' => 'tabelPDRB',
        //     'subTajuk' => 'tabelRingkasan'
        // ];

        // echo view('layouts/header', $data);
        // echo view('layouts/navbar');
        // echo view('layouts/sidebar', $data);
        // echo view('tabelPDRB/diskrepansi-ADHB');
        // echo view('layouts/footer');
    }

    public function viewTabelRingkasan()
    {
        //
        $data = [
            'title' => 'Rupiah | Tabel Ringkasan',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel Ringkasan PDRB Kab/Kota',
            // 'nilaiDiskrepansi' => $this->nilaiDiskrepansi->get_data(),
            // 'komponen' => $this->putaran->get_data(),
            // 'adhb'  => $this->putaran->get_data(),
            // 'adhk'  => $this->putaran->get_data(),
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
        foreach ($periodes as $periode) {
            if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                $dataPDRB[] = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);
            } else {
                if ($this->putaran->getDataFinal($jenisPDRB, $kota, $periode)) {
                    $dataPDRB[] = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);
                }
                $dataPDRB[] = [];
            }
        }

        return $dataPDRB;
    }

    private function countTable3($periodes, $jenisPDRB, $kota)
    {
        $dataPDRBDistribusiPersentase = [];
        foreach ($periodes as $periode) {
            // Tempat data disimpan
            $dataPDRBDistribusiPersentasePeriodei = [];

            // pengecekan apakah data sudah final atau belum
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

                // pengecekan apakah data sudah final atau belum
                if ($this->revisi->getDataFinal($jenisPDRB, $kota, $periode)) {
                    // mengambil data final (mengambil dari tabel revisi)
                    $dataPDRB = $this->revisi->getDataFinal($jenisPDRB, $kota, $periode);
                    $dataPDRBBefore = $this->revisi->getDataFinal($jenisPDRB, $kota, $periodeSebelumnya);

                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
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


                    // looping untuk menghitung persentase
                    foreach ($dataPDRB as $index => $komponen) {
                        $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                        array_push($dataPertumbuhanPDRBDADHKi, $komponen);
                    }

                    // memasukkan data ke array
                    array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
                }
            } else {
                // memasukkan array kosong ke array
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

                // looping untuk menghitung persentase
                foreach ($dataPDRB as $index => $komponen) {
                    $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                    array_push($dataPertumbuhanPDRBDADHKi, $komponen);
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


                // looping untuk menghitung persentase
                foreach ($dataPDRB as $index => $komponen) {
                    $komponen->nilai = ($komponen->nilai - $dataPDRBBefore[$index]->nilai) * 100 / abs($dataPDRBBefore[$index]->nilai);
                    array_push($dataPertumbuhanPDRBDADHKi, $komponen);
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

                // memasukkan data ke array
                array_push($dataPertumbuhanPDRBDADHK, $dataPertumbuhanPDRBDADHKi);
            } else {
                // mengambil data final (mengambil dari tabel revisi)
                $dataPDRB = $this->putaran->getDataFinal($jenisPDRB, $kota, $periode);

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

    public function getDataTabelPerKota()
    {
        $jenisPDRB = $this->request->getPost('jenisPDRB');
        $kota = $this->request->getPost('kota');
        $periodes = $this->request->getPost('selectedPeriode');
        sort($periodes);


        switch ($jenisPDRB) {
            case '1':
                $dataPDRB = $this->countTable2($periodes, '1', $kota);
                break;
            case '2':
                $dataPDRB = $this->countTable2($periodes, '2', $kota);
                break;
            case '3':
                $dataPDRB = $this->countTable3($periodes, '1', $kota);
                break;
            case '4':
                $dataPDRB = $this->countTable4($periodes, '2', $kota);
                break;
            case '5':
                $dataPDRB = $this->countTable5($periodes, '2', $kota);
                break;
            case '6':
                $dataPDRB = $this->countTable6($periodes, '2', $kota);
                break;
            case '7':
                $dataPDRB = $this->countTable7($periodes, $kota);
                break;
            case '8':
                $dataPDRB = $this->countTable8($periodes, $kota);
                break;
            case '9':
                $dataPDRB = $this->countTable9($periodes, $kota);
                break;
            case '10':
                $dataPDRB = $this->countTable10($periodes, $kota);
                break;
            case '11':
                $dataPDRB = $this->countTable11($periodes, $kota);
                break;
            case '12':
                # code...
                $dataPDRB = $this->countTable12($periodes, $kota);
                break;
            case '13':
                # code...
                $dataPDRB = $this->countTable13($periodes, $kota);
                break;
            default:
                # code...
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
            $dataObj = $this->putaran->getDataHistory($jenisPDRB, $kota, $p, $periode);
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

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('tabelPDRB/tabelHistoryPutaran', $data);
        echo view('layouts/footer');
    }

    public function getAllPeriode()
    {
        $allPeriode = $this->putaran->getAllPeriode();

        return $allPeriode;
    }

    // public function exportExcel($tableSelected, $jenisPDRB, $kota, $putaran, $periode)
    // {

    //     $periodeArr = explode(",", $periode);

    //     $komponen = $this->komponen->get_data();

    //     // get filter 
    //     $dataPDRB = $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr);


    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $putaran == 'null' ?  $title = $tableSelected . " - " . $kota . " - Semua Putaran" :   $title = $tableSelected . " - " . $kota . " - Putaran " . $putaran;

    //     // header table 
    //     $sheetData = [];
    //     $columnHeader = ['Komponen'];
    //     foreach ($periodeArr as $col) {
    //         array_push($columnHeader, $col);
    //     }
    //     array_push($sheetData, $columnHeader);

    //     // isi tabel 
    //     $komponenData = [];
    //     $nilaiPDRB = [];
    //     $temp = -1;

    //     // mengubah tipe data jadi array
    //     // $dataPDRBarray = array_push($dataPDRBarray, $dataPDRB);
    //     foreach ($komponen as $rows) {
    //         for ($col = 0; $col < sizeof($columnHeader); $col++) {
    //             if ($col == 0) {
    //                 if ($rows->id_komponen == 1 || $rows->id_komponen == 2 || $rows->id_komponen == 3 || $rows->id_komponen == 4 || $rows->id_komponen == 5 || $rows->id_komponen == 6 || $rows->id_komponen == 7 || $rows->id_komponen == 8) {
    //                     $komponen = $rows->id_komponen . ". " . $rows->komponen;
    //                 } elseif ($rows->id_komponen == 9) {
    //                     $komponen = $rows->komponen;
    //                 } else {
    //                     $komponen = "     " . $rows->id_komponen . ". " . $rows->komponen;
    //                 };
    //                 array_push($nilaiPDRB, $komponen);
    //             } else {
    //                 $temp++;
    //                 array_push($nilaiPDRB, $dataPDRB[$temp]->nilai);
    //             }
    //         }
    //         array_push($komponenData, $nilaiPDRB);
    //         $nilaiPDRB = [];
    //     }
    //     array_push($sheetData, $komponenData);

    //     $sheet->fromArray([$title]);
    //     $sheet->fromArray($sheetData[0], null, 'A3');
    //     $sheet->fromArray($sheetData[1], null, 'A4');


    //     // mergeCell dan bold judul tabel
    //     $sheet->mergeCells('A1:C1');
    //     $sheet->getStyle('A1')->getFont()->setBold(true);


    //     // bold dan center table header 
    //     foreach ($sheet->getColumnIterator() as $column) {
    //         $row = $column->getColumnIndex() . '3';
    //         $sheet->getStyle($row)->getFont()->setBold(true);
    //         $sheet->getStyle($row)->getAlignment()->setHorizontal('center');
    //     }

    //     // table border`
    //     $styleArray = [
    //         'borders' => [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['argb' => 'FF000000'],
    //             ]
    //         ]
    //     ];

    //     // setting column width, border, number format 
    //     foreach ($sheet->getColumnIterator() as $column) {
    //         foreach ($sheet->getRowIterator() as $row) {
    //             foreach ($row->getCellIterator() as $cell) {
    //                 $cell->getStyle()->applyFromArray($styleArray);
    //             }
    //             // $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    //         }
    //         $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    //         $column->getColumnIndex() == 'A' ? " " : $sheet->getStyle($column->getColumnIndex())->getNumberFormat()->setFormatCode('#,##0.00');
    //     }
    //     // Simpan sebagai file Excel
    //     $filename = $title . '.xlsx';
    //     $writer = new Xlsx($spreadsheet);
    //     header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="' . $filename . '"');

    //     $writer->save('php://output');
    //     exit();
    // }

    // public function generateTabelExcel($jenisPDRB, $kota, $putaran, $periode, $nama, $all = false)
    // {
    //     $dataSheet = [];

    //     // header tabel 
    //     $columnHeader = ['Komponen'];
    //     $columnHeader2 = [];
    //     foreach($periode )
    // }

    public function exportExcelHistory($jenisPDRB, $kota, $putaran, $periode, $nama, $all = false)
    {

        // ubah putaran dari string jadi array 
        $putaranArr = explode(",", $putaran);
        // $putaranArr = ['1', '2'];
        sort($putaranArr);

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
            for ($col = 0; $col < sizeof($columnHeader) + 1; $col++) {
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

    public function exportPDF($tableSelected, $jenisPDRB, $kota, $putaran, $periode)
    {

        $periodeArr = explode(",", $periode);

        $komponen = $this->komponen->get_data();
        // get filter 
        $dataPDRB = $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr);

        // create dompdf object 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        // Load konten HTML ke Dompdf
        $data = [
            'subTajuk' => 'Tabel History Putaran',
            'dataPDRB' => $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr),
            'komponen' => $this->komponen->get_data(),
            'selectedPeriode' => $periode
        ];

        $html = view('tabelPDRB/table_view', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ob_end_clean();
        $dompdf->stream('data_PDRB.pdf', array('Attachment' => 0));
    }
}
