<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleHasPermissionModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'role_has_permission';
    protected $primaryKey       = 'id_role_permission';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['id_role_permission', 'role_id', 'permission_id'];
}
