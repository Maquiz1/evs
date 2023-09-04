<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$numRec = 15;
$now = date('Y-m-d');
$nxt_day = date('Y-m-d', strtotime($now . ' + 1 days'));
$today = $override->getCount2News('visit', 'expected_date', $nxt_day, 'status', 0, 'site_id', $user->data()->site_id);


if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        $validate = new validate();
        if (Input::get('add_visit')) {
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $user->updateRecord('visit', array(
                        'visit_date' => Input::get('visit_date'),
                        'status' => 1,
                        'visit_status' => 1,
                        'comments' => Input::get('comments'),
                    ), Input::get('id'));
                    $successMessage = 'Visit  Added Successful';
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

        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidebar.php'; ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <!-- <div class="row mb-2">
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
                    </div> -->
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1>Today Visits</h1>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php if ($_GET['status'] == 1) { ?>
                                            List of Registered Clients
                                        <?php } elseif ($_GET['status'] == 2) { ?>
                                            List of Sensitized Clients

                                        <?php } elseif ($_GET['status'] == 3) { ?>
                                            List of Screened Clients

                                        <?php } elseif ($_GET['status'] == 4) { ?>
                                            List of Eligible Clients

                                        <?php } elseif ($_GET['status'] == 5) { ?>
                                            List of Enrolled Clients

                                        <?php } elseif ($_GET['status'] == 6) { ?>
                                            List of Available Clients

                                        <?php } elseif ($_GET['status'] == 7) { ?>

                                        <?php } ?>
                                </div>
                                <!-- /.card-header -->

                                <?php
                                $pagNum = 0;
                                $pagNum = $override->getCount2('visit', 'expected_date', date('Y-m-d'), 'status', 0, 'site_id', $user->data()->site_id);

                                $pages = ceil($pagNum / $numRec);
                                if (!$_GET['page'] || $_GET['page'] == 1) {
                                    $page = 0;
                                } else {
                                    $page = ($_GET['page'] * $numRec) - $numRec;
                                }
                                $visit = $override->getCount2News('visit', 'expected_date', date('Y-m-d'), 'status', 0, 'site_id', $user->data()->site_id);
                                ?>

                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Day.</th>
                                                <th>Date.</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>SEX</th>
                                                <th>AGE</th>
                                                <th>STUDY</th>
                                                <th>Phone</th>
                                                <th>STATUS</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 1;
                                            foreach ($visit as $value) {
                                                $dob = $value['dob'];
                                                $age = $user->dateDiffYears(date('Y-m-d'), $dob);
                                                $project_name = $override->get('study', 'id', $value['project_name'])[0];
                                                $name = $override->get('clients', 'id', $value['client_id'])[0];
                                                $gender = $override->get('clients', 'id', $value['client_id'])[0];
                                                $lname = $override->get('user', 'id', $user->data()->id)[0];
                                                $project_name = $override->get('study', 'id', $value['project_name'])[0];
                                            ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $value['visit_code']; ?></td>
                                                    <td><?= $value['expected_date']; ?></td>
                                                    <td><?= $name['fname']; ?></td>
                                                    <td><?= $name['mname']; ?></td>
                                                    <td><?= $name['lname']; ?></td>
                                                    <?php if ($name['gender'] == 1) { ?>
                                                        <td>Male</td>
                                                    <?php } elseif ($name['gender'] == 2) { ?>
                                                        <td>Female</td>
                                                    <?php } ?>
                                                    <td><?= $age; ?></td>
                                                    <td><?= $project_name['name']; ?></td>
                                                    <td><?= $name['phone1']; ?></td>
                                                    <?php if ($value['status'] == 1) { ?>
                                                        <td>Done</td>
                                                    <?php } else {
                                                    ?>
                                                        <td>Pending</td>

                                                    <?php
                                                    } ?>

                                                    <td>
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addVisit<?= $value['id'] ?>">
                                                            Add Visit
                                                        </button>
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="addVisit<?= $value['id'] ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Follow Up Status</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label>Follow Up Date</label>
                                                                                <input value="<?php if ($value['status'] != 0) {
                                                                                                    echo $value['visit_date'];
                                                                                                } ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" />
                                                                                <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label>Comments / Remarks / Notes
                                                                                    :
                                                                                </label>
                                                                                <textarea name="comments" id="comments" cols="20%" rows="3" placeholder="Type Comments..." onkeyup="myFunction()" required><?php if ($value['comments']) {
                                                                                                                                                                                                                print_r($value['comments']);
                                                                                                                                                                                                            }  ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                    <input type="hidden" name="vc" value="<?= $value['visit_code'] ?>">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <input type="submit" name="add_visit" class="btn btn-warning" value="Save">
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                <?php
                                                $x++;
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>Day.</th>
                                                <th>Date.</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>SEX</th>
                                                <th>AGE</th>
                                                <th>STUDY</th>
                                                <th>Phone</th>
                                                <th>STATUS</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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