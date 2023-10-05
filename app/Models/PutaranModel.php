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

    // get all data 
    public function getAll()
    {
        $builder = $this->db->table('putaran')->select();
        return $builder->get()->getResult();
    }
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

    // get data final 
    public function getDataFinal($idPDRB, $kota, $periode)
    {
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResult();
    }

    public function getDataFinalMod($idPDRB, $kota, $periode)
    {
        $putaranMax = $this->getPutaranTerakhirbyWilayah($kota, $periode);
        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode', 'id_wilayah', 'id_pdrb'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->where('periode', $periode)
            ->where('putaran', $putaranMax)
            ->orderBy('periode')
            ->orderBy('id_komponen');

        return $builder->get()->getResultObject();
    }


    public function getDataHistory($idPDRB, $kota, $putaran, $periode)
    {

        $builder = $this->db->table('putaran')
            ->join('komponen_7', 'putaran.id_komponen = komponen_7.id_komponen')
            ->select(['putaran.id_komponen', 'komponen_7.komponen', 'nilai', 'periode'])
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $idPDRB)
            ->whereIn('periode', $periode)
            ->orderBy('id_komponen')
            ->orderBy('periode');

        $putaran == 'null' ? '' : $builder->where('putaran', $putaran);

        return $builder->get()->getResult();
    }

    public function getPutaranTerakhir()
    {
        // get current year 
        $currentYear = date("Y");
        $builder = $this->db->query('SELECT DISTINCT putaran FROM Putaran where tahun=' . $currentYear);
        return $builder->getResultArray();
    }

    public function getPutaranTerakhirbyWilayah($wilayah, $periode)
    {
        $builder =  $this->db->query("SELECT MAX(putaran) AS max_putaran FROM Putaran WHERE periode = '$periode' AND id_wilayah = '$wilayah'");
        $row = $builder->getRow();
        $maxPutaran = $row->max_putaran;
        return $maxPutaran;
    }

    public function getPutaranTerakhirPeriode($periode)
    {
        $builder =  $this->db->query("SELECT MAX(putaran) AS max_putaran FROM Putaran WHERE periode = '$periode'");
        $row = $builder->getRow();
        $maxPutaran = $row->max_putaran;
        return $maxPutaran;
    }

    public function getAllPeriode()
    {
        $builder = $this->db->table('putaran')
            ->select()
            ->distinct('periode')
            ->orderBy('tahun', 'desc');
        return $builder->get()->getResult();
    }

    public function getObject()
    {
        $builder = $this->db->table('putaran')
            ->select();
        return $builder->get()->getResultObject();
    }

    public function getTabel1($periode, $komponen)
    {
        $builder = $this->db->table('putaran')
            ->select()
            ->where('id_pdrb', "1")
            ->whereIn('id_komponen', $komponen)
            ->orderBy('periode', 'ASC')
            ->orderBy('id_komponen', 'ASC')
            ->orderBy('id_wilayah', 'ASC');
        foreach ($periode as $value) {
            $putaran =  $this->getPutaranTerakhirPeriode($value);
            $builder->where('periode', $value)->where('putaran', $putaran);
        }
        return $builder->get()->getResult();
    }
}
