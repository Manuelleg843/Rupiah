<?php

namespace App\Models;

use CodeIgniter\Model;

class RevisiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'revisi';
    protected $primaryKey       = 'id_revisi';
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
        'nilai',
        'uploaded_at',
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

    // get data by periode and wilayah
    public function get_data_revisi_wilayah_periode($kota, $jenisPDRB, $periode) //cara buat otomatis satker
    {
        $builder = $this->db->table('revisi')
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('periode', $periode)
            ->orderBy('revisi.id_komponen');
        return $builder->get()->getResult();
    }

    // get periode 
    public function getPeriode()
    {
        $builder = $this->db->table('revisi')
            ->select('periode')
            ->groupBy('periode')
            ->where('periode', '2023Q1');
        return $builder->get()->getResultArray();
    }

    public function getWilayah()
    {
        $builder = $this->db->table('revisi')
            ->select('id_wilayah')
            ->groupBy('id_wilayah')
            ->where('id_wilayah', '3100');
        return $builder->get()->getResultArray();
    }

    public function getJenisPDRB()
    {
        $builder = $this->db->table('revisi')
            ->select('id_pdrb')
            ->groupBy('id_pdrb');
        return $builder->get()->getResultArray();
    }

    // get jenis komponen
    public function getJenisKomponen()
    {
        $builder = $this->db->table('revisi')
            ->distinct('revisi.id_komponen,komponen')
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->orderBy('revisi.id_komponen');
        return $builder->get()->getResultArray();
    }


    // get data dari tabel
    public function get_data()
    {
        $builder = $this->db->table('revisi')
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'revisi.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'nilai', 'periode'])
            ->where('id_wilayah', '3100')
            ->where('id_pdrb', '1')
            ->where('periode', '2023Q1')
            ->orderBy('id_komponen');
        return $builder->get()->getResult();
    }

    public function get_data_2023Q1()
    {
        $builder = $this->db->table('revisi')
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
        // dd($postData);
        $dtpostData = $postData['data'];

        $response = array();

        // read value
        $draw = $dtpostData['draw'];
        $start = $dtpostData['start'];
        $columnIndex = $dtpostData['oder'][0]['column'];
        $columnName = $dtpostData['columns'][$columnIndex]['data'];

        // custom filter
        $searchJenisPDRB = $dtpostData['jenisPDRB'];

        $builder = $this->db->table('revisi')
            ->select();
        if ($searchJenisPDRB != "") {
            $builder = $builder->where('id_pdrb', '$searchJenisPBDRB');
        }

        // fetch records
        $searchQuery = $this->db->table('revisi')->select('*');
        if ($searchJenisPDRB != '') {
            $searchQuery->where('id_pdrb', $searchJenisPDRB);
        }
        // $records = $searchQuery->findAll($start);
        $records = $searchQuery->get();

        // dd($records);
        $data = array();
        // return $builder->get()->getResult();
    }

    // get data by jenis_pdrb 
    public function getData_by_jenisPdrb($id_pdrb)
    {
        // $query = $this->db->table('putaran')->where('id_pdrb', $id_pdrb)->get();
        $builder = $this->db->query('SELECT nilai FROM revisi WHERE id_pdrb = ' . $id_pdrb);
        return $builder->getResult();
    }

    public function getDataGabungan()
    {
        $builder = $this->db->table('revisi')
            ->select()
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->where('id_pdrb', '1')
            ->where('id_wilayah', '3100');
        return $builder->get()->getResult();
    }

    public function getFilteredData($postData)
    {
        $builder = $this->db->table('revisi');

        // Apply individual column search
        foreach ($postData['columns'] as $column) {
            if (!empty($column['search']['value'])) {
                $builder->orLike($column['data'], $column['search']['value']);
            }
        }

        $totalFiltered = $builder->countAllResults(false);
        $filteredData = $builder->get()->getResultArray();
        $response = [
            "draw" => intval($postData['draw']),
            "recordsTotal" => $this->countAll(),
            "recordsFiltered" => $totalFiltered,
            "data" => $filteredData,
        ];

        return $response;
    }

    // Fungsi mendapatkan data final (revisi)
    public function getDataFinal($idPDRB, $kota, $periode)
    {
        $builder = $this->db->table('revisi')
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->select(['revisi.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResult();
    }

    public function batchUpdate($updateBatchData)
    {
        $builder = $this->db->table('revisi');
        $builder->updateBatch($updateBatchData, ['periode', 'id_wilayah', 'id_pdrb', 'id_komponen']);
    }

    public function getDataFinalMod($idPDRB, $kota, $periode)
    {
        $builder = $this->db->table('revisi')
            ->join('komponen_7', 'revisi.id_komponen = komponen_7.id_komponen')
            ->select(['revisi.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResultObject();
    }
}
