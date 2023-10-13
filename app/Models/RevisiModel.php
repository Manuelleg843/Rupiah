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

    public function getDataKomponenPeriode($kota, $jenisPDRB, $jeniskomponen, $periode)
    {
        $builder = $this->table('revisi')
            ->select()
            ->where('id_wilayah', $kota)
            ->where('id_pdrb', $jenisPDRB)
            ->where('id_komponen', $jeniskomponen)
            ->where('periode', $periode);
        return $builder->get()->getResult();
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
