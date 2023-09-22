        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-auto">
                            <h1 class="m-0"><?= $subTajuk ?></h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card">
                                <!-- filter-->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto form-group">
                                            <select class="form-control" style="width: 100%;" id="selectTable">
                                                <option value="Pilih Jenis Tabel" hidden>Pilih Jenis Tabel</option>
                                                <option value="Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)">Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                                <option value="Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)">Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                            </select>
                                        </div>
                                        <div class="col-auto form-group">
                                            <select class="form-control" style="width: 100%;" id="selectKota">
                                                <option value="Pilih Wilayah" hidden>Pilih Wilayah</option>
                                                <option value="Provinsi DKI Jakarta">Provinsi DKI Jakarta</option>
                                                <option value="Kepulauan Seribu">Kepulauan Seribu</option>
                                                <option value="Jakarta Pusat">Jakarta Pusat</option>
                                                <option value="Jakarta Utara">Jakarta Utara</option>
                                                <option value="Jakarta Barat">Jakarta Barat</option>
                                                <option value="Jakarta Selatan">Jakarta Selatan</option>
                                                <option value="Jakarta Timur">Jakarta Timur</option>
                                            </select>
                                        </div>
                                        <div class="col-auto form-group">
                                            <select class="form-control" style="width: 100%;" id="selectPutaran">
                                                <option value="Pilih Putaran" hidden>Pilih Putaran</option>
                                                <option value="Putaran 1">Putaran 1</option>
                                                <option value="Putaran 2">Putaran 2</option>
                                                <option value="Putaran 3">Putaran 3</option>
                                                <option value="Putaran 4">Putaran 4</option>
                                            </select>
                                        </div>
                                        <div class="col-auto align-items-center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                                Pilih Periode
                                            </button>
                                        </div>
                                    </div>

                                    <!-- filter end -->

                                    <!-- modal periode -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Pilih Periode</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="" class="p-2">
                                                        <div id="checkboxes-container-current-year"></div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-between">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Pilih Periode</button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="javascript:checkboxSemua()">Semua Periode</a></li>
                                                            <div class="dropdown-divider"></div>
                                                            <li><a class="dropdown-item" href="javascript:clearCheckbox()" id="hapusPilihan">Hapus Pilihan</a></li>
                                                        </ul>
                                                    </div>
                                                    <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="button" class="btn btn-success" data-dismiss="modal">Simpan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.modal -->

                                    <!-- card-header -->
                                    <div class="mt-2" style="border-top: 1px solid #ccc;">
                                        <!-- export -->
                                        <row class="row justify-content-end mt-3">
                                            <div class="col-auto">
                                                <div class="btn-group">
                                                    <a href="#" target="_blank" class="btn btn-outline-danger">
                                                        <i class="fa fa-file-pdf"></i>
                                                        <span>Ekspor PDF</span>
                                                    </a>
                                                    <a href="#" target="_blank" class="btn btn-outline-success">
                                                        <i class="fa fa-file-excel"></i>
                                                        <span>Ekspor Excel</span>
                                                    </a>
                                                    <a href="#" target="_blank" class="btn btn-outline-secondary">
                                                        <i class="fa fa-file-excel"></i>
                                                        <span>Ekspor Semua Putaran</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </row>
                                        <!-- export end -->
                                        <div class="card-header">
                                            <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                                        </div>
                                    </div>
                                    <!-- /. card-header -->

                                    <!-- /.card-body -->
                                    <div class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead class="text-center table-primary sticky-top">
                                                <tr>
                                                    <th colspan="2">Komponen</th>
                                                    <th>2023Q1</th>
                                                    <th>2023Q2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($komponen7 as $row) :
                                                    $id = $row->id_komponen;
                                                    $komponen = $row->komponen;
                                                ?>
                                                    <tr>
                                                        <td colspan="2" <?php
                                                                        $bold = ($id == 1 || $id == 2 || $id == 3 || $id == 4 || $id == 5 || $id == 6 || $id == 7 || $id == 8 || $id == 9) ? " style='font-weight: bold;'" : "";
                                                                        echo $bold;
                                                                        ?>>
                                                            <?php
                                                            echo $id . ". " . $row->komponen;
                                                            ?>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->