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
                            <a href="<?= base_url('/beranda'); ?>" class="nav-link <?= ($tajuk == 'Beranda') ? 'active'  : ''; ?>">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Beranda
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if (in_array('3', session()->get('permission'))) { ?>
                    <li class="nav-item menu-open">
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/admin/administrator'); ?>" class="nav-link <?= ($tajuk == 'Admin') ? 'active'  : ''; ?>">
                                    <i class="nav-icon fas fa-user-plus"></i>
                                    <p>
                                        Admin
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (in_array('4', session()->get('permission'))) { ?>
                    <li class="nav-item menu-open">
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/monitoring'); ?>" class="nav-link <?= ($tajuk == 'Monitoring Putaran') ? 'active'  : ''; ?>">
                                    <i class="far fas fa-tachometer-alt nav-icon"></i>
                                    <p>
                                        Monitoring
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if (in_array('2', session()->get('permission'))) { ?>
                    <!-- Kode yang dijalankan jika kondisi benar -->
                    <li class="nav-item menu-open">
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/uploadData/angkaPDRB'); ?>" class="nav-link <?= ($subTajuk == 'Angka PDRB') ? 'active'  : ''; ?>">
                                    <i class="far fas fa-upload nav-icon"></i>
                                    <p>Upload Angka PDRB</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
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
                            <a href="<?= base_url('/tabelPDRB/tabelPerKota'); ?>" class="nav-link <?= ($subTajuk == 'Tabel PDRB Per Kota (PKRT 7 Komponen)') ? 'active'  : ''; ?>">
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