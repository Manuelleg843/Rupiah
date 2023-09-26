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

    // get jenis pdrb
    public function getJenisPDRB($jenisPDRB)
    {
        $query = $this->db->table('jenis_pdrb')
            ->where('id_pdrb', $jenisPDRB);
        return $query->get()->getResult();
    }

    // get wilayah
    public function getWilayah()
    {
        $query = $this->db->table('wilayah')
            ->orderBy('id_wilayah');
        return $query->get()->getResult();
    }

    public function getData($jenisPDRB, $kota)
    {
        $builder = $this->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('periode', '2023Q1')
            ->where('putaran', '1')
            ->orderBy('id_komponen');

        return $builder->get()->getResult();
    }


    // public function getData()
    // {
    //     $builder = $this->table('putaran')
    //         ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
    //         ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
    //         ->where('id_wilayah', '3100')
    //         ->where('id_pdrb', "1")
    //         ->where('periode', '2023Q1')
    //         ->where('putaran', '1')
    //         ->orderBy('id_komponen');

    //     return $builder->get()->getResult();
    // }

    public function getByPDRB($idPDRB, $kota)
    {
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['periode', 'putaran.id_komponen', 'komponen_7.komponen', 'id_wilayah', 'id_pdrb', 'tahun', 'putaran', 'nilai', 'periode'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', '2023Q1')
            ->where('putaran', '1')
            ->orderBy('id_komponen');
        return $builder->get()->getResult();
    }

    public function getPutaranTerakhir()
    {
        // get current year 
        $currentYear = date("Y");
        $builder = $this->db->query('SELECT DISTINCT putaran FROM Putaran where tahun=' . $currentYear);
        return $builder->getResultArray();
    }
}
