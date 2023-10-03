<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('pesan'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $tajuk ?></h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- switch jenis data -->
        <div class="col-auto">
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($subTajuk == 'User Administrator') ? 'active'  : ''; ?>" href="<?= base_url('/admin/administrator'); ?>">User Administrator</a>
                </li>
                <li class="nav-item">

                    <a class="nav-link <?= ($subTajuk == 'Role and Permission') ? 'active'  : ''; ?>" href="<?= base_url('/admin/roleAndPermission'); ?>">Role & Permission</a>
                </li>
            </ul>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <!-- filter-->
                        <div class="card-header">
                            <div class="row my-2 mb-2 justify-content-between">
                                <!-- card-header -->
                                <div class="card-header">
                                    <div class="row">
                                        <h2 class="card-title px-1" style="font-weight: bold;" id="judulTable">List User</h2>
                                    </div>
                                </div>
                            </div>

                            <!-- search component -->
                            <div class=" col-lg-6">
                                <div class="form-group">
                                    <form action="" method="post">
                                        <div class="input-group">
                                            <input type="search" class="form-control" name="keyword" placeholder="Cari User...">
                                            <div class="input-group-append">
                                                <button type="submit" name="submit" class="btn btn-default">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- search ended -->
                        </div>
                        <!-- filter ended -->

                        <!-- modal ubah role -->
                        <div id="roleModal" class="modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Ubah Role</h4>
                                        <button type="button" class="close-ubah-role close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="modal-form" action="<?= base_url('/admin/administrator/'); ?>" method="POST">
                                            <div class="form-group">
                                                <label for="dropdownSelect">Pilih Salah Satu:</label>
                                                <select class="form-control" name="id_role" id="dropdown-role"></select>
                                            </div>
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin?')">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end modal ubah role -->

                        <!-- card body -->
                        <div class="card-body table-responsive " style="overflow-y: scroll; height: 400px; position:relative; overflow-x:scroll;">
                            <table id="example1" class="table table-bordered table-hover ">
                                <thead class="text-center table-primary fixedHeader-floating">
                                    <tr>
                                        <th colspan="2" style="width: 40%;">Nama</th>
                                        <th>Satuan Kerja</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php foreach ($users as $user) : ?>
                                        <tr>
                                            <td colspan="2"><?= $user['nama']; ?></td>
                                            <td><?php
                                                if ($user['id_satker'] == 3100) {
                                                    echo "DKI Jakarta";
                                                } elseif ($user['id_satker'] == 3173) {
                                                    echo "Jakarta Pusat";
                                                } elseif ($user['id_satker'] == 3175) {
                                                    echo "Jakarta Utara";
                                                } elseif ($user['id_satker'] == 3174) {
                                                    echo "Jakarta Barat";
                                                } elseif ($user['id_satker'] == 3171) {
                                                    echo "Jakarta Selatan";
                                                } elseif ($user['id_satker'] == 3172) {
                                                    echo "Jakarta Timur";
                                                } elseif ($user['id_satker'] == 3101) {
                                                    echo "Kepulauan Seribu";
                                                } else {
                                                    echo "DKI Jakarta";
                                                }
                                                ?></td>
                                            <td class="text-center">
                                                <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">
                                                    <?php
                                                    if ($user['id_role'] == 1) {
                                                        echo "Super Admin";
                                                    } elseif ($user['id_role'] == 2) {
                                                        echo "Admin";
                                                    } elseif ($user['id_role'] == 3) {
                                                        echo "Operator";
                                                    } else {
                                                        echo "Viewer";
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button name="id_role" class="show-role-modal btn btn-primary btn-sm px-3 mx-1" data-niplama="<?= $user['niplama']; ?>" data-role="<?= $user['id_role']; ?>">Ubah Role</button>
                                                    <form class="d-inline" action="<?= base_url('/admin/administrator'); ?>/<?= $user['niplama']; ?>" method="POST">
                                                        <?= csrf_field(); ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-danger  btn-sm px-3 mx-1" onclick="return confirm('Apakah anda yakin?')">hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?= $pager->links('users', 'users_pagination') ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->