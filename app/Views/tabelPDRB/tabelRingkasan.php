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
        </section>
        <!-- content header end -->

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
                                            <option value="Tabel 1.11. Perbandingan Diskrepansi Nasional dan Regional Menurut Komponen">Tabel 1.11. Perbandingan Diskrepansi Nasional dan Regional Menurut Komponen</option>
                                            <option value="Tabel 1.12. Perbandingan Diskrepansi Kumulatif Nasional dan Regional Menurut Komponen">Tabel 1.12. Perbandingan Diskrepansi Kumulatif Nasional dan Regional Menurut Komponen</option>
                                            <option value="Tabel 1.13. Ringkasan Pertumbuhan Ekstrem Provinsi">Tabel 1.13. Ringkasan Pertumbuhan Ekstrem Provinsi</option>
                                            <option value="Tabel 1.14. Ringkasan Revisi Pertumbuhan Esktrem dan Balik Arah Provinsi">Tabel 1.14. Ringkasan Revisi Pertumbuhan Esktrem dan Balik Arah Provinsi</option>
                                        </select>
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

                                <!-- filter end -->

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
                                                    <input class="form-check-input" type="checkbox" id="0" value="0">
                                                    <label class="form-check-label" for="0">PDRB</label>
                                                </div>
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
                                    </div>
                                </div>

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
                                            <div class="modal-body">
                                                <form action="" class="p-2">
                                                    <div id="checkboxes-container"></div>
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
                                                        <li><a class="dropdown-item" href="#" id="allYear">Semua Tahunan</a></li>
                                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown">Tahun</a>
                                                            <ul class="dropdown-menu" id="tahunDropdown">
                                                            </ul>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#" id="hapusPilihan">Hapus Pilihan</a></li>
                                                    </ul>
                                                </div>
                                                <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-success" data-dismiss="modal">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.modal periode-->

                                <!-- TABEL ADHB -->
                                <!-- card-header adhb -->
                                <div class="mt-2" style="border-top: 1px solid #ccc;">
                                    <!-- export -->
                                    <div class="row justify-content-end mt-3">
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
                                    </div>

                                    <!-- <div class="row justify-content-end mt-3">
                                        <div class="col-auto align-items-center">
                                            <button type="button" class="btn btn-outline-success">
                                                <i class="fa fa-file-excel"></i>
                                                <span>Ekspor Excel</span>
                                            </button>
                                        </div>
                                        <div class="col-auto align-items-center">
                                            <button type="button" class="btn btn-outline-danger">
                                                <i class="fa fa-file-pdf"></i>
                                                <span>Ekspor PDF</span>
                                            </button>
                                        </div>
                                    </div> -->


                                    <!-- judul tabel -->
                                    <div class="card-header">
                                        <div class="row">
                                            <h2 class="card-title" style="font-weight: bold;" id="judulTableADHB"></h2>
                                        </div>
                                        <div class="row">
                                            <!-- <p>Keterangan diskrepansi positif = kota lebih besar, diskrepansi negatif = kota lebih kecil</p> -->
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->

                                <div class="table-responsive text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll; ">
                                    <table id="tabelRingkasan" class="table table-bordered table-hover">
                                        <thead class="text-center table-primary sticky-top">
                                            <tr>
                                                <th colspan="2" rowspan="2" style="vertical-align: middle;">Komponen</th>
                                                <th colspan="3">2023Q1</th>
                                                <th colspan="3">2023Q2</th>
                                                <th colspan="3">2023Q3</th>
                                                <th colspan="3">2023Q4</th>
                                                <th colspan="3">2023</th>
                                            </tr>
                                            <tr>
                                                <th>Diskrepansi</th>
                                                <th>DKI Jakarta</th>
                                                <th>Total Kab/Kota</th>
                                                <th>Diskrepansi</th>
                                                <th>DKI Jakarta</th>
                                                <th>Total Kab/Kota</th>
                                                <th>Diskrepansi</th>
                                                <th>DKI Jakarta</th>
                                                <th>Total Kab/Kota</th>
                                                <th>Diskrepansi</th>
                                                <th>DKI Jakarta</th>
                                                <th>Total Kab/Kota</th>
                                                <th>Diskrepansi</th>
                                                <th>DKI Jakarta</th>
                                                <th>Total Kab/Kota</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th colspan="2" style="font-weight: bold; position:sticky">PDRB</th>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">1. Pengeluaran Konsumsi Rumah Tangga (1.a. s/d 1.l.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">2. Pengeluaran Konsumsi LNPRT</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">3. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">4. Pembentukan Modal Tetap Bruto (4.a. + 4.b.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold;position:sticky">5. Perubahan Inventori</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">6. Ekspor Luar Negeri (6.a. + 6.b.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">7. Ekspor Luar Negeri (7.a. + 7.b.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-weight: bold; position:sticky">8. Net Ekspor Antar Daerah (8.a. + 8.b.)</td>
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
                                                <td>0,00</td>
                                                <td>0,00</td>
                                                <td>0,00</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <!-- tabel adhb end -->

                                <!-- tabel ADHK -->
                                <div class="mt-2">
                                    <!-- card-header ADHK -->
                                    <div class="card-header">
                                        <div class="row">
                                            <h2 class="card-title" style="font-weight: bold;" id="judulTableADHK"></h2>
                                        </div>
                                        <div class="row">
                                            <!-- <p>Keterangan diskrepansi positif = kota lebih besar, diskrepansi negatif = kota lebih kecil</p> -->
                                        </div>
                                    </div>
                                    <!-- /.card-header -->

                                    <!-- tabel ADHK -->
                                    <div class="table-responsive text-nowrap" style="overflow-y: scroll; height: 400px;  overflow-x:scroll;">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead class="text-center table-primary sticky-top border-primary align-content-center">
                                                <tr>
                                                    <th colspan="2" rowspan="2" style="vertical-align: middle;">Komponen</th>
                                                    <th colspan="3">2023Q1</th>
                                                    <th colspan="3">2023Q2</th>
                                                    <th colspan="3">2023Q3</th>
                                                    <th colspan="3">2023Q4</th>
                                                    <th colspan="3">2023</th>
                                                </tr>
                                                <tr>
                                                    <th>Diskrepansi</th>
                                                    <th>DKI Jakarta</th>
                                                    <th>Total Kab/Kota</th>
                                                    <th>Diskrepansi</th>
                                                    <th>DKI Jakarta</th>
                                                    <th>Total Kab/Kota</th>
                                                    <th>Diskrepansi</th>
                                                    <th>DKI Jakarta</th>
                                                    <th>Total Kab/Kota</th>
                                                    <th>Diskrepansi</th>
                                                    <th>DKI Jakarta</th>
                                                    <th>Total Kab/Kota</th>
                                                    <th>Diskrepansi</th>
                                                    <th>DKI Jakarta</th>
                                                    <th>Total Kab/Kota</th>
                                                </tr>

                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th colspan="2" style="font-weight: bold;">PDRB</th>
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
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Rumah Tangga (1.a. s/d 1.l.)</td>
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
                                                    <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</td>
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
                                                    <td colspan="2" style="font-weight: bold;">5. Perubahan Inventori</td>
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
                                                    <td colspan="2" style="font-weight: bold;">7. Ekspor Luar Negeri (7.a. + 7.b.)</td>
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
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">8. Net Ekspor Antar Daerah (8.a. + 8.b.)</td>
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
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                    <td>0,00</td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- tabel ADHK end -->
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->