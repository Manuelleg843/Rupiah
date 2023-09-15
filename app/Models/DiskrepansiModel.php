<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskrepansiModel extends Model
{
    protected $table            = 'diskrepansi';
    protected $primaryKey       = 'id_diskrepansi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'periode',
        'id_kuartal',
        'id_komponen_gab',
        'id_pdrb',
        'tahun',
        'nilai',
        'is_over'
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

    public function get_data()
    {
        $builder = $this->db->query('SELECT nilai FROM diskrepansi');

        return $builder->getResult();
    }
}
