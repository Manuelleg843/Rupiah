<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PutaranSeeder extends Seeder
{
    public function run()
    {

        // lokasi file csv
        $csvFile = WRITEPATH . 'putaran_dummy.csv'; //belum dicustom path nya 

        // read file csv 
        $csv = array_map('str_getcsv', file($csvFile));

        // table name 
        $table = 'putaran';

        // table column
        $fields = [
            'periode',
            'id_kuartal',
            'id_komponen',
            'id_wilayah',
            'id_pdrb',
            'tahun',
            'putaran',
            'nilai',
            'uploaded_by',
        ];

        // insert data ke table
        foreach ($csv as $row) {
            $data = array_combine($fields, $row);
            $this->db->table($table)->insert($data);
        };

        // // unique column untuk mengidentifikasi existed data
        // $searchColumns = ['periode', 'id_komponen_gab', 'id_wilayah', 'id_pdrb', 'putaran'];

        // // connect db
        // $builder = $this->db->table($table);

        // // loop through rows in CSV
        // foreach ($csv as $row) {

        //     // buat WHERE conditions
        //     $where = [];
        //     foreach ($searchColumns as $col) {
        //         $where[$col] = $row[$col];
        //     }

        //     // cek if data already in table 
        //     $existingData = $builder
        //         ->where($where)->get()->getRow();

        //     // if data exists, update data
        //     if ($existingData) {
        //         $data = array_combine($fields, $row);
        //         $builder->update($data, $where);
        //         // $dataToUpdate = [
        //         //     'nilai' => $row['nilai'],
        //         //     'uploaded_at' => $row['uploaded_at'],
        //         //     'uploaded_by' => $row['uploaded_by'],
        //         // ];

        //         // $builder->update($dataToUpdate, $where);
        //     } else {
        //         // if data doesnt exist, insert data 
        //         $data = array_combine($fields, $row);
        //         $this->db->table($table)->insert($data);
        //     }

        //     // $data = array_combine($fields, $row);
        //     // $this->db->table($table)->insert($data);
        // };
    }
}
