<?php

namespace App\Models;

use CodeIgniter\Database\MySQLi\Builder;
use CodeIgniter\Model;

class Komponen7Model extends Model
{
    // protected $DBGroup          = '';
    protected $table            = 'komponen_7';
    protected $primaryKey       = 'id_komponen';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_komponen',
        'komponen',
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
        $builder = $this->db->query('SELECT * FROM komponen_7 ORDER BY id_komponen ASC');

        return $builder->getResult();
    }

    public function getById($komponen)
    {
        $builder = $this->db->table('komponen_7')
            ->select();
        // ->whereIn('id_komponen', $komponen);

        $subKomponen_1 = ['1a', '1b', '1c', '1d', '1d', '1e', '1f', '1g'];
        $subKomponen_4 = ['4a', '4b'];

        foreach ($komponen as $value) {
            if ($value == '1') {
                $komponen = array_merge($komponen, $subKomponen_1);
            }
            if ($value == '4') {
                $komponen = array_merge($komponen, $subKomponen_4);
            }
        }
        $builder->whereIn('id_komponen', $komponen)
            ->orderBy('id_komponen', 'ASC');

        return $builder->get()->getResultArray();
    }
}
