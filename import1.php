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

<?php include 'header.php'; ?>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidebar.php'; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1>
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
                            </h1>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1>Electronic Clinical Trial Volunteer Management System</h1>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">KCTF</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header">
                            <h4>Import Excel Data</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" enctype="multipart/form-data">

                                <input type="file" name="import_file" class="form-control" />
                                <input type="submit" name="save_excel_data" class="btn btn-primary mt-3" value="Import" />

                            </form>

                        </div>
                    </div>

                    <!-- /.container-fluid -->
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
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
</body>

</html>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Import Excel Data into database in PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->