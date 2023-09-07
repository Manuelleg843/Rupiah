<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('/assets/index3.html'); ?>" class="brand-link">
        <img src="<?= base_url('/assets/dist/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" />
        <span class="brand-text font-weight-light">Rupiah</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/'); ?>" class="nav-link <?= ($tajuk == 'Beranda') ? 'active'  : ''; ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Beranda
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu<?= ($tajuk == 'Upload Data') ? '-open'  : ''; ?>">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>
                            Upload Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/uploadData/angkaPDRB'); ?>" class="nav-link <?= ($subTajuk == 'Angka PDRB') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Angka PDRB</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-<?= ($tajuk == 'Tabel PDRB') ? 'open'  : ''; ?>">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Tabel PDRB
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelRingkasan'); ?>" class="nav-link <?= ($subTajuk == 'Tabel Ringkasan') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel Ringkasan</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelPerKota'); ?>" class="nav-link <?= ($subTajuk == 'Tabel Per Kota') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel Per Kota</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/tabelPDRB/tabelHistoryPutaran'); ?>" class="nav-link <?= ($subTajuk == 'Tabel History Putaran') ? 'active'  : ''; ?>">
                                <i class="far fas fa-arrow-right nav-icon"></i>
                                <p>Tabel History Putaran</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('/arahRevisi'); ?>" class="nav-link <?= ($tajuk == 'Arah Revisi') ? 'active'  : ''; ?>">
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

