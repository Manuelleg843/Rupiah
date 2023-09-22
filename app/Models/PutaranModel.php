<?php

namespace App\Models;

use CodeIgniter\Model;

class PutaranModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'putaran';
    protected $primaryKey       = 'id_putaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
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

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    // get periode 
    public function getPeriode()
    {
        $builder = $this->db->table('putaran')
            ->select('periode')
            ->groupBy('periode')
            ->where('periode', '2023Q1');
        return $builder->get()->getResultArray();
    }

    public function getWilayah()
    {
        $builder = $this->db->table('putaran')
            ->select('id_wilayah')
            ->groupBy('id_wilayah')
            ->where('id_wilayah', '3100');
        return $builder->get()->getResultArray();
    }

    public function getJenisPDRB()
    {
        $builder = $this->db->table('putaran')
            ->select('id_pdrb')
            ->distinct()
            ->groupBy('id_pdrb');
        $dataJenis = $builder->get()->getResult();
        $jenisPDRB = [];

        // dd($dataJenis);
        foreach ($dataJenis as $key => $value) {
            // dd($value);
            $jenisPDRB[] = $value;
        }
        // return $builder->get()->getResultArray();\
        return $jenisPDRB;
    }

    public function getByPDRB($jenisPDRB)
    {
        $builder = $this->db->table('putaran')
            ->select()
            ->orderBy('id_komponen');
        if (isset($jenisPDRB)) {
            $builder->where('id_pdrb', $jenisPDRB);
        };
        return $builder->get()->getResultArray();
    }

    public function getPutaran()
    {
        $builder = $this->db->table('putaran')
            ->select('putaran')
            ->groupBy('putaran');
        return $builder->get()->getResultArray();
    }

    // get jenis komponen
    public function getJenisKomponen()
    {
        $builder = $this->db->table('putaran')
            ->distinct('putaran.id_komponen,komponen')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->orderBy('putaran.id_komponen');
        return $builder->get()->getResultArray();
    }


    // get data dari tabel
    public function get_data()
    {
        // $where = "periode='2023Q1' OR periode='2023Q2'";
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
            ->where('id_wilayah', '3100')
            ->where('id_pdrb', '1')
            ->where('periode', '2023Q1')
            ->where('putaran', '1')
            ->orderBy('id_komponen');
        return $builder->get()->getResult();
    }

    public function get_data_2023Q1()
    {

        $builder = $this->db->table('putaran')
            ->select('nilai,id_komponen')
            ->where('id_pdrb', '1')
            ->where('id_wilayah', '3100')
            ->where('periode', '2023Q1')
            ->orderBy('id_komponen');

        return $builder->get()->getResult();
    }


    public function get_pdrb()
    {
        $request = service('request');
        $postData = $request->getPost();
        $dtpostData = $postData['data'];

        $response = array();

        // read value
        $draw = $dtpostData['draw'];
        $start = $dtpostData['start'];
        $columnIndex = $dtpostData['oder'][0]['column'];
        $columnName = $dtpostData['columns'][$columnIndex]['data'];

        // custom filter
        $searchJenisPDRB = $dtpostData['jenisPDRB'];

        $builder = $this->db->table('putaran')
            ->select();
        if ($searchJenisPDRB != "") {
            $builder = $builder->where('id_pdrb', '$searchJenisPBDRB');
        }

        // fetch records
        $searchQuery = $this->db->table('putaran')->select('*');
        if ($searchJenisPDRB != '') {
            $searchQuery->where('id_pdrb', $searchJenisPDRB);
        }
        // $records = $searchQuery->findAll($start);
        $records = $searchQuery->get();

        // dd($records);
        $data = array();
        // return $builder->get()->getResult();
    }

    public function getPutaranTerakhir()
    {
        // get current year 
        $currentYear = date("Y");
        $builder = $this->db->query('SELECT DISTINCT putaran FROM Putaran where tahun=' . $currentYear);
        return $builder->getResultArray();
    }

    public function getByFilter($jenisPDRB)
    {
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
            ->where('id_wilayah', '3100')
            ->where('id_pdrb', $jenisPDRB)
            ->where('periode', '2023Q1')
            ->where('putaran', '1')
            ->orderBy('id_komponen');
        return $builder->get()->getResult();
    }
}
