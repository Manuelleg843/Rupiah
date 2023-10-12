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
                <input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            <div class="card">
                                <!-- filter-->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto form-group">
                                            <select class="form-control" style="width: 100%;" id="selectTableHistory">
                                                <option value="Pilih Jenis Tabel" hidden>Pilih Jenis Tabel</option>
                                                <option value="1" selected>Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                                <option value="2">Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                            </select>
                                        </div>
                                        <div class="col-auto form-group" onchange="loadData()">
                                            <select class="form-control" style="width: 100%;" id="selectKota">
                                                <option value="<?= session()->get('id_satker'); ?>" hidden><?= session()->get('satker'); ?></option>
                                                <?php if (session()->get('id_satker') == 3100) { ?>
                                                    <option value="3100">Provinsi DKI Jakarta</option>
                                                    <option value="3101">Kepulauan Seribu</option>
                                                    <option value="3173">Jakarta Pusat</option>
                                                    <option value="3175">Jakarta Utara</option>
                                                    <option value="3174">Jakarta Barat</option>
                                                    <option value="3171">Jakarta Selatan</option>
                                                    <option value="3172">Jakarta Timur</option>
                                                <?php } else { ?>
                                                    <option value="3100">Provinsi DKI Jakarta</option>
                                                    <option value="<?= session()->get('id_satker'); ?>"><?= session()->get('satker'); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-auto form-group">
                                            <select class="form-control" style="width: 100%;" id="selectPeriodeHistory" onchange="loadData()">
                                                <option value="Pilih Putaran" hidden>Pilih Periode</option>
                                                <?php foreach ($periode as $opsi) : ?>
                                                    <option value="<?= $opsi ?>" <?php if ($opsi == end($periode)) echo 'selected'; ?>>
                                                        <?= $opsi ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-auto align-items-center">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#putaranModal">
                                                Pilih Putaran
                                            </button>
                                        </div>
                                    </div>


                                    <!-- filter end -->

                                    <!-- modal putaran -->
                                    <div class="modal fade" id="putaranModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Pilih Putaran</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="p-2" id="selectPutaranHistory" onload="loadData()">
                                                    <div class="modal-body" id="periode-checkboxes-container">
                                                        <div class="row">
                                                            <?php foreach ($putaran as $opsi) : ?>
                                                                <div class="col form-check form-check-inline">
                                                                    <input type="checkbox" class="form-check-input" name="<?= $opsi ?>" id="<?= $opsi ?>" value="<?= $opsi ?>" <?php if ($opsi == end($putaran)) echo "checked" ?>>
                                                                    <label class="form-check-label" for="<?= $opsi ?>"><?= $opsi ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer d-flex justify-content-between">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Pilih Putaran</button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="javascript:checkboxSemua()">Semua Putaran</a></li>
                                                                <div class="dropdown-divider"></div>
                                                                <li><a class="dropdown-item" href="javascript:clearCheckbox()" id="hapusPilihan">Hapus Pilihan</a></li>
                                                            </ul>
                                                        </div>
                                                        <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="button" class="btn btn-success" data-dismiss="modal" id="simpan-periode" onclick="loadData()">Simpan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.modal -->

                                    <!-- card-header -->
                                    <div class="mt-2" style="border-top: 1px solid #ccc;">
                                        <!-- export -->
                                        <div class="row justify-content-end mt-3">
                                            <div class="col-auto">
                                                <div class="btn-group">
                                                    <button id="export-button-pdf" class="btn btn-outline-danger">
                                                        <i class="fa fa-file-pdf"></i>
                                                        <span>Ekspor PDF</span>
                                                    </button>
                                                    <button id="export-button-excel" class="btn btn-outline-success">
                                                        <i class="fa fa-file-excel"></i>
                                                        <span>Ekspor Excel</span>
                                                    </button>
                                                    <a id="export-button-excelAll" class="btn btn-outline-secondary">
                                                        <i class="fa fa-file-excel"></i>
                                                        <span>Ekspor Semua Putaran</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- export end -->
                                        <div class="card-header">
                                            <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                                        </div>
                                    </div>
                                    <!-- /. card-header -->

                                    <!-- /.card-body -->
                                    <div id="PDRBTableContainer" class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">

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

            <script>
                window.addEventListener('load', function() {
                    loadData();
                });
            </script>