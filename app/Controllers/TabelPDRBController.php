<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskrepansiModel;
use App\Models\Komponen7Model;
use App\Models\PutaranModel;
use App\Models\RevisiModel;
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

    public function __construct()
    {
        $this->nilaiDiskrepansi = new DiskrepansiModel();
        $this->komponen = new Komponen7Model();
        $this->putaran = new PutaranModel();
        $this->revisi = new RevisiModel();
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

        $data = [
            'title' => 'Rupiah | Tabel History Putaran',
            'tajuk' => 'Tabel PDRB',
            'subTajuk' => 'Tabel History Putaran',
            'putaran' => $this->putaran->getPutaranTerakhir(),
        ];

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

    public function exportExcel($tableSelected, $jenisPDRB, $kota, $putaran, $periode)
    {

        $periodeArr = explode(",", $periode);

        $komponen = $this->komponen->get_data();

        // get filter 
        $dataPDRB = $this->putaran->getData($jenisPDRB, $kota, $putaran, $periodeArr);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $putaran == 'null' ?  $title = $tableSelected . " - " . $kota . " - Semua Putaran" :   $title = $tableSelected . " - " . $kota . " - Putaran " . $putaran;

        // header table 
        $sheetData = [];
        $columnHeader = ['Komponen'];
        foreach ($periodeArr as $col) {
            array_push($columnHeader, $col);
        }
        array_push($sheetData, $columnHeader);

        // isi tabel 
        $komponenData = [];
        $nilaiPDRB = [];
        $temp = -1;

        // mengubah tipe data jadi array
        // $dataPDRBarray = array_push($dataPDRBarray, $dataPDRB);
        foreach ($komponen as $rows) {
            for ($col = 0; $col < sizeof($columnHeader); $col++) {
                if ($col == 0) {
                    if ($rows->id_komponen == 1 || $rows->id_komponen == 2 || $rows->id_komponen == 3 || $rows->id_komponen == 4 || $rows->id_komponen == 5 || $rows->id_komponen == 6 || $rows->id_komponen == 7 || $rows->id_komponen == 8) {
                        $komponen = $rows->id_komponen . ". " . $rows->komponen;
                    } elseif ($rows->id_komponen == 9) {
                        $komponen = $rows->komponen;
                    } else {
                        $komponen = "     " . $rows->id_komponen . ". " . $rows->komponen;
                    };
                    array_push($nilaiPDRB, $komponen);
                } else {
                    $temp++;
                    array_push($nilaiPDRB, $dataPDRB[$temp]->nilai);
                }
            }
            array_push($komponenData, $nilaiPDRB);
            $nilaiPDRB = [];
        }
        array_push($sheetData, $komponenData);

        $sheet->fromArray([$title]);
        $sheet->fromArray($sheetData[0], null, 'A3');
        $sheet->fromArray($sheetData[1], null, 'A4');


        // mergeCell dan bold judul tabel
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true);


        // bold dan center table header 
        foreach ($sheet->getColumnIterator() as $column) {
            $row = $column->getColumnIndex() . '3';
            $sheet->getStyle($row)->getFont()->setBold(true);
            $sheet->getStyle($row)->getAlignment()->setHorizontal('center');
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

        // setting column width, border, number format 
        foreach ($sheet->getColumnIterator() as $column) {
            foreach ($sheet->getRowIterator() as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $cell->getStyle()->applyFromArray($styleArray);
                }
                // $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
            $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            $column->getColumnIndex() == 'A' ? " " : $sheet->getStyle($column->getColumnIndex())->getNumberFormat()->setFormatCode('#,##0.00');
        }
        // Simpan sebagai file Excel
        $filename = $title . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

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

        // generate html untuk tabel 
        // $html = ' <div class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">';
        // $html .= '<table id="PDRBTable" class="table table-bordered table-hover">';
        // $html .= '<thead class="text-center table-primary sticky-top"><tr><th colspan="2">Komponen</th><th>2023Q1</th></tr></thead>';
        // $html .= '<tbody>';
        // foreach ($dataPDRB as $row) {
        //     $html .= '<tr>';
        //     if ($row->id_komponen == 9) {
        //         $html .= '<td colspan="2">';
        //         $html .= $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
        //     } else {
        //         if ($row->id_komponen == 1 && $row->id_komponen == 2 && $row->id_komponen == 3 && $row->id_komponen == 4 && $row->id_komponen == 5 && $row->id_komponen == 6 && $row->id_komponen == 7 && $row->id_komponen == 8) {
        //             $html .= '<td colspan="2">' . $row->id_komponen . ". " . $row->komponen . '</td>';
        //         } else {
        //             $html .= '<td colspan="2" class="pl-4">' . $row->id_komponen  . '.  ' . $row->komponen  . '</td>';
        //         }
        //     }

        //     // $html .= '<tr>';
        //     // $html .= '<td colspan="2">';
        //     // $html .= '<td class="text-right">' . $row->id_komponen  . '.   ' . $row->komponen  . '</td>';
        //     $html .= '<td class="text-right">' . number_format($row->nilai, 2, ',', '.') . '</td>';
        //     $html .= '</tr>';
        // }
        // $html .= '</table>';
        // echo view('tabelPDRB/table_view', $data);
        // $html = $this->response->getBody();
        $html = view('tabelPDRB/table_view', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        ob_end_clean();
        $dompdf->stream('data_PDRB.pdf', array('Attachment' => 0));
    }
}
