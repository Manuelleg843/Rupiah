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
                        <select class="form-control" style="width: 100%;" id="pilihKota">
                            <option value="Pilih Wilayah" hidden>Pilih Wilayah</option>
                            <option value="DKI Jakarta">Provinsi DKI Jakarta</option>
                            <option value="Kepulauan Seribu">Kepulauan Seribu</option>
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
            <div class="row">
                <?php if (session()->has('msg')) : ?>
                    <div class="text-danger col">
                        <?= session('msg') ?>
                    </div>
                <?php endif; ?>
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
                    <div class="card-body">
                        <form method="post" action="<?= base_url('/downloadExcel'); ?>">
                            <div class="form-group">
                                <label for="downloadTemplate">1. Download Format Template Upload Terbaru</label>
                                <div id="checkboxes-container-3-years" class="ml-2 mb-2"></div>
                                <button type="submit" class="btn btn-warning">Download Template</button>
                                <input type="hidden" id="kotaSelected" name="kotaSelected" hidden>
                            </div>
                        </form>
                        <form method="post" action="<?= base_url('/uploadExcel'); ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="alasanUpload">2. Isi Keterangan/Alasan Upload</label>
                                <textarea class="form-control" rows="3" placeholder="Isikan Keterangan/Alasan..." name="alasanUpload" id="alasanUpload"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputFile">3. Upload File Template yang Telah Diisi</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputFile" name="inputFile" accept=".xlsx">
                                        <label class="custom-file-label" for="inputFile" id="inputFileLabel">Pilih file</label>
                                    </div>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary toastsDefaultWarning" data-dismiss="modal">Upload Data</button> -->
                            <button type="submit" class="btn btn-primary">Upload Data</button>
                        </form>
                    </div>
                    <!-- /.card-body -->
                    <!-- <div class="card-footer">
                    </div> -->
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
                        <div class="card-body">
                            <div class="row mt-2 justify-content-between">
                                <div class="col-auto">
                                    <div class="form-group">
                                        <select class="form-control" style="width: 100%;">
                                            <option hidden>Pilih Jenis Tabel</option>
                                            <option>Tabel PDRB Atas Harga Berlaku (Juta Rupiah)</option>
                                            <option>Tabel PDRB Atas Harga Konstan (Juta Rupiah)</option>
                                        </select>
                                    </div>
                                </div>
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
        </div>
    </section>
    <!-- /.content -->
    <script>
        // Mengambil wilayah yang sudah terpilih
        const kotaSelected = document.getElementById("kotaSelected");
        pilihKota.addEventListener("change", function() {
            varKotaSelected = this.value;

            modalWilayah.textContent = "Upload PDRB - " + varKotaSelected;
            kotaSelected.setAttribute("value", varKotaSelected);
        });
    </script>