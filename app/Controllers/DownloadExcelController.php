<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\Komponen7Model;
use App\Models\WilayahModel;

class DownloadExcelController extends BaseController
{
    protected $komponen7Model;
    protected $wilayahModel;

    public function __construct()
    {
        $this->komponen7Model = new Komponen7Model();
        $this->wilayahModel = new WilayahModel();
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
        $wilayahTerpilih = $this->wilayahModel->where('id_wilayah', array_pop($postData))->first()['wilayah'];

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
        $komponen7DataObj = $this->komponen7Model->get_data();
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
}
