<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TabelPDRB extends BaseController
{
    public function index($content)
    {
        $data = [
            'title' => "Rupiah | $content"
        ];

        return view('tabelPDRB/' . $content, $data);
    }

    public function tabelRingkasan($tabelRingkasan)
    {
        $content = view('tabelPDRB/tabelRingkasan' . $tabelRingkasan);

        $data = [
            'title' => "Rupiah | Tabel Ringkasan",
            'content' => $content,
        ];

        return view('tabelPDRB/tabelRingkasan', $data);
    }
    public function tabelPerKota($tabelPerKota)
    {
        $content = view('tabelPDRB/tabelPerKota' . $tabelPerKota);

        $data = [
            'title' => "Rupiah | Tabel Per Kota",
            'content' => $content,
        ];

        return view('tabelPDRB/tabelPerKota', $data);
    }
    public function tabelPutaran($tabelHistoriPutaran)
    {
        $content = view('tabelPDRB/tabelPutaran' . $tabelHistoriPutaran);

        $data = [
            'title' => "Rupiah | Tabel Per Kota",
            'content' => $content,
        ];

        return view('tabelPDRB/tabelPutaran', $data);
    }
}
