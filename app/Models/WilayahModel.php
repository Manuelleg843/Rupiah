<?php

namespace App\Models;

use CodeIgniter\Model;

class WilayahModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'wilayah';
    protected $primaryKey       = 'id_wilayah';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_wilayah', 'wilayah'];

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

    public function getAll()
    {
        $builder = $this->db->table('wilayah')->select()->orderBy('id_wilayah', 'ASC');

        return $builder->get()->getResultArray();
    }

    // get semua ID kota, kecuali wilayah tertentu
    public function getAllIDKota($wilayah = false)
    {
        $builder = $this->db->table('wilayah')
            ->select('id_wilayah');
        $wilayah ? $builder->where('id_wilayah !=', $wilayah) : "";
        return $builder->get()->getResultObject();
    }
}
