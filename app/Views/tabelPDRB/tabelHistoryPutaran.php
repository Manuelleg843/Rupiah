        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?= $subTajuk?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Layout</a></li>
                                <li class="breadcrumb-item active">Fixed Navbar Layout</li>
                            </ol>
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
                                                <!-- <label>Jenis Tabel: </label> -->
                                                <select class="form-control select2" style="width: 100%;">
                                                    <option selected="selected">Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah)</option>
                                                    <option>Tabel 3.2. PDRB ADHK Menurut Pengeluaran (Juta Rupiah)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <!-- <label>Kota : </label> -->
                                                <select class="form-control select2" style="width: 100%;">
                                                    <option>DKI Jakarta</option>
                                                    <option>Jakarta Pusat</option>
                                                    <option>Jakarta Utara</option>
                                                    <option>Jakarta Barat</option>
                                                    <option>Jakarta Selatan</option>
                                                    <option>Jakarta Timur</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <!-- <label>Putaran: </label> -->
                                                <select class="form-control select2" style="width: 100%;">
                                                    <option>Putaran 1</option>
                                                    <option>Putaran 2</option>
                                                    <option>Putaran 3</option>
                                                    <option>Putaran 4</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-sm">
                                                Pilih Periode
                                            </button>
                                            <!-- <div class="form-group">
                                                <label>Periode: </label>
                                                <select class="form-control select2" style="width: 100%;">
                                                    <option>belum dibikin optionnya</option>
                                                </select>
                                            </div> -->
                                        </div>

                                    </div>

                                    <!-- export -->
                                    <div class="row mb-2 justify-content-md-end">
                                        <div class="col-auto align-items-center">
                                            <button type="submit" class="btn btn-outline-success">
                                                <i class="fa fa-file-excel"></i>
                                                <span>Ekspor Excel</span>
                                            </button>
                                        </div>
                                        <div class="col-auto align-items-center">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fa fa-file-pdf"></i>
                                                <span>Ekspor PDF</span>
                                            </button>
                                        </div>
                                        <div class="col-auto align-items-center">
                                            <button type="submit" class="btn btn-outline-success">
                                                <i class="fa fa-file-excel"></i>
                                                <span>Ekspor Semua Putaran</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- card-header -->
                                    <div class="card-header">
                                        <div class="row">
                                            <h2 class="card-title" style="font-weight: bold;">Tabel 3.1. PDRB ADHB Menurut Pengeluaran (Juta Rupiah) - DKI Jakarta - Putaran 4</h2>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-hover">
                                            <thead class="text-center table-primary">
                                                <tr>
                                                    <th colspan="2">Komponen</th>
                                                    <th>2023Q1</th>
                                                    <th>2023Q2</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Rumah Tangga (1.a. s/d 1.l.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">2. Pengeluaran Konsumsi LNPRT</td>
                                                    <td>Rp</td>
                                                    <td>Rp </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">4. Pembentukan Modal Tetap Bruto (4.a. + 4.b.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">5. Perubahan Inventori</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">6. Ekspor Luar Negeri (6.a. + 6.b.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">7. Ekspor Luar Negeri (7.a. + 7.b.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="font-weight: bold;">8. Net Ekspor Antar Daerah (8.a. + 8.b.)</td>
                                                    <td>Rp</td>
                                                    <td>Rp</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" style="font-weight: bold;">PDRB</th>
                                                    <th>Rp</th>
                                                    <th>Rp</th>
                                                </tr>
                                            </tfoot>
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