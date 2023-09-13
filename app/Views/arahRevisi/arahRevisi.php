<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $subTajuk ?></h1>
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
                    <!-- Default box -->
                    <div class="card">
                        <!-- filter-->
                        <div class="card-header">
                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;" id="selectTable">
                                            <option value="Pilih Jenis Tabel" hidden>Pilih Jenis Tabel</option>
                                            <option value="Tabel 301. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)">Tabel 301. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                            <option value="Tabel 302. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)">Tabel 302. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                            <option value="Tabel 303. Pertumbuhan PDRB (Q-TO-Q)">Tabel 303. Pertumbuhan PDRB (Q-TO-Q)</option>
                                            <option value="Tabel 304. Pertumbuhan PDRB (Y-ON-Y)">Tabel 304. Pertumbuhan PDRB (Y-ON-Y)</option>
                                            <option value="Tabel 305. Pertumbuhan PDRB (C-TO-C)">Tabel 305. Pertumbuhan PDRB (C-TO-C)</option>
                                            <option value="Tabel 306. Indeks Implisit">Tabel 306. Indeks Implisit</option>
                                            <option value="Tabel 307. Pertumbuhan Indeks Implisit (Y-ON-Y)">Tabel 307. Pertumbuhan Indeks Implisit (Y-ON-Y)</option>
                                            <option value="Tabel 308. Sumber Pertumbuhn (Q-TO-Q)">Tabel 308. Sumber Pertumbuhn (Q-TO-Q)</option>
                                            <option value="Tabel 309. Sumber Pertumbuhan (Y-ON-Y)">Tabel 309. Sumber Pertumbuhan (Y-ON-Y)</option>
                                            <option value="Tabel 310. Sumber Pertumbuhan (C-TO-C)">Tabel 310. Sumber Pertumbuhan (C-TO-C)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;" id="selectKota">
                                            <option value="Pilih Wilayah" hidden>Pilih Wilayah</option>
                                            <option>DKI Jakarta</option>
                                            <option>Jakarta Pusat</option>
                                            <option>Jakarta Utara</option>
                                            <option>Jakarta Barat</option>
                                            <option>Jakarta Selatan</option>
                                            <option>Jakarta Timur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-komponen">
                                        Pilih Komponen
                                    </button>
                                </div>
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                        Pilih Periode
                                    </button>
                                </div>

                            </div>
                            <div class="row mb-2 justify-content-between">
                                <!-- switch jenis data -->
                                <div class="col-auto">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <a class="nav-link <?= ($subTajuk == 'Arah Revisi Kota (PKRT 7 Komponen)') ? 'active'  : ''; ?>" href="<?= base_url('arahRevisi_7Komponen'); ?>">PKRT 7 Komponen</a>
                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link <?= ($subTajuk == 'Arah Revisi Kota (PKRT 12 Komponen)') ? 'active'  : ''; ?>" href="<?= base_url('arahRevisi'); ?>">PKRT 12 Komponen</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- export -->
                                <div class="col-auto align-items-center">
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="fa fa-file-excel"></i>
                                        <span>Ekspor Excel</span>
                                    </button>
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fa fa-file-pdf"></i>
                                        <span>Ekspor PDF</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                        <!-- filter ended -->

                        <!-- modal komponen -->
                        <div class="modal fade" id="modal-komponen">
                            <div class="modal-dialog modal-komponen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Komponen</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="1" value="1">
                                            <label class="form-check-label" for="1">1. Pengeluaran Konsumsi Rumah Tangga</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="2" value="2">
                                            <label class="form-check-label" for="2">2. Pengeluaran Konsumsi LNPRT</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="3" value="3">
                                            <label class="form-check-label" for="3">3. Pengeluaran Konsumsi Pemerintah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="4" value="4">
                                            <label class="form-check-label" for="4">4. Pembentukan Modal Tetap Bruto</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="5" value="5">
                                            <label class="form-check-label" for="5">5. Perubahan Inventori</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="6" value="6">
                                            <label class="form-check-label" for="6">6. Ekspor Luar Negeri</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="7" value="7">
                                            <label class="form-check-label" for="7">7. Impor Luar Negeri</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="8" value="8">
                                            <label class="form-check-label" for="8">8. Net Ekspor Antar Daerah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="8a" value="8a">
                                            <label class="form-check-label" for="8a">8.a. Ekspor Antar Daerah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="8b" value="8b">
                                            <label class="form-check-label" for="8b">8.b. Impor Antar Daerah</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="11" value="11">
                                            <label class="form-check-label" for="11">PDRB</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Pilihan</button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="javascript:checkboxSemua()">Pilih Semua</a></li>
                                                <li><a class="dropdown-item" href="#" id="hapusPilihan">Hapus Pilihan</a></li>
                                            </ul>
                                        </div>
                                        <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- end modal komponen -->

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
                                                <li><a class="dropdown-item" href="#" id="allQ1">Semua Q1</a></li>
                                                <li><a class="dropdown-item" href="#" id="allQ2">Semua Q2</a></li>
                                                <li><a class="dropdown-item" href="#" id="allQ3">Semua Q3</a></li>
                                                <li><a class="dropdown-item" href="#" id="allQ4">Semua Q4</a></li>
                                                <li><a class="dropdown-item" href="#" id="onlyYears">Semua Tahunan</a></li>
                                                <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown">Tahun</a>
                                                    <ul class="dropdown-menu" id="tahunDropdownCurrentYear">
                                                    </ul>
                                                </li>
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
                        <div class="card-header">
                            <div class="row">
                                <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- card body -->
                        <div class="card-body table-responsivetable-responsive text-nowrap" style="overflow-y: scroll; height: 400px; max-height: fit-content; position:relative; overflow-x:auto;">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead class="text-center table-primary">
                                    <tr>
                                        <th colspan="4" rowspan="2">Komponen</th>
                                        <th colspan="3">2023Q1</th>
                                        <th colspan="3">2023Q2</th>
                                        <th colspan="3">2023Q3</th>
                                        <th colspan="3">2023Q4</th>
                                    </tr>
                                    <tr>
                                        <th>Rilis</th>
                                        <th>Revisi</th>
                                        <th>Arah</th>
                                        <th>Rilis</th>
                                        <th>Revisi</th>
                                        <th>Arah</th>
                                        <th>Rilis</th>
                                        <th>Revisi</th>
                                        <th>Arah</th>
                                        <th>Rilis</th>
                                        <th>Revisi</th>
                                        <th>Arah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">1. Pengeluaran Konsumsi Rumah Tangga</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">2. Pengeluaran Konsumsi LNPRT</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">1. Pengeluaran Konsumsi Pemerintah</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">4. Pembentukan Modal Tetap Bruto</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">5. Perubahan Inventori</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">6. Ekspor Luar Negeri</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">7. Impor Luar Negeri</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="font-weight: bold;">8. Net Ekspor Antar Daerah</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" style="font-weight: bold;">PDRB</th>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
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