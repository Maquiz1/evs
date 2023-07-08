<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$pageError = null;
$successMessage = null;
$errorM = false;
$errorMessage = null;
$t_crf = 0;
$p_crf = 0;
$w_crf = 0;
$s_name = null;
$c_name = null;
$site = null;
$country = null;
$study_crf = null;
$data_limit = 10000;
// $favicon = $override->get('images', 'cat', 1)[0];
// $logo = $override->get('images', 'cat', 2)[0];

//modification remove all pilot crf have been removed/deleted from study crf
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('register')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'project_id' => array(
                    'required' => true,
                ),
                'initial' => array(
                    'required' => true,
                ),
                'sensitization_one' => array(
                    'required' => true,
                ),
                'sensitization_two' => array(
                    'required' => true,
                ),
                'sensitization_no' => array(
                    'required' => true,
                ),
                'client_category' => array(
                    'required' => true,
                ),
                'fname' => array(
                    'required' => true,
                ),
                'mname' => array(
                    'required' => true,
                ),
                'lname' => array(
                    'required' => true,
                ),
                'dob' => array(
                    'required' => true,
                ),
                'gender' => array(
                    'required' => true,
                ),
                // 'phone1' => array(
                //     'required' => true,
                //     // 'unique' => 'details'
                // ),
                'attend_school' => array(
                    'required' => true,
                ),
                'region' => array(
                    'required' => true,
                ),
                'district' => array(
                    'required' => true,
                ),
                'ward' => array(
                    'required' => true,
                ),
                'village' => array(
                    'required' => true,
                ),
                'hamlet' => array(
                    'required' => true,
                ),
                'duration' => array(
                    'required' => true,
                ),
                'willing_contact' => array(
                    'required' => true,
                ),
                'location' => array(
                    'required' => true,
                ),
                'status' => array(
                    'required' => true,
                )
            ));
            if ($validate->passed()) {
                $dob_date = date('Y-m-d', strtotime(Input::get('dob')));
                $intwr_date = date('Y-m-d', strtotime(Input::get('interviewer_date')));
                $rvwr_date = date('Y-m-d', strtotime(Input::get('reviewer_date')));
                $death_date = date('Y-m-d', strtotime(Input::get('death_date')));

                // $details = $override->selectData3('details', 'sensitization_no', Input::get('sensitization_no'), 'project_name', Input::get('project_id'))[0];
                // $phone = $override->selectData1('details', 'phone', Input::get('phone1'))[0];
                if ($details) {
                    $errorMessage = 'Please re-check Sensitization number For That Study, Already Registered!';
                } else {
                    try {
                        $user->createRecord('details', array(
                            'project_name' => Input::get('project_id'),
                            'initial' => Input::get('initial'),
                            'sensitization_one' => Input::get('sensitization_one'),
                            'sensitization_two' => Input::get('sensitization_two'),
                            'sensitization_no' => Input::get('sensitization_no'),
                            'client_category' => Input::get('client_category'),
                            'fname' => Input::get('fname'),
                            'mname' => Input::get('mname'),
                            'lname' => Input::get('lname'),
                            'dob' => $dob_date,
                            'gender' => Input::get('gender'),
                            'phone' => Input::get('phone1'),
                            'phone2' => Input::get('phone2'),
                            'attend_school' => Input::get('attend_school'),
                            'region' => Input::get('region'),
                            'district' => Input::get('district'),
                            'ward' => Input::get('ward'),
                            'village' => Input::get('village'),
                            'hamlet' => Input::get('hamlet'),
                            'duration' => Input::get('duration'),
                            'willing_contact' => Input::get('willing_contact'),
                            'location' => Input::get('location'),
                            'staff_id' => $user->data()->id,
                            'status' => Input::get('status'),
                            'reason' => Input::get('reason'),
                            'other_reason' => Input::get('other_reason'),
                            'death_date' => $death_date,
                            'details' => Input::get('details'),
                            'end_study' => Input::get('end_study'),
                        ));
                        $successMessage = 'Client Registered Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
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
                                    <h3 class="card-title">Register Clients</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Registered Date</label>
                                                    <input type="date" class="form-control fas fa-calendar input-prefix" name="registered_date" id="registered_date" value="" required="" />
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
                                                    <label>Marital Status:</label>
                                                    <select id="region" name="region" class="form-control" value="" required>
                                                        <option value="">Select</option>
                                                        <?php foreach ($override->getData('marital_status') as $lt) { ?>
                                                            <option value="<?= $lt['name'] ?>"><?= $lt['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">
                                                        <!-- <label class="col-form-label" for="inputSuccess"><i class="fas fa-check"></i><i class="far fa-bell"><i class="far fa-times-circle"></i> -->
                                                        <!-- Phone :</label> <input type="text" name="phone1" class="form-control is-valid is-invalid is-warning" id="inputSuccess" pattern="\d*" minlength="10" maxlength="10" value="" required /> -->
                                                        Phone :</label> <input type="text" name="phone1" class="form-control" id="inputSuccess" pattern="\d*" minlength="10" maxlength="10" value="" required />

                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-2">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Phone2:</label>
                                                    <input type="text" name="phone2" class="form-control" pattern="\d*" minlength="10" maxlength="10" value="" />
                                                </div>
                                            </div>


                                            <div class="col-sm-2">
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
                                            <div class="col-sm-2">
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


                                            <div class="col-sm-2">
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
                                            <div class="col-sm-2">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>VILLAGE:</label>
                                                    <input type="text" name="village" id="village" class="form-control" value="" />
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <!-- select -->
                                                <div class="form-group">
                                                    <label>Hamlet / Kitongoji:</label>
                                                    <input type="text" name="hamlet" id="hamlet" class="form-control" value="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4">
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
                                                <div class="form-group">
                                                    <label>Briefly describe participant residential location in relation to the nearest famous neighborhoods:
                                                    </label>
                                                    <textarea name="location" id="location" cols="40%" rows="3" value="" required></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Comments / Remarks / Notes
                                                        :
                                                    </label>
                                                    <textarea name="comments" id="comments" cols="40%" rows="3" value="" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                                            <input type="submit" name="register" value="Register Client" class="btn btn-success btn-clean">
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