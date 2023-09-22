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
                                <h4>Putaran 1</h4>
                            </div>
                            <div class="row mb-3 justify-content-end mr-1">
                                <ul class="nav nav-pills">
                                    <li class="nav-item ">
                                        <small id="detail_buka" class="mr-2"><i>Putaran Belum dibuka</i></small>
                                        <small id="detail_tutup" class="mr-2" hidden><i>Sedang Dalam Putaran </i></small>
                                        <button id="buttonbuka" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-buka">
                                            Buka Putaran
                                        </button>
                                        <button hidden id="buttontutup" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-tutup">
                                            Tutup Putaran
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- /.card-header -->
                            <div style="overflow-x: scroll; position:relative;">
                                <table id="tabel" class="table table-bordered table-secondary">
                                    <thead id="kepala" class="text-center">
                                        <tr>
                                            <th>Wilayah</th>
                                            <th>Status</th>
                                            <th>Waktu Upload</th>
                                            <th>Diupload Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: bold;">PROV DKI Jakarta</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-success">
                                                    Sudah Upload
                                                </button>
                                            </td>
                                            <td>2023-08-05 12:35:22</td>
                                            <td>Rayyan 1</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kabupaten Kepulauan Seribu</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-danger">
                                                    Belum Upload
                                                </button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kota Jakarta Pusat</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-danger">
                                                    Belum Upload
                                                </button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kota Jakarta Utara</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-success">
                                                    Sudah Upload
                                                </button>
                                            </td>
                                            <td>2023-08-05 14:24:08</td>
                                            <td>Rayyan 2</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kota Jakarta Barat</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-danger">
                                                    Belum Upload
                                                </button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kota Jakarta Selatan</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-success">
                                                    Sudah Upload
                                                </button>
                                            </td>
                                            <td>2023-08-05 16:55:37</td>
                                            <td>Rayyan 3</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold;">Kota Jakarta Timur</td>
                                            <td>
                                                <button disabled type="button" class="btn btn-block btn-success">
                                                    Sudah Upload
                                                </button>
                                            </td>
                                            <td>2023-08-05 13:44:58</td>
                                            <td>Rayyan 4</td>
                                        </tr>
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
                        <p>Apakah anda yakin untuk memulai putaran&hellip;</p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Tidak
                        </button>
                        <button onclick="membuka();" type="button" class="btn btn-success swalDefaultBuka" data-dismiss="modal">
                            Yakin
                        </button>
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
                        <p>Apakah anda yakin untuk menghentikan putaran&hellip;</p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Tidak
                        </button>
                        <button onclick="menutup();" type="button" class="btn btn-success swalDefaultTutup" data-dismiss="modal">
                            Yakin
                        </button>
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