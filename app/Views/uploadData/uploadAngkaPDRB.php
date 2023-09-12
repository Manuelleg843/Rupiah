<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-auto">
                    <h1><?= $tajuk ?></h1>
                </div>
            </div>

            <div class="row">
                <!-- filter berdasarkan kota -->
                <div class="col-auto mt-4">
                    <div class="form-group">
                        <select class="form-control" style="width: 100%;" id="selectKota">
                            <option value="Pilih Wilayah" hidden>Pilih Wilayah</option>
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Jakarta Utara">Jakarta Utara</option>
                            <option value="Jakarta Barat">Jakarta Barat</option>
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                            <option value="Jakarta Timur">Jakarta Timur</option>
                        </select>
                    </div>
                </div>
                <div class="col-auto mt-4">
                    <button type="button" class="btn btn-primary col start" data-toggle="modal" data-target="#modal-default">
                        <i class="fas fa-upload"></i>
                        <span>Upload</span>
                    </button>

                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="modalWilayah"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="downloadTemplate">1. Download Format Template Upload Terbaru</label>
                                <button class="btn btn-warning">Download Template</button>
                            </div>
                            <div class="form-group">
                                <label for="alasanUpload">2. Isi Keterangan/Alasan Upload</label>
                                <textarea class="form-control" rows="3" placeholder="Isikan Keterangan/Alasan..."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputFile">3. Upload File Berkas yang Telah Diisi</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputFile">
                                        <label class="custom-file-label" for="inputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Upload Data</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row mt-2">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;">
                                            <option hidden>Pilih Jenis Tabel</option>
                                            <option>Tabel PDRB Atas Harga Berlaku (Juta Rupiah)</option>
                                            <option>Tabel PDRB Atas Harga Konstan (Juta Rupiah)</option>
                                        </select>
                                    </div>
                                </div>
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
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="table-responsive text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">
                            <table id="tabelPDRB" class="table table-bordered table-hover">
                                <thead class="text-center table-primary sticky-top">
                                    <tr>
                                        <th colspan="2">Komponen</th>
                                        <th>2023Q1</th>
                                        <th>2023Q2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">1. Pengeluaran Konsumsi Rumah Tangga (1.a. s/d 1.l.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">2. Pengeluaran Konsumsi LNPRT</td>
                                        <td>Rp</td>
                                        <td>Rp </td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">3. Pengeluaran Konsumsi Pemerintah (3.a. + 3.b.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">4. Pembentukan Modal Tetap Bruto (4.a. + 4.b.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">5. Perubahan Inventori</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">6. Ekspor Luar Negeri (6.a. + 6.b.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">7. Ekspor Luar Negeri (7.a. + 7.b.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                    <tr style="height:5px; line-height:5px">
                                        <td colspan="2" style="font-weight: bold;">8. Net Ekspor Antar Daerah (8.a. + 8.b.)</td>
                                        <td>Rp</td>
                                        <td>Rp</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="height:5px; line-height:5px">
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