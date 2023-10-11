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
                                <div class="col-auto form-group" onchange="loadData()">
                                    <select class="form-control" style="width: 100%;" id="selectTablePerkota">
                                        <option value="1">Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                        <option value="2">Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                        <option value="3">Tabel 3.3. Tabel Distribusi Persentase PDRB ADHB</option>
                                        <option value="4">Tabel 3.4. Pertumbuhan PDRB ADHK (Q-TO-Q)</option>
                                        <option value="5">Tabel 3.5. Pertumbuhan PDRB ADHK (Y-ON-Y)</option>
                                        <option value="6">Tabel 3.6. Pertumbuhan PDRB ADHK (C-TO-C)</option>
                                        <option value="7">Tabel 3.7. Indeks Implisit PDRB (Persen)</option>
                                        <option value="8">Tabel 3.8. Pertumbuhan Indeks Implisit (Q-TO-Q)</option>
                                        <option value="9">Tabel 3.9. Pertumbuhan Indeks Implisit (Y-ON-Y)</option>
                                        <option value="10">Tabel 3.10. Sumber Pertumbuhan Ekonomi (Q-TO-Q)</option>
                                        <option value="11">Tabel 3.11. Sumber Pertumbuhan Ekonomi (Y-ON-Y)</option>
                                        <option value="12">Tabel 3.12. Sumber Pertumbuhan Ekonomi (C-TO-C)</option>
                                        <option value="13">Tabel 3.13. Ringkasan Pertumbuhan Ekstrem Provinsi</option>
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
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-periode">
                                        Pilih Periode
                                    </button>
                                </div>
                            </div>
                            <!-- filter end-->

                            <!-- modal periode -->
                            <div class="modal fade" id="modal-periode" tabindex="-1" aria-labelledby="modal-periodeLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-periodeLabel">Pilih Periode</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="" class="p-2" id="selectPeriode" onload="loadData()">
                                            <div class="modal-body">
                                                <div id="checkboxes-container-year" class="checkboxes-periode">
                                                </div>
                                            </div>
                                        </form>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Pilih Periode</button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:checkboxSemua()">Semua Periode</a></li>
                                                    <li><a class="dropdown-item" href="#" id="allQ1">Semua Q1</a></li>
                                                    <li><a class="dropdown-item" href="#" id="allQ2">Semua Q2</a></li>
                                                    <li><a class="dropdown-item" href="#" id="allQ3">Semua Q3</a></li>
                                                    <li><a class="dropdown-item" href="#" id="allQ4">Semua Q4</a></li>
                                                    <li><a class="dropdown-item" href="#" id="onlyYears">Semua Tahunan</a></li>
                                                    <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown">Tahun</a>
                                                        <ul class="dropdown-menu" id="tahunDropdown">
                                                        </ul>
                                                    </li>
                                                    <div class="dropdown-divider"></div>
                                                    <li><a class="dropdown-item" href="javascript:clearCheckbox()" id="hapusPilihan">Hapus Pilihan</a></li>
                                                </ul>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="loadData()" id="simpan-periode">Simpan</button>
                                            </div>
                                        </div>
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
                                            <button class="btn btn-outline-danger" id="export-button">
                                                <i class="fa fa-file-pdf"></i>
                                                <span>Ekspor PDF</span>
                                            </button>
                                            <button id="exportExcel" class="btn btn-outline-success">
                                                <i class="fa fa-file-excel"></i>
                                                <span>Ekspor Excel</span>
                                            </button>
                                            <a href="#" target="_blank" class="btn btn-outline-secondary">
                                                <i class="fa fa-file-excel"></i>
                                                <span>Ekspor Satu Kota</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- export end -->
                                <div class="card-header">
                                    <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                                </div>
                            </div>
                            <!-- /.card-header -->


                            <div id="tabelPerkota" class="table-responsive d-flex text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">

                            </div>
                        </div>

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