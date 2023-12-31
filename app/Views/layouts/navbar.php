<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- widget fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li>
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <!-- Icon from Freepik -->
                        <img src="<?= base_url('/assets/dist/img/user.png'); ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle" />
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                <?= session()->get('nama'); ?>
                            </h3>
                            <p class="text-sm"><?= session()->get('satker'); ?></p>
                            <p class="text-sm text-muted"><?= session()->get('role'); ?></p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('/UserController/logout'); ?>" class="dropdown-item dropdown-footer">Logout</a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->