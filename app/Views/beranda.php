<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $tajuk ?></h1>
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
            <!-- LATEST STATISTICS -->
            <div class="row">
                <div class="col-lg col-md-6 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Atas Dasar Harga Berlaku</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">857,22
                                        Triliun</h6>
                                    <span class="text-white">Diskrepansi -2,57%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg col-md-6 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Atas Dasar Harga Konstan</span>
                                    <h6 class="my-3 text-white number-stats" style=" line-height: 2rem; font-size: 2rem;">510,34
                                        Triliun</h6>
                                    <span class="text-white">Diskrepansi 0,76%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Y-ON-Y</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">5,13%</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Q-TO-Q</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">1,25%</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">C-TO-C</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">5,04%</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-between">
                <div class="col card py-2 last-update mx-2">
                    <div class="row">
                        <div class="col">Tahun 2023, Triwulan 2, Putaran 4</div>
                        <div class="col d-flex justify-content-end">(Last Update 2023-08-05 16:04:28)</div>
                    </div>
                </div>
            </div>

            <!-- BAR CHART -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Y-ON-Y</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChartYOY" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Q-TO-Q</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChartQTQ" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">C-TO-C</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChartCTC" style="min-height: 250px; height: 250px; max-height: 500px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- row -->

            <!-- LINE CHART -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="" class="d-flex align-items-center">
                                <select name="Grafik" id="Grafik" class="mr-3 py-2">
                                    <option value="Grafik_Pertumbuhan_PDRB">Grafik Pertumbuhan PDRB</option>
                                    <option value="Grafik_Pertumbuhan_PKRT">Grafik Pertumbuhan PKRT</option>
                                    <option value="Grafik_Pertumbuhan_PK-LNRT">Grafik Pertumbuhan PK-LNRT</option>
                                    <option value="Grafik_Pertumbuhan_PKB">Grafik Pertumbuhan PKB</option>
                                    <option value="Grafik_Pertumbuhan_PMTB">Grafik Pertumbuhan PKB</option>
                                    <option value="Grafik_Pertumbuhan_PKB">Grafik Pertumbuhan Ekspor</option>
                                    <option value="Grafik_Pertumbuhan_PMTB">Grafik Pertumbuhan Impor</option>
                                </select>
                                <select name="Jangka" id="Jangka" class="mr-3 py-2">
                                    <option value="Triwulan">Triwulan</option>
                                    <option value="Tahunan"> Tahunan</option>
                                </select>
                                <select name="Jenis" id="Jenis" class="mr-3 py-2">
                                    <!-- Untuk Tahunan -->
                                    <option value="Tahunan_YONY">Y-ON-Y</option>
                                    <!-- Untuk Triwulan -->
                                    <option value="Triwulan_YONY">Y-ON-Y</option>
                                    <option value="Triwulan_QTOQ">Q-TO-Q</option>
                                    <option value="Triwulan_CTOC">C-TO-C</option>
                                </select>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                    Pilih Periode
                                </button>

                                <!-- Modal Pilih Periode-->
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
                                                        <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown">Tahun</a>
                                                            <ul class="dropdown-menu" id="tahunDropdown">
                                                            </ul>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#" id="hapusPilihan">Hapus Pilihan</a></li>
                                                    </ul>
                                                </div>
                                                <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-success">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div id="line-chart" style="height: 300px;"></div>
                        </div>
                        <!-- /.card-body-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->