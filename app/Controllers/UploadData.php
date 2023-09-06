<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class UploadData extends BaseController
{
    public function index($content)
    {
        $data = [
            'title' => "Rupiah | $content"
        ];

        return view('uploadData/' . $content, $data);
    }

    public function intan($tabelRingkasan)
    {
        $content = view('uploadData/uploadAngkaPDRB' . $tabelRingkasan);

        $data = [
            'title' => "Rupiah | Tabel Ringkasan",
            'content' => $content,
        ];

        return view('uploadData/uploadAngkaPDRB', $data);
    }
    // public function index($content)
    // {
    //     $data = [
    //         'title' => "Rupiah | $content"
    //     ];

    //     return view('uploadData/' . $content, $data);
    // }

    // public function uploadPDRB($uploadPDRB)
    // {
    //     $content = view('uploadData/uploadAngkaPDRB' . $uploadPDRB);

    //     $data = [
    //         'title' => "Rupiah | Upload Angka PDRB",
    //         'content' => $content,
    //     ];

    //     return view('uploadData/uploadAngkaPDRB', $data);
    // }
}
