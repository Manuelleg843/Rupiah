<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PutaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'email'    => 'admin@example.com',
        ];

        // Masukkan data ke dalam tabel 'users'
        $this->db->table('users')->insert($data);
    }
}
