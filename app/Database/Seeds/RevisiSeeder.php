<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RevisiSeeder extends Seeder
{
    public function run()
    {
        // lokasi file csv
        $csvFile = WRITEPATH . 'tabel revisi.csv'; //belum dicustom path nya 

        // read file csv 
        $csv = array_map('str_getcsv', file($csvFile));

        // table name 
        $table = 'revisi';

        // table column
        $fields = [
            'periode',
            'id_kuartal',
            'id_komponen',
            'id_wilayah',
            'id_pdrb',
            'tahun',
            'nilai',
            'uploaded_by',
        ];

        // insert data ke table
        foreach ($csv as $row) {
            $data = array_combine($fields, $row);
            $this->db->table($table)->insert($data);
        };
    }
}
