<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Masuk</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/assets/css/adminlte.min.css">

    <style>
        body {
            background-image: url('/assets/img/bps-dki-blurred.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .login-box-msg {
            text-align: start;
            padding-left: 0;
        }

        .login-decor {
            max-width: 100%;
            height: auto;
        }

        .login-decor img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="bg-blur w-75">
        <div class="login-box w-100">
            <!-- /.login-logo -->
            <div class="card ">
                <div class="card-body row">
                    <div class="col-6 d-flex flex-column justify-content-center">
                        <div class="login-decor mb-4"><img src="/assets/img/logo-bps-dki-jakarta.png" alt="Logo bps dki jakarta"></div>
                        <h2>Selamat Datang,</h2>
                        <p>silakan masuk untuk memulai sesi Anda.</p>
                        <form action="<?= base_url('/login'); ?>" method="post">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Ingat Saya
                                        </label>
                                    </div>
                                </div> -->
                                <!-- /.col -->
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        <div class="social-auth-links text-center mt-2 mb-auto">
                            <p class="mb-2">
                                atau
                            </p>
                            <a href="#" class="btn btn-block btn-primary mb-1">
                                Masuk menggunakan SSO
                            </a>
                        </div>
                        <!-- /.social-auth-links -->
                        <div class="row">
                            <div class="col">
                                <span>
                                    <a href="<?= base_url('/lupa_password') ?>">Lupa kata sandi?</a>
                                </span>
                            </div>
                        </div>
                        <!-- <span>
                            <a href="forgot-password.html">Lupa kata sandi?</a>
                        </span>
                        <span class="mb-0">
                            <a href="register.html">Daftar</a>
                        </span> -->
                    </div>
                    <div class="col-6 login-decor">
                        <img src="/assets/img/login-vector-2.jpg" alt="Login decorations">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.login-box -->
    </div>

    <!-- jQuery -->
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/assets/js/adminlte.min.js"></script>
</body>

</html>