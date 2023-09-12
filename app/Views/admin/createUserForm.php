<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $subTajuk ?></h1>
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
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <!-- card-header -->
                        <div class="card-header">
                            <div class="row ml-4 mt-4 mb-2">
                                <h2 class="card-title" style="font-weight: bold;">Form Tambah User</h2>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- card body -->
                        <div class="card-body mx-4 text-nowrap">
                            <form action="proses-form.php" method="post">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputGelarDepan">Gelar Depan</label>
                                        <input type="text" class="form-control" id="inputGelarDepan">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputGelarBelakang">Gelar Belakang</label>
                                        <input type="text" class="form-control" id="inputGelarBelakang">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputGelarDepan">NIP Lama</label>
                                        <input type="text" class="form-control" id="inputGelarDepan">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputGelarBelakang">NIP Baru</label>
                                        <input type="text" class="form-control" id="inputGelarBelakang">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dropdownSelect">Satuan Kerja</label>
                                    <select class="form-control" id="dropdownSelect" name="selectedOption">
                                        <option selected value="3100">DKI Jakarta</option>
                                        <option value="3171">Jakarta Pusat</option>
                                        <option value="3101">Kepulauan Seribu</option>
                                        <option value="3172">Jakarta Utara</option>
                                        <option value="3173">Jakarta Barat</option>
                                        <option value="3174">Jakarta Selatan</option>
                                        <option value="3175">Jakarta Timur</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="dropdownSelect">Jabatan Fungsional</label>
                                    <select class="form-control" id="dropdownSelect" name="selectedOption">
                                        <option value="Statstisi Ahli Madya">Statstisi Ahli Madya</option>
                                        <option value="Statistisi Ahli Muda">Statistisi Ahli Muda</option>
                                        <option value="Pranata Komputer Madya">Pranata Komputer Madya</option>
                                        <option value="Pranata Komputer Ahli Muda">Pranata Komputer Ahli Muda</option>
                                        <option value="Statistisi Ahli Pertama">Statistisi Ahli Pertama</option>
                                        <option value="Pranata Komputer Ahli Pertama">Pranata Komputer Ahli Pertama</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->