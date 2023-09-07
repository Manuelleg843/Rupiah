<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $subTajuk ?></h1>
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
                        <div class="card-header">
                            <div class="row mt-2">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;" id="selectTable">
                                            <option value="Pilih Jenis Tabel" hidden>Pilih Jenis Tabel</option>
                                            <option value="Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)">Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                            <option value="Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)">Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                            <option value="Tabel 3.3. Pertumbuhan PDRB (Q-TO-Q)">Tabel 3.3. Pertumbuhan PDRB (Q-TO-Q)</option>
                                            <option value="Tabel 3.4. Pertumbuhan PDRB (Y-ON-Y)">Tabel 3.4. Pertumbuhan PDRB (Y-ON-Y)</option>
                                            <option value="Tabel 3.5. Pertumbuhan PDRB (C-TO-C)">Tabel 3.5. Pertumbuhan PDRB (C-TO-C)</option>
                                            <option value="Tabel 3.6. Indeks Implisit">Tabel 3.6. Indeks Implisit</option>
                                            <option value="Tabel 3.7. Pertumbuhan Indeks Implisit (Y-ON-Y)">Tabel 3.7. Pertumbuhan Indeks Implisit (Y-ON-Y)</option>
                                            <option value="Tabel 3.8. Sumber Pertumbuhn (Q-TO-Q)">Tabel 3.8. Sumber Pertumbuhn (Q-TO-Q)</option>
                                            <option value="Tabel 3.9. Sumber Pertumbuhan (Y-ON-Y)">Tabel 3.9. Sumber Pertumbuhan (Y-ON-Y)</option>
                                            <option value="Tabel 3.10. Sumber Pertumbuhan (C-TO-C)">Tabel 3.10. Sumber Pertumbuhan (C-TO-C)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;" id="selectKota">
                                            <option value="Pilih Kota" hidden>Pilih Kota</option>
                                            <option value="DKI Jakarta">DKI Jakarta</option>
                                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                                            <option value="Jakarta Utara">Jakarta Utara</option>
                                            <option value="Jakarta Barat">Jakarta Barat</option>
                                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                                            <option value="Jakarta Timur">Jakarta Timur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-komponen">
                                        Pilih Komponen
                                    </button>
                                </div>
                                <div class="col-auto align-items-center">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-periode">
                                        Pilih Periode
                                    </button>
                                </div>

                            </div>
                            <div class="row mb-2 justify-content-between">
                                <!-- switch jenis data -->
                                <div class="col-auto">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <a class="nav-link <?= ($subTajuk == 'Tabel PDRB Per Kota (PKRT 7 Komponen)') ? 'active'  : ''; ?>" href="<?= base_url('/tabelPDRB/tabelPerKota'); ?>">PKRT 7 Komponen</a>
                                        </li>
                                        <li class="nav-item">

                                            <a class="nav-link <?= ($subTajuk == 'Tabel PDRB Per Kota (PKRT 12 Komponen)') ? 'active'  : ''; ?>" href="<?= base_url('/tabelPDRB/tabelPerKota_12Komponen'); ?>">PKRT 12 Komponen baru</a>
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
                                    <button type="submit" class="btn btn-outline-success">
                                        <i class="fa fa-file-excel"></i>
                                        <span>Ekspor Satu Kota</span>
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
                                            <label class="form-check-label" for="1">1. Pengeluaran Konsumsi Rumah (1.a. s/d 1.l.)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="2" value="2">
                                            <label class="form-check-label" for="2">2. Pengeluaran Konsumsi LNPRT</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="3" value="3">
                                            <label class="form-check-label" for="3">3. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="4" value="4">
                                            <label class="form-check-label" for="4">4. Pembentukan Modal Tetap Bruto (4.a. + 4.b.)</label>
                                        </div>
                                        <!-- 2011 -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="5" value="5">
                                            <label class="form-check-label" for="5">5. Perubahan Inventori</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="6" value="6">
                                            <label class="form-check-label" for="6">6. Ekspor Luar Negeri (6.a. + 6.b.)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="7" value="7">
                                            <label class="form-check-label" for="7">7. Impor Luar Negeri (7.a. + 7.b.)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="8" value="8">
                                            <label class="form-check-label" for="8">8. Net Ekspor Antar Daerah (8.a. - 8.b.)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="9" val9e="0">
                                            <label class="form-check-label" for="9">9. PDRB</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <div class="row justify-content-md-end">
                                            <div class="col-auto">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                        Pilihan
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li class="dropdown-item"><a href="#">Pilih Semua</a></li>
                                                        <li class="dropdown-item"><a href="#">Hapus Pilihan</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <button type=" button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- end modal komponen -->
                        <!-- modal periode -->
                        <div class="modal fade" id="modal-periode">
                            <div class="modal-dialog modal-periode">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Periode</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- 2010 -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2010Q1" value="2010Q1">
                                            <label class="form-check-label" for="2010Q1">2010Q1</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2010Q2" value="2010Q2">
                                            <label class="form-check-label" for="2010Q2">2010Q2</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2010Q3" value="2010Q3">
                                            <label class="form-check-label" for="2010Q3">2010Q3</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2010Q4" value="2010Q4">
                                            <label class="form-check-label" for="2010Q4">2010Q4</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2010" value="2010">
                                            <label class="form-check-label" for="2010">2010</label>
                                        </div>
                                        <!-- 2011 -->
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2011Q1" value="2011Q1">
                                            <label class="form-check-label" for="2011Q1">2011Q1</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2011Q2" value="2011Q2">
                                            <label class="form-check-label" for="2011Q2">2011Q2</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2011Q3" value="2011Q3">
                                            <label class="form-check-label" for="2011Q3">2011Q3</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2011Q4" value="2011Q4">
                                            <label class="form-check-label" for="2011Q4">2011Q4</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="2011" value="2011">
                                            <label class="form-check-label" for="2011">2011</label>
                                        </div>

                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                                        <div class="row justify-content-md-end">
                                            <div class="col-auto align-items-center">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                                        Pilihan
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li class="dropdown-item"><a href="#">Pilih Semua</a></li>
                                                        <li class="dropdown-item"><a href="#">Semua Q1</a></li>
                                                        <li class="dropdown-item"><a href="#">Semua Q2</a></li>
                                                        <li class="dropdown-item"><a href="#">Semua Q3</a></li>
                                                        <li class="dropdown-item"><a href="#">Semua Q4</a></li>
                                                        <li class="dropdown-divider"></li>
                                                        <li class="dropdown-item"><a href="#">Hapus Pilihan</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-auto align-items-center">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batalkan</button>
                                            </div>
                                            <div class="col-auto align-items-center">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
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
                        <div class="card-body table-responsive text-nowrap" style="overflow-y: scroll; height: 400px; position:relative;">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead class="text-center table-primary fixedHeader-floating">
                                    <tr>
                                        <th colspan="2">Komponen</th>
                                        <th>2023Q1</th>
                                        <th>2023Q2</th>
                                        <th>2023Q3</th>
                                        <th>2023Q4</th>
                                        <th>2023</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Rumah Tangga (1.a. s/d 1.l.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">2. Pengeluaran Konsumsi LNPRT</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">4. Pembentukan Modal Tetap Bruto (4.a. + 4.b.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">5. Perubahan Inventori</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">6. Ekspor Luar Negeri (6.a. + 6.b.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">7. Impor Luar Negeri (7.a. + 7.b.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">8. Net Ekspor Antar Daerah (8.a. - 8.b.)</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="font-weight: bold;">PDRB</th>
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