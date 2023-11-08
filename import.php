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




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Import Excel Data into database in PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <?php if ($errorMessage) { ?>
                    <div class="block">
                        <div class="alert alert-danger">
                            <b>Error!</b> <?= $errorMessage ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                <?php } elseif ($pageError) { ?>
                    <div class="block col-md-12">
                        <div class="alert alert-danger">
                            <b>Error!</b> <?php foreach ($pageError as $error) {
                                                echo $error . ' , ';
                                            } ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                <?php } elseif ($successMessage) { ?>
                    <div class="block">
                        <div class="alert alert-success">
                            <b>Success!</b> <?= $successMessage ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    </div>
                <?php } ?>

                <div class="card">
                    <div class="card-header">
                        <h4>How to Import Excel Data into database in PHP</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">

                            <input type="file" name="import_file" class="form-control" />
                            <input type="submit" name="save_excel_data" class="btn btn-primary mt-3" value="Import" />

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>