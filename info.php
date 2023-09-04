<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$numRec = 15;

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
                                if ($_GET['status'] == 1) {
                                    $pagNum = $override->countData('clients', 'status', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 2) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'sensitization1', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 3) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'screening1', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 4) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 5) {
                                    $pagNum = $override->countData2('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id);
                                } elseif ($_GET['status'] == 6) {
                                    $pagNum = $override->getNoAvailable('progres', 'status', 1, 'pt_status', 0, 'pt_status', 2);
                                } elseif ($_GET['status'] == 7) {
                                    $pagNum = $override->countData('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                                }

                                $pages = ceil($pagNum / $numRec);
                                if (!$_GET['page'] || $_GET['page'] == 1) {
                                    $page = 0;
                                } else {
                                    $page = ($_GET['page'] * $numRec) - $numRec;
                                }


                                if ($_GET['status'] == 1) {
                                    $clients = $override->getWithLimit1('clients', 'status', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 2) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'sensitization1', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 3) {

                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'screening1', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 4) {

                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 5) {
                                    $clients = $override->getWithLimit3('clients', 'status', 1, 'enrolled', 1, 'site_id', $user->data()->site_id, $page, $numRec);
                                } elseif ($_GET['status'] == 6) {
                                    $clients = $override->getAvailable('progres', 'status', 1, 'pt_status', 0, 'pt_status', 2);
                                } elseif ($_GET['status'] == 7) {

                                    $clients = $override->getWithLimit1('clients', 'status', 0, 'site_id', $user->data()->site_id, $page, $numRec);
                                }
                                ?>

                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>SEX</th>
                                                <th>AGE</th>
                                                <?php if ($_GET['status'] == 2 || $_GET['status'] == 3 || $_GET['status'] == 4 || $_GET['status'] == 5) { ?>
                                                    <th>STUDY</th>
                                                <?php } ?>

                                                <?php if ($_GET['status'] == 3) { ?>
                                                    <th>Screening 1</th>
                                                <?php } ?>

                                                <?php if ($_GET['status'] == 3) { ?>
                                                    <th>Screening 2</th>
                                                <?php } ?>
                                                <th>Phone</th>
                                                <th>STATUS</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 1;
                                            foreach ($clients as $value) {
                                                $dob = $value['dob'];
                                                $age = $user->dateDiffYears(date('Y-m-d'), $dob);
                                                $project_name = $override->get('study', 'id', $value['project_name'])[0];
                                                $client = $override->get('clients', 'id', $value['client_id'])[0];

                                            ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $client['fname']; ?></td>
                                                    <td><?= $client['mname']; ?></td>
                                                    <td><?= $client['lname']; ?></td>

                                                    <?php if ($client['gender'] == 1) { ?>
                                                        <td>Male</td>
                                                    <?php } elseif ($client['gender'] == 2) { ?>
                                                        <td>Female</td>
                                                    <?php } ?>

                                                    <td><?= $age; ?></td>
                                                    <?php if ($_GET['status'] == 2 || $_GET['status'] == 3 || $_GET['status'] == 4 || $_GET['status'] == 5) { ?>
                                                        <td><?= $project_name['name']; ?></td>
                                                    <?php } ?>

                                                    <td><?= $client['phone1']; ?></td>

                                                    <?php if ($_GET['status'] == 1 || $_GET['status'] == 6) { ?>
                                                        <td>Active</td>
                                                    <?php } else { ?>
                                                        <td>Not Active</td>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 3) { ?>
                                                        <?php if ($value['screening2'] == 1) { ?>
                                                            <td>Done</td>
                                                        <?php } elseif ($value['screening2'] == 2) { ?>
                                                            <td>Not Done</td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 4) { ?>
                                                        <?php if ($value['enrolled'] == 1) { ?>
                                                            <td>Enrolled</td>
                                                        <?php } elseif ($value['enrolled'] == 2) { ?>
                                                            <td>Not Enrolled</td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <td>
                                                        <?php
                                                        if ($_GET['status'] == 1) { ?>
                                                            <div class="btn-group btn-group-xs"><a href="add.php?id=1&cid=<?= $value['id'] ?>&btn=update_client" class="btn btn-default btn-clean"><span class="icon-eye-open"></span> Edit</a></div>
                                                        <?php }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 2) {

                                                            if ($value['screened'] != 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization2.php?id=1&cid=<?= $value['id'] ?>&btn=update_sensitize" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Complete Sensitization</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization2.php?id=1&cid=<?= $value['id'] ?>&btn=update_sensitize" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Sensitization</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 3) {

                                                            if ($value['screening1'] == 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=update_screening" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Screening 1</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=add_screening" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Screening 1</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 3) {

                                                            if ($value['screening2'] == 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening2.php?id=1&cid=<?= $value['id'] ?>&btn=update_screening" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Screening 2</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening2.php?id=1&cid=<?= $value['id'] ?>&btn=update_screening" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Screening 2</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 4) {

                                                            if ($value['enrolled'] >= 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_enrollment.php?id=1&cid=<?= $value['id'] ?>&btn=update_enrollment" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Enrollment</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_enrollment.php?id=1&cid=<?= $value['id'] ?>&btn=add_enrollment" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Enrollment</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 5) {

                                                            if ($value['enrolled'] >= 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="follow_up.php?id=1&cid=<?= $value['id'] ?>&btn=update_enrollment" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Schedule</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="follow_up.php?id=1&cid=<?= $value['id'] ?>&btn=add_enrollment" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Schedule</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>



                                                        <?php
                                                        if ($_GET['status'] == 6) {

                                                            if ($value['sensitization1'] != 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization.php?id=1&cid=<?= $value['id'] ?>&btn=add_sensitize" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Sensitization</a></div>

                                                            <?php
                                                            } elseif($value['sensitization2'] == 1) {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization.php?id=1&cid=<?= $value['id'] ?>&btn=update_sensitize" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Sensitization</a></div>

                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                                $x++;
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No.</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>SEX</th>
                                                <th>AGE</th>
                                                <?php if ($_GET['status'] == 2 || $_GET['status'] == 3 || $_GET['status'] == 4 || $_GET['status'] == 5) { ?>
                                                    <th>STUDY</th>
                                                <?php } ?>

                                                <?php if ($_GET['status'] == 3) { ?>
                                                    <th>Screening 1</th>
                                                <?php } ?>

                                                <?php if ($_GET['status'] == 3) { ?>
                                                    <th>Screening 2</th>
                                                <?php } ?>
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