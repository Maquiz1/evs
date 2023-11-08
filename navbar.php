<?php
require_once 'php/core/init.php';

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec = 30;
if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('save_excel_data')) {
            $validate = $validate->check($_POST, array(
                // 'name' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {

                    $fileName = $_FILES['import_file']['name'];
                    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

                    $allowed_ext = ['xls', 'csv', 'xlsx'];

                    if (in_array($file_ext, $allowed_ext)) {
                        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
                        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
                        $data = $spreadsheet->getActiveSheet()->toArray();

                        $count = "0";

                        $i = 0;



                        foreach ($data as $row) {

                            $dob_date = date('Y-m-d', strtotime(Input::get('dob')));

                            $dob = $row['5'];
                            $age = $user->dateDiffYears(date('Y-m-d'), $dob);


                            if ($count > 0) {
                                $registered_date = $row['0'];
                                $fname = $row['1'];
                                $mname = $row['2'];
                                $lname = $row['3'];
                                $dob = $row['4'];
                                $age = $row['5'];
                                $gender = $row['6'];
                                $marital = $row['7'];
                                $education = $row['8'];
                                $occupation = $row['9'];
                                $phone1 = $row['10'];
                                $phone2 = $row['11'];
                                $region = $row['12'];
                                $district = $row['13'];
                                $ward = $row['14'];
                                $village = $row['15'];
                                $hamlet = $row['16'];
                                $duration = $row['17'];
                                $location = $row['18'];
                                $comments = $row['19'];

                                $user->createRecord('clients', array(
                                    'registered_date' => $registered_date,
                                    'fname' => $fname,
                                    'mname' => $mname,
                                    'lname' => $lname,
                                    'dob' => $dob,
                                    'age' => $age,
                                    'gender' => $gender,
                                    'marital' => $marital,
                                    'education' => $education,
                                    'occupation' => $occupation,
                                    'phone1' => $phone1,
                                    'phone2' => $phone2,
                                    'region' => $region,
                                    'district' => $district,
                                    'ward' => $ward,
                                    'village' => $village,
                                    'hamlet' => $hamlet,
                                    'duration' => $duration,
                                    'location' => $location,
                                    'staff_id' => $user->data()->id,
                                    'site_id' => $user->data()->site_id,
                                    'status' => 1,
                                    'comments' => $comments,
                                ));
                                $msg = true;
                            } else {
                                $count = "1";
                            }
                        }

                        if ($msg) {
                            $successMessage = 'Successfully Imported';
                            // header('Location: import.php');
                            // exit(0);
                        } else {
                            $successMessage = 'Not Imported';
                            // header('Location: import.php');
                            // exit(0);
                        }
                    } else {
                        $successMessage = 'Invalid File';
                        // header('Location: import.php');
                        // exit(0);
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
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







<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index1.php" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="add.php?id=1&btn=Add" class="nav-link">Register New</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="add_user.php" class="nav-link">Add Staff</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="add_site.php" class="nav-link">Add Site</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="add_study.php" class="nav-link">Add Study</a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="import.php" class="nav-link">Upload Data</a>
        </li> -->
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addVisit">
            Upload Data </button>
    </ul>

    <div class="modal fade" id="addVisit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="validation" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title">Import Excel Data</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>File Name</label>
                                    <input type="file" name="import_file" class="form-control" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="save_excel_data" class="btn btn-primary mt-3" value="Import" />
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" role="button">Logout
                </a>
            </li>
        </ul>
</nav>