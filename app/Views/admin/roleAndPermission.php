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
        <!-- /.container-fluid -->
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
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class=" col">
                    <!-- Default box -->
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <div class="row">
                                <h2 class="card-title mt-3 px-3" style="font-weight: bold;" id="judulTable">List Role</h2>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- modal edit roleAdmin -->
                        <div class="modal fade" id="modal-editRoleAdmin">
                            <div class="modal-dialog modal-komponen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Role</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form dengan checkbox -->
                                        <form action="proses-form.php" method="post">
                                            <label>
                                                <input type="checkbox" name="Nama" value="nama" checked> assign-pegawai
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="email" checked> monitoring-putaran
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" checked> upload-tabel
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" disabled> add-user
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" checked> View
                                            </label><br>
                                            <!-- Tambahkan checkbox lain sesuai dengan field yang Anda inginkan -->

                                            <div class="modal-footer d-flex justify-content-end">
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <input type="submit" class="btn btn-primary" value="Simpan" data-dismiss="modal">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- end modal komponen -->

                        <!-- Modal -->
                        <div id="myPermissionModal" class="modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Permission</h4>
                                        <button type="button" class="closeModal close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="modal-permission-form" action="<?= base_url('/admin/roleAndPermission/'); ?>" method="post">
                                            <div class="ml-4 mb-4 d-flex justify-content-start">
                                                <div id="checkbox-container">
                                                    <!-- Checkbox akan ditambahkan di sini -->
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda yakin?')">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- modal edit roleOperator -->
                        <div class="modal fade" id="modal-editRoleOperator">
                            <div class="modal-dialog modal-komponen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Edit Role</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form dengan checkbox -->
                                        <form action="proses-form.php" method="post">
                                            <label>
                                                <input type="checkbox" name="Nama" value="nama"> assign-pegawai
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="email"> monitoring-putaran
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" checked> upload-tabel
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" disabled> add-user
                                            </label><br>
                                            <label>
                                                <input type="checkbox" name="Nama" value="telepon" checked> View
                                            </label><br>
                                            <!-- Tambahkan checkbox lain sesuai dengan field yang Anda inginkan -->

                                            <div class="modal-footer d-flex justify-content-end">
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <input type="submit" class="btn btn-primary" value="Simpan" data-dismiss="modal">
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- end modal komponen -->

                        <!-- card body -->
                        <div class="card-body table-responsive " style="overflow-y: scroll; height: 400px; position:relative; overflow-x:scroll;">
                            <table id="example1" class="table table-bordered table-hover ">
                                <thead class="text-center table-primary fixedHeader-floating">
                                    <tr>
                                        <th colspan="2" style="width: 40%;">Role</th>
                                        <th>Penjelasan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">Super Admin</small>
                                        </td>
                                        <td>
                                            Role untuk bisa mendapatkan semua hak akses
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $superAdminPermissionJSON = json_encode($superAdminPermissions);
                                                ?>
                                                <button disabled name="id_permission" class="show-permission-modal btn btn-primary btn-sm px-3 mx-1" data-role="1" data-arrayPermission="<?= htmlspecialchars($superAdminPermissionJSON); ?>">Ubah Role</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">Admin</small>
                                        </td>
                                        <td>
                                            Role untuk assign pegawai ke role dan monitoring putaran
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $adminPermissionJSON = json_encode($adminPermissions);
                                                ?>
                                                <button name="id_permission" class="show-permission-modal btn btn-primary btn-sm px-3 mx-1" data-role="2" data-arrayPermission="<?= htmlspecialchars($adminPermissionJSON); ?>">Ubah Role</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">Operator</small>
                                        </td>
                                        <td>
                                            Role untuk meng-upload data PDRB, melihat tabel
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $operatorPermissionJSON = json_encode($operatorPermissions);
                                                ?>
                                                <button name="id_permission" class="show-permission-modal btn btn-primary btn-sm px-3 mx-1" data-role="3" data-arrayPermission="<?= htmlspecialchars($operatorPermissionJSON); ?>">Ubah Role</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">Viewer</small>
                                        </td>
                                        <td>
                                            Role melihat tabel
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <?php
                                                $viewerPermissionJSON = json_encode($viewerPermission);
                                                ?>
                                                <button name="id_permission" class="show-permission-modal btn btn-primary btn-sm px-3 mx-1" data-role="4" data-arrayPermission="<?= htmlspecialchars($viewerPermissionJSON); ?>">Ubah Role</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class=" col">
                    <!-- Default box -->
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <div class="row">
                                <h2 class="card-title mt-3 px-3" style="font-weight: bold;" id="judulTable">List Permission</h2>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- card body -->
                        <div class="card-body table-responsive " style="overflow-y: scroll; height: 400px; position:relative; overflow-x:scroll;">
                            <table id="example1" class="table table-bordered table-hover ">
                                <thead class="text-center table-primary fixedHeader-floating">
                                    <tr>
                                        <th colspan="2" style="width: 40%;">Permission</th>
                                        <th>Penjelasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">assign-pegawai</small>
                                        </td>
                                        <td>
                                            Untuk bisa mengakses menu assign role pegawai
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">monitoring-putaran</small>
                                        </td>
                                        <td>
                                            Untuk bisa mengakses menu monitoring putaran
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">upload-tabel</small>
                                        </td>
                                        <td>
                                            Untuk bisa melakukan upload tabel PDRB
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" colspan="2">
                                            <small class="bg-success text-xs p-1 px-3 mx-1 rounded-pill text-white">View</small>
                                        </td>
                                        <td>
                                            Untuk bisa melihat tabel PDRB
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->