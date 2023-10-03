<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleHasPermissionModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $RoleHasPermissionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->RoleHasPermissionModel = new RoleHasPermissionModel();
    }
    public function index()
    {
        //
        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'User Administrator'
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/administrator');
        echo view('layouts/footer');
    }
    public function viewAdministrator()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $keyword = $this->request->getVar('keyword');

        if ($keyword) {
            $users = $this->userModel->search($keyword);
        } else {
            $users = $this->userModel;
        }

        //
        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'User Administrator',
            'users' => $users->paginate(10, 'users'),
            'pager' => $this->userModel->pager,
        ];

        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/administrator', $data);
        echo view('layouts/footer');
    }

    public function viewRoleAndPermission()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // mengamil permission super admin
        $superAdminPermissions = [];
        foreach ($this->RoleHasPermissionModel->select('id_permission')->where('id_role', '1')->findAll() as $key) {
            array_push($superAdminPermissions, $key['id_permission']);
        }

        // mengambil permission admin
        $adminPermissions = [];
        foreach ($this->RoleHasPermissionModel->select('id_permission')->where('id_role', '2')->findAll() as $key) {
            array_push($adminPermissions, $key['id_permission']);
        }

        // mengambil permission operator
        $operatorPermissions = [];
        foreach ($this->RoleHasPermissionModel->select('id_permission')->where('id_role', '3')->findAll() as $key) {
            array_push($operatorPermissions, $key['id_permission']);
        }

        // mengambil permission viewer
        $viewerPermission = [];
        foreach ($this->RoleHasPermissionModel->select('id_permission')->where('id_role', '4')->findAll() as $key) {
            array_push($viewerPermission, $key['id_permission']);
        }

        $data = [
            'title' => 'Rupiah | Admin',
            'tajuk' => 'Admin',
            'subTajuk' => 'Role and Permission',
            'superAdminPermissions' => $superAdminPermissions,
            'adminPermissions' => $adminPermissions,
            'operatorPermissions' => $operatorPermissions,
            'viewerPermission' => $viewerPermission,
        ];



        echo view('layouts/header', $data);
        echo view('layouts/navbar');
        echo view('layouts/sidebar', $data);
        echo view('/admin/roleAndPermission');
        echo view('layouts/footer');
    }

    public function updateUser($niplama)
    {
        if (!in_array('3', session()->get('permission'))) {
            return redirect()->to('/beranda');
        }

        $userLama = $this->userModel->where('niplama', $niplama)->first();

        $roleBaru = $this->request->getVar('id_role');

        // cek apakah role user sudah di ubah
        if ($userLama['id_role'] == $roleBaru) {
            session()->setFlashdata('pesan', 'Role user belum di ubah, silahkan pilih role user yang berbeda dari sebelumnya.');
            return redirect()->to('/admin/administrator');
        }

        if ($roleBaru == 1) {
            session()->setFlashdata('pesan', 'Role user tidak dapat diubah menjadi Super Admin.');
            return redirect()->to('/admin/administrator');
        } else {

            // mengubah role user
            $userLama['id_role'] = $roleBaru;
            $customQuery = "UPDATE master_pegawai SET id_role = $roleBaru  WHERE niplama = $niplama";

            // cek apakah role user berhasil diubah
            if ($this->userModel->query($customQuery)) {
                session()->setFlashdata('pesan', 'Role user berhasil diubah.');
                return redirect()->to('/admin/administrator');
            } else {
                session()->setFlashdata('pesan', 'Role user gagal diubah.');
                return redirect()->to('/admin/administrator');
            }
        }
    }

    public function updatePermission($id_role)
    {
        if (!in_array('3', session()->get('permission'))) {
            return redirect()->to('/beranda');
        }

        // mengambil permission yang di pilih
        $permissions = [];
        foreach ($this->request->getVar() as $permission) {
            array_push($permissions, $permission);
        }

        // menghapus permission lama
        $customQuery = "DELETE FROM role_has_permission WHERE id_role = $id_role";
        $this->RoleHasPermissionModel->query($customQuery);
        if (!$this->RoleHasPermissionModel->query($customQuery)) {
            session()->setFlashdata('pesan', 'Permission gagal diubah.');
            return redirect()->to('/admin/roleAndPermission');
        }

        // menambah permission baru
        foreach ($permissions as $permission) {
            $customQuery = "INSERT INTO role_has_permission (id_role, id_permission) VALUES ($id_role, $permission)";
            $this->RoleHasPermissionModel->query($customQuery);
        }

        // cek apakah permission berhasil diubah
        session()->setFlashdata('pesan', 'Permission berhasil diubah.');
        return redirect()->to('/admin/roleAndPermission');
    }

    public function deleteUser($id)
    {
        // method untuk menghapus data user

        // cek apakah user memiliki akses untuk menghapus data user
        if (!in_array('3', session()->get('permission'))) {
            return redirect()->to('/beranda');
        }

        $this->userModel->delete($id);
        session()->setFlashdata('pesan', 'User berhasil dihapus.');
        return redirect()->to('/admin/administrator');
    }
}
