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
                            <?php if (session()->get('id_satker') == 3100) { ?>
                                <option value="3100" selected>Provinsi DKI Jakarta</option>
                                <option value="3101">Kepulauan Seribu</option>
                                <option value="3173">Jakarta Pusat</option>
                                <option value="3175">Jakarta Utara</option>
                                <option value="3174">Jakarta Barat</option>
                                <option value="3171">Jakarta Selatan</option>
                                <option value="3172">Jakarta Timur</option>
                            <?php } else { ?>
                                <option value="3100">Provinsi DKI Jakarta</option>
                                <option value="<?= session()->get('id_satker'); ?>" selected><?= session()->get('satker'); ?></option>
                            <?php } ?>
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
            <!-- pesan error download -->
            <div class="row">
                <?php if (session()->has('msg')) : ?>
                    <div class="text-danger col">
                        <?= session('msg') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="judulModal">Upload PDRB - Provinsi DKI Jakarta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" action="<?= base_url('/downloadExcel'); ?>">
                            <div class="form-group">
                                <label for="downloadTemplate">Download Format Template Upload Terbaru</label>
                                <div id="checkboxes-container-3-years" class="ml-2 mb-2"></div>
                                <button type="submit" class="btn btn-warning">Download Template</button>
                                <input type="hidden" id="kotaJudulModal" name="kotaJudulModal" hidden>
                            </div>
                        </form>
                        <form method="post" action="<?= base_url('/uploadExcel'); ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="inputFile">Upload File Template yang Telah Diisi</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="inputFile" name="inputFile" accept=".xlsx">
                                        <label class="custom-file-label" for="inputFile" id="inputFileLabel">Pilih file</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload Data</button>
                        </form>
                    </div>
                    <!-- /.card-body -->
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
                                        <select class="form-control" style="width: 100%;" id="selectTableUpload" onchange="loadData()">
                                            <option hidden>Pilih Jenis Tabel</option>
                                            <option value="1" selected>Tabel PDRB Atas Harga Berlaku (Juta Rupiah)</option>
                                            <option value="2">Tabel PDRB Atas Harga Konstan (Juta Rupiah)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group">
                                        <button id="export-button-pdf" class="btn btn-outline-danger pdf">
                                            <i class="fa fa-file-pdf"></i>
                                            <span>Ekspor PDF</span>
                                        </button>
                                        <!-- </a> -->
                                        <button id="export-button-excel" onclick="fungsieksporButtonExcelPerkota()" class="btn btn-outline-success excel">
                                            <i class="fa fa-file-excel"></i>
                                            <span>Ekspor Excel</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <h2 class="card-title" style="font-weight: bold;" id="judulTable"></h2>
                            </div>
                            <!-- /.card-header -->
                            <div id="PDRBTableContainer" class="table-responsive text-nowrap" style="overflow-y: scroll; height: 400px; overflow-x:scroll;">
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <!-- jQuery -->
    <script src="<?= base_url('/assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script>
        // Menampilkan pesan error setelah upload (jika ada)
        $(document).ready(function() {
            <?php
            $errorMsg = session()->getFlashdata('errorMsg');
            if (isset($errorMsg) && is_array($errorMsg) && count($errorMsg) > 0) :
            ?>
                var errorMessage = <?php echo json_encode(implode("<br>", $errorMsg)); ?>;
                $(document).Toasts('create', {
                    class: "bg-warning toast-warning-validasi",
                    fixed: false,
                    title: "Perhatian!",
                    body: errorMessage,
                });
                console.log('errorMessage')
            <?php endif; ?>
        })
    </script>