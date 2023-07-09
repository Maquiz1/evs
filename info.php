<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
// $pageError = null;
// $successMessage = null;
// $errorM = false;
// $errorMessage = null;
// $t_crf = 0;
// $p_crf = 0;
// $w_crf = 0;
// $s_name = null;
// $c_name = null;
// $site = null;
// $country = null;
// $study_crf = null;
// $data_limit = 10000;

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
                            <h1>Volunteer Database ( KINGANI CLINICAL TRIAL FACILITY )</h1>
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
                                        <?php if ($_GET['status'] == 2) { ?>
                                            List of Registered Clients
                                        <?php } elseif ($_GET['status'] == 2) { ?>
                                            List of Sensitized Clients

                                        <?php } elseif ($_GET['status'] == 2) { ?>
                                            List of SCreened Clients

                                        <?php } elseif ($_GET['status'] == 2) { ?>
                                            List of Eligible Clients

                                        <?php } elseif ($_GET['status'] == 2) { ?>
                                            List of Enrolled Clients

                                        <?php } ?>
                                </div>
                                <!-- /.card-header -->
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
                                                <th>STATUS</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 1;
                                            foreach ($override->getData('clients') as $value) {
                                                // $client = $override->get('clients', 'id', $value['client_id']);
                                                // $group = $override->get('patient_group', 'id', $client[0]['pt_group'])[0]['name'];
                                            ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $value['fname']; ?></td>
                                                    <td><?= $value['mname']; ?></td>
                                                    <td><?= $value['lname']; ?></td>
                                                    <?php if ($value['gender'] == 1) { ?>
                                                        <td>Male</td>
                                                    <?php } elseif ($value['gender'] == 2) { ?>
                                                        <td>Female</td>
                                                    <?php } ?>
                                                    <td><?= $value['dob']; ?></td>

                                                    <?php if ($_GET['status'] == 1) { ?>
                                                        <?php if ($value['sensitization'] == 1) { ?>
                                                            <td>
                                                                <div class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Sensitized</div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <div class="btn btn-danger btn-clean"><span class="icon-eye-open"></span> Not Sensitized</div>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 2) { ?>
                                                        <?php if ($value['screened'] == 1) { ?>
                                                            <td>
                                                                <div class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Screened</div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <div class="btn btn-danger btn-clean"><span class="icon-eye-open"></span> Not Screened</div>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 3) { ?>
                                                        <?php if ($value['eligible'] == 1) { ?>
                                                            <td>
                                                                <div class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Eligible</div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <div class="btn btn-danger btn-clean"><span class="icon-eye-open"></span> Not Eligible</div>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 4) { ?>
                                                        <?php if ($value['enrolled'] == 1) { ?>
                                                            <td>
                                                                <div class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Enrolled</div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <div class="btn btn-danger btn-clean"><span class="icon-eye-open"></span> Not Enrolled</div>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <?php if ($_GET['status'] == 5) { ?>
                                                        <?php if ($value['end_study'] == 1) { ?>
                                                            <td>
                                                                <div class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Active</div>
                                                            </td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <div class="btn btn-danger btn-clean"><span class="icon-eye-open"></span> Not Active</div>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>

                                                    <td>
                                                        <div class="btn-group btn-group-xs"><a href="add.php?id=1&cid=<?= $value['id'] ?>&btn=view" class="btn btn-default btn-clean"><span class="icon-eye-open"></span> View</a></div>
                                                        <div class="btn-group btn-group-xs"><a href="add.php?id=1&cid=<?= $value['id'] ?>&btn=edit" class="btn btn-info btn-clean"><span class="icon-eye-open"></span> Edit</a></div>
                                                        <?php
                                                        if ($_GET['status'] == 1) {
                                                            if ($value['sensitization1'] == 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization.php?id=1&cid=<?= $value['id'] ?>&btn=update_sensitize" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Sensitization</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_sensitization.php?id=1&cid=<?= $value['id'] ?>&btn=add_sensitize" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Sensitization</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 2) {

                                                            if ($value['screening1'] == 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=update_screening" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Screening</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=add_screening" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Screening</a></div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($_GET['status'] == 3) {

                                                            if ($value['eligible'] == 1) {
                                                        ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=update_screening" class="btn btn-success btn-clean"><span class="icon-eye-open"></span> Update Screening</a></div>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <div class="btn-group btn-group-xs"><a href="add_screening.php?id=1&cid=<?= $value['id'] ?>&btn=add_screening" class="btn btn-warning btn-clean"><span class="icon-eye-open"></span> Add Screening</a></div>
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
    <script src="dist/js/demo.js"></script>
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