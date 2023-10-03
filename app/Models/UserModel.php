<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'master_pegawai';
    protected $primaryKey       = 'niplama';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields = ['nama', 'email', 'niplama', 'password', 'nipbaru', 'jabatan', 'unitkerja', 'role', 'status', 'created_at', 'updated_at'];



    protected function passwordHash(array $data)
    {
        if (isset($data['data']['email']))
            $data['data']['email'] = $data['data']['email'];

        return $data;
    }

    public function search($keyword)
    {
        $builder = $this->table('master_pegawai');
        $builder->like('nama', $keyword);
        return $builder;
    }
}
