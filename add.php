<?php
// require_once 'php/core/init.php';
// $user = new User();
// $override = new OverideData();
// $email = new Email();
// $random = new Random();
// $validate = new validate();
// $successMessage = null;
// $pageError = null;
// $errorMessage = null;
// if ($user->isLoggedIn()) {
//     if (Input::exists('post')) {
       
//     }
// } else {
//     Redirect::to('index.php');
// }
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidebar.php'; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Register Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Register Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">

                            <!-- general form elements disabled -->
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">General Elements</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Study</label>
                                                    <select class="form-control" id="project_id" name="project_id" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('study') as $group) { ?>
                                                            <option value="<?= $group['name'] ?>"><?= $group['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Text Disabled</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..." disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Text Disabled</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..." disabled>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Text Disabled</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..." disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Text</label>
                                                    <input type="text" class="form-control" placeholder="Enter ...">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Text Disabled</label>
                                                    <input type="text" class="form-control" placeholder="Enter ..." disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>Textarea</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Textarea Disabled</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter ..." disabled></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- input states -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess"><i class="fas fa-check"></i> Input with
                                                success</label>
                                            <input type="text" class="form-control is-valid" id="inputSuccess" placeholder="Enter ...">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputWarning"><i class="far fa-bell"></i> Input with
                                                warning</label>
                                            <input type="text" class="form-control is-warning" id="inputWarning" placeholder="Enter ...">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputError"><i class="far fa-times-circle"></i> Input with
                                                error</label>
                                            <input type="text" class="form-control is-invalid" id="inputError" placeholder="Enter ...">
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- checkbox -->
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox">
                                                        <label class="form-check-label">Checkbox</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" checked>
                                                        <label class="form-check-label">Checkbox checked</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" disabled>
                                                        <label class="form-check-label">Checkbox disabled</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <!-- radio -->
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio1">
                                                        <label class="form-check-label">Radio</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="radio1" checked>
                                                        <label class="form-check-label">Radio checked</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled>
                                                        <label class="form-check-label">Radio disabled</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Select</label>
                                                    <select class="form-control">
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Select Disabled</label>
                                                    <select class="form-control" disabled>
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <!-- Select multiple-->
                                                <div class="form-group">
                                                    <label>Select Multiple</label>
                                                    <select multiple class="form-control">
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Select Multiple Disabled</label>
                                                    <select multiple class="form-control" disabled>
                                                        <option>option 1</option>
                                                        <option>option 2</option>
                                                        <option>option 3</option>
                                                        <option>option 4</option>
                                                        <option>option 5</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (right) -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include 'footer.php'; ?>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>