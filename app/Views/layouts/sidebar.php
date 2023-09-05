<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('/asset/index3.html'); ?>" class="brand-link">
        <img src="<?= base_url('/asset/dist/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('/asset/dist/img/user2-160x160.jpg'); ?>" class="img-circle elevation-2" alt="User Image" />
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/'); ?>" class="nav-link <?= ($tajuk == 'beranda') ? 'active'  : ''; ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Beranda
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu<?= ($tajuk == 'uploadData') ? '-open'  : ''; ?>">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>
                            Upload Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/uploadData/angkaPDRB'); ?>" class="nav-link <?= ($subTajuk == 'angkaPDRB') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Angka PDRB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-<?= ($tajuk == 'tabelPDRB') ? 'open'  : ''; ?>">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Tabel PDRB
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelRingkasan'); ?>" class="nav-link <?= ($subTajuk == 'tabelRingkasan') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel Ringkasan</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelPerProvinsi'); ?>" class="nav-link <?= ($subTajuk == 'tabelPerProvinsi') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel Per Provinsi</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelHistoryPutaran'); ?>" class="nav-link <?= ($subTajuk == 'tabelHistoryPutaran') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel History Putaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/arahRevisi'); ?>" class="nav-link <?= ($tajuk == 'arahRevisi') ? 'active'  : ''; ?>">
                                <i class="far fas fa-hourglass nav-icon"></i>
                                <p>
                                    Arah Revisi
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-download"></i>
                        <p>
                            Download
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
