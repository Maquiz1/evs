<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();
$validate = new validate();
$successMessage = null;
$pageError = null;
$errorMessage = null;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
    }
} else {
    // Redirect::to('index.php');
}
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
                                                    <label>SENSITIZATION NUMBER:</label>
                                                    <input type="text" class="form-control" name="sensitization_no" class="sensitization_no" pattern="\d*" minlength="3" maxlength="3" value="" required />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>SENSITIZATION ONE:</label>
                                                    <select id="sensitization_one" name="sensitization_one" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>SENSITIZATION TWO:</label>
                                                    <select id="sensitization_two" name="sensitization_two" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select id="client_category" name="client_category" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('client_category') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" name="fname" class="form-control" value="" required="" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>Second Name</label>
                                                    <input type="text" name="mname" class="form-control" value="" required="" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" name="lname" class="form-control" value="" required="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>DATE OF BIRTH</label>
                                                    <input type="date" class="form-control fas fa-calendar input-prefix" name="dob" id="dob" value="" required="" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Child attending school?:</label>
                                                    <select id="attend_school" name="attend_school" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('yes_no_na') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>GENDER:</label>
                                                    <select id="gender" name="gender" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('gender') as $gender) { ?>
                                                            <option value="<?= $gender['name'] ?>"><?= $gender['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Phone:</label>
                                                    <input type="text" name="phone1" class="form-control" pattern="\d*" minlength="10" maxlength="10" value="" required />
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Phone2:</label>
                                                    <input type="text" name="phone2" class="form-control" pattern="\d*" minlength="10" maxlength="10" value="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>REGION:</label>
                                                    <select id="region" name="region" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('region') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>DISTRICT:</label>
                                                    <select id="district" name="district" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('district') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>WARD:</label>
                                                    <select id="ward" name="ward" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('ward') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>VILLAGE:</label>
                                                    <input type="text" name="village" id="village" class="form-control" value="" />
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hamlet / Kitongoji:</label>
                                                    <input type="text" name="hamlet" id="hamlet" class="form-control" value="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>For Bagamoyo residents, please specify the intended duration of stay in Bagamoyo:</label>
                                                    <select id="duration" name="duration" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('duration') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Is the participant willing to be contacted for the next sensitization meeting?:</label>
                                                    <select id="willing_contact" name="willing_contact" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('yes_no') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>Briefly describe participant residential location in relation to the nearest famous neighborhoods:
                                                    </label>
                                                    <textarea name="location" id="location" cols="50%" rows="3" value="" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Status:</label>
                                                    <select id="status" name="status" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('status') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 box1">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Reason:</label>
                                                    <select id="reason" name="reason" value="" class="form-control">
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('end_study_reason') as $lt) { ?>
                                                            <option value="<?= $lt['reason'] ?>"><?= $lt['reason'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 box2">
                                                <!-- textarea -->
                                                <div class="form-group">
                                                    <label>Other reason Details:Location(SPECIFY Place of a Participant)</label>
                                                    <textarea name="other_reason" id="other_reason" cols="50%" rows="3" value=""></textarea>
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