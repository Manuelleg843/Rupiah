<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h4 id='putaran'>Tahun <?= $tahun ?>, Kuartal <?= $id_kuartal ?>, Putaran <?= $putaran ?></h4>
                            </div>
                            <div class="row mb-3 d-flex justify-content-end mr-1">
                                <div class="mt-1 mr-auto ml-2">
                                    <a href="<?= base_url('/monitoring') ?>"><i class="nav-icon fas fa-redo"></i></a>
                                    <small>&nbsp;&nbsp;<i>muat ulang</i></small>
                                </div>
                                <ul class="nav nav-pills">
                                    <li class="nav-item ">
                                        <small id="detail_buka" class="mr-2" <?= ($isActive == 1) ? 'hidden' : '' ?>><i>Putaran Belum dibuka</i></small>
                                        <small id="detail_tutup" class="mr-2" <?= ($isActive == 0) ? 'hidden' : '' ?>><i>Sedang Dalam Putaran </i></small>
                                        <button <?= ($isActive == 1) ? 'hidden' : '' ?> id="buttonbuka" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-buka">
                                            Buka Putaran
                                        </button>
                                        <button <?= ($isActive == 0) ? 'hidden' : '' ?> id="buttontutup" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-tutup">
                                            Tutup Putaran
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- /.card-header -->
                            <div style="overflow-x: scroll; position:relative;">
                                <table id="tabel" class="<?= ($isActive == 1) ? 'table table-bordered table-striped' : 'table table-bordered table-secondary' ?>">
                                    <thead id="kepala" class="<?= ($isActive == 1) ? 'text-center table-primary' : 'text-center' ?>">
                                        <tr>
                                            <th>Wilayah</th>
                                            <th>Status</th>
                                            <th>Waktu Upload</th>
                                            <th>Diupload Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($wilayah as $value) {
                                            echo "<tr>";
                                            echo "<td style='font-weight: bold;'>" . $value . "</td>";
                                            echo "<td>";
                                            if (current($status) == 1) {
                                                echo "<button disabled type='button' class='btn btn-block btn-success'>";
                                                echo "Sudah Upload";
                                                echo "</button>";
                                            } else {
                                                echo "<button disabled type='button' class='btn btn-block btn-danger'>";
                                                echo "Belum Upload";
                                                echo "</button>";
                                            }
                                            echo "</td>";
                                            echo "<td>" . current($waktu_upload) . "</td>";
                                            echo "<td>" . current($diupload_oleh) . "</td>";
                                            echo "</tr>";
                                            next($status);
                                            next($waktu_upload);
                                            next($diupload_oleh);
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

        <!-- /.modal Sukses Buka Button-->
        <div class="modal fade" id="modal-buka">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- <div class="modal-header">

                    </div> -->
                    <div class="modal-body mt-2">
                        <p>Apakah anda yakin untuk membuka putaran?</p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Tidak
                        </button>
                        <a href="<?= base_url('/monitoring/updateStatus') ?>" onclick="hrefModal(event);" type="button" class="btn btn-success swalDefaultBuka" data-dismiss="modal">
                            Yakin
                        </a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal Sukses Tutup Button-->
        <div class="modal fade" id="modal-tutup">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body mt-2">
                        <p>Apakah anda yakin untuk menutup putaran?</p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Tidak
                        </button>
                        <a href="<?= base_url('/monitoring/updateStatus') ?>" onclick="hrefModal(event);" type="button" class="btn btn-success swalDefaultTutup" data-dismiss="modal">
                            Yakin
                        </a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->