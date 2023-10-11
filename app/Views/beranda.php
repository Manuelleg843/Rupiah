<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs">
                    <h1 class="m-0"><?= $tajuk ?></h1>
                </div>
                <div class="col">
                    <small id="detail_tutup" <?= ($isActive == 0) ? 'hidden' : '' ?>>*Angka Sementara</small>
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
                <!-- Card ADHB -->
                <div class="col-lg col-md-6 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Atas Dasar Harga Berlaku</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">
                                        <?php
                                        echo number_format($adhb[0]->nilai, 0, ',', '.');
                                        ?>
                                    </h6>
                                    <span class="text-white">
                                        <?php
                                        echo 'Diskrepansi ' . number_format($Diskrepansi_adhb, 2, ',', '.') . '%';
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card ADHK -->
                <div class="col-lg col-md-6 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Atas Dasar Harga Konstan</span>
                                    <h6 class="my-3 text-white number-stats" style=" line-height: 2rem; font-size: 2rem;">
                                        <?php
                                        echo number_format($adhk[0]->nilai, 0, ',', '.');
                                        ?>
                                    </h6>
                                    <span class="text-white">
                                        <?php
                                        echo 'Diskrepansi ' . number_format($Diskrepansi_adhk, 2, ',', '.') . '%';
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Pertumbuhan Y ON Y -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Y-ON-Y</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">
                                        <?php
                                        echo number_format($hasil_Y_ON_Y, 2, ',', '.') . '%';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Pertumbuhan Q TO Q -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">Q-TO-Q</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">
                                        <?php
                                        echo number_format($hasil_Q_TO_Q, 2, ',', '.') . '%';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card Pertumbuhan C TO C -->
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="card card-small bg-primary card-stats">
                        <div class="card-body d-flex p-0">
                            <div class="d-flex flex-column m-auto">
                                <div class="text-center">
                                    <span class="text-white">C-TO-C</span>
                                    <h6 class="my-3 text-white number-stats" style="line-height: 2rem; font-size: 2rem;">
                                        <?php
                                        echo number_format($hasil_C_TO_C, 2, ',', '.') . '%';
                                        ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Update -->
            <div class="row d-flex justify-content-between">
                <div class="col card py-2 last-update mx-2">
                    <div class="row">
                        <div class="col">
                            <?php
                            if ($adhb[0]->id_kuartal > "4") {
                                echo 'Tahun ' . $adhb[0]->tahun . ', Triwulan 4, Putaran ' . $adhb[0]->putaran;
                            } else {
                                echo 'Tahun ' . $adhb[0]->tahun . ', Triwulan ' . $adhb[0]->id_kuartal . ', Putaran ' . $adhb[0]->putaran;
                            }
                            ?>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <?php
                            echo '(Last Update ' . $adhb[0]->uploaded_at . ' )'; // caranya pasang waktu gimana
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAR CHART (Last Update) -->
            <div class="row">
                <!-- BarChart  Y ON Y -->
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
                <!-- BarChart  Q TO Q -->
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
                <!-- BarChart  C TO C -->
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
                        <!-- Filter -->
                        <div class="card-header">
                            <form action="" class="d-flex align-items-center">
                                <select name="Grafik" id="Grafik" class="mr-3 py-2">
                                    <option id="9" value="PDRB" selected>Grafik Pertumbuhan PDRB</option>
                                    <option id="1" value="PKRT">Grafik Pertumbuhan PKRT</option>
                                    <option id="2" value="PK-LNPRT">Grafik Pertumbuhan PK-LNPRT</option>
                                    <option id="3" value="PKP">Grafik Pertumbuhan PKP</option>
                                    <option id="4" value="PMTB">Grafik Pertumbuhan PMTB</option>
                                    <option id="6" value="Ekspor">Grafik Pertumbuhan Ekspor</option>
                                    <option id="7" value="Impor">Grafik Pertumbuhan Impor</option>
                                </select>
                                <select name="Jangka" id="Jangka" class="mr-3 py-2">
                                    <option id="2" value="Triwulan">Triwulan</option>
                                    <option id="1" value="Tahunan"> Tahunan</option>
                                </select>
                                <select style="width:80px;" name="Jenis" id="Jenis" class="mr-3 py-2">
                                    <!-- Untuk Tahunan -->
                                    <option id="11" value="Tahunan_YONY">Y-ON-Y</option>
                                    <!-- Untuk Triwulan -->
                                    <option id="21" value="Triwulan_YONY">Y-ON-Y</option>
                                    <option id="22" value="Triwulan_QTOQ">Q-TO-Q</option>
                                    <option id="23" value="Triwulan_CTOC">C-TO-C</option>
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
                                                    <div id="checkboxes-container-year-only" class="checkboxes-container-or-year-only checkboxes-periode"></div>
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
                                                        <div class="dropdown-divider"></div>
                                                        <li><a class="dropdown-item" href="javascript:clearCheckbox()" id="hapusPilihan">Hapus Pilihan</a></li>
                                                    </ul>
                                                </div>
                                                <div><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-success" data-dismiss="modal" id="periode-beranda" onclick="loadDataLine()">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Grafik -->
                        <div class="card-body" id="graph-container">
                            <canvas id="lineChart" style="height: 281px; width: 649px;"></canvas>
                        </div>
                        <!-- /.card-body-->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script>
        window.addEventListener('load', function() {
            TerimaDataBar();
        });
    </script>