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
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <!-- filter-->
                            <div class="row">
                                <!-- Jenis Tabel -->
                                <div class="col-auto form-group" id="dropdownTableArahRevisi">
                                    <select class="form-control" style="width: 100%;" id="selectTableArahRevisi">
                                        <!-- <option value="Pilih Jenis Tabel" hidden>Pilih Jenis Tabel</option> -->
                                        <option id="1" value="PDRB-ADHB" selected>Tabel 301. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                        <option id="2" value="PDRB-ADHK">Tabel 302. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                        <option id="3" value="Pertumbuhan-Y-ON-Y">Tabel 303. Pertumbuhan PDRB (Y-ON-Y)</option>
                                        <option id="4" value="Pertumbuhan-Q-TO-Q">Tabel 304. Pertumbuhan PDRB (Q-TO-Q)</option>
                                        <option id="5" value="Pertumbuhan-C-TO-C">Tabel 305. Pertumbuhan PDRB (C-TO-C)</option>
                                        <option id="6" value="indeks-implisit">Tabel 306. Indeks Implisit</option>
                                        <option id="7" value="pertumbuhan-indeks-implisit-Y-ON-Y">Tabel 307. Pertumbuhan Indeks Implisit (Y-ON-Y)</option>
                                        <option id="8" value="pertumbuhan-indeks-implisit-Q-TO-Q">Tabel 308. Pertumbuhan Indeks Implisit (Q-TO-Q)</option>
                                        <option id="9" value="sumber-pertumbuhan-Y-ON-Y">Tabel 309. Sumber Pertumbuhan (Y-ON-Y)</option>
                                        <option id="10" value="sumber-pertumbuhan-Q-TO-Q">Tabel 310. Sumber Pertumbuhan (Q-TO-Q)</option>
                                        <option id="11" value="sumber-pertumbuhan-C-TO-C">Tabel 311. Sumber Pertumbuhan (C-TO-C)</option>
                                    </select>
                                </div>
                                <!-- Jenis Prov/Kab/Kot -->
                                <div class="col-auto form-group">
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
                                <!-- Pilih Periode -->
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-periode">
                                        Pilih Periode
                                    </button>
                                </div>
                            </div>
                            <!-- filter end -->

                            <!-- modal periode -->
                            <div class="modal fade" id="modal-periode" tabindex="-1" aria-labelledby="modal-periodeLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <!-- Judul -->
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-periodeLabel">Pilih Periode</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="modal-body">
                                            <form action="" class="p-2" id="periode-checkboxes-container">
                                                <div id="checkboxes-container-current-year-min2kuartal" class="checkboxes-periode">
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Button -->
                                        <div class="modal-footer d-flex justify-content-between">
                                            <!-- Semua/Hapus Pilihan -->
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Pilih Periode</button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="javascript:checkboxSemua()">Semua Periode</a></li>
                                                    <div class="dropdown-divider"></div>
                                                    <li><a class="dropdown-item" href="javascript:clearCheckbox()" id="hapusPilihan">Hapus Pilihan</a></li>
                                                </ul>
                                            </div>
                                            <!-- Batal/Simpan -->
                                            <div>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-success" data-dismiss="modal" id="simpan-periode" onclick="loadData()">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.modal -->

                            <!-- Card Header -->
                            <div class="mt-2" style="border-top: 1px solid #ccc;">
                                <!-- Ekspor Tabel -->
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
                                        </div>
                                    </div>
                                </row>
                                <!-- Judul Tabel -->
                                <div class="card-header">
                                    <div class="row" id="judulTable-container">
                                        <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel -->
                            <div id="arah-revisi-container" class="table-responsive text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">

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