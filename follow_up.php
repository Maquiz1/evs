<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

$successMessage = null;
$pageError = null;
$errorMessage = null;
$numRec = 15;
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
                        'study_id' => Input::get('study_id'),
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
                        <div class="col-sm-6">
                            <h1>DataTables</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">DataTables</li>
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
                                    <h3 class="card-title">DataTable with default features</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="2%">#</th>
                                                <th width="8%">Visit Name</th>
                                                <th width="3%">Visit Code</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Expected Date</th>
                                                <th width="10%">Visit Date</th>
                                                <th width="5%">Status</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            foreach ($override->get('visit', 'client_id', $_GET['cid']) as $visit) {
                                                $crf1 = $override->get1('crf1', 'patient_id', $_GET['cid'], 'vcode', $visit['visit_code'])[0];
                                                $sc = $override->get('screening', 'client_id', $_GET['cid'])[0];
                                                $cntV = $override->getCount('visit', 'client_id', $visit['client_id']);
                                                $client = $override->get('clients', 'id', $_GET['cid'])[0];
                                                $study_id = $override->get1('clients', 'id', $_GET['cid'], 'status', 1)[0];

                                                $visit_status = 0;
                                                if ($visit['visit_status']) {
                                                }


                                                // print_r($visit['visit_status']);
                                                if ($visit['status'] == 0) {
                                                    $btnV = 'Add';
                                                } elseif ($visit['status'] == 1) {
                                                    $btnV = 'Edit';
                                                    // if ($x == 1) {
                                                    //     $btnV = 'Add';
                                                    // }
                                                }
                                                if ($sc) {
                                                    $btnS = 'Edit';
                                                } else {
                                                    $btnS = 'Add';
                                                }
                                                if ($lb) {
                                                    $btnL = 'Edit';
                                                } else {
                                                    $btnL = 'Add';
                                                }
                                                if ($visit['visit_code'] == 'D1') {
                                                    $v_typ = 'Enrollment';
                                                } elseif ($visit['visit_code'] == 'END') {
                                                    $v_typ = 'END STUDY';
                                                } else {
                                                    $v_typ = 'Follow Up';
                                                }

                                                if ($x == 1 || ($x > 1 && $sc['eligibility'] == 1 && $lb['eligibility'] == 1)) {
                                                }

                                            ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td> <?= $visit['visit_name'] ?></td>
                                                    <td> <?= $visit['visit_code'] ?></td>
                                                    <td> <?= $v_typ ?></td>
                                                    <td> <?= $visit['expected_date'] ?></td>
                                                    <td> <?= $visit['visit_date'] ?></td>
                                                    <td>
                                                        <?php if ($visit['status'] == 1) { ?>
                                                            <a href="#" role="button" class="btn btn-success">Done</a>
                                                        <?php } elseif ($visit['status'] == 0) { ?>
                                                            <a href="#" role="button" class="btn btn-warning">Pending</a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($visit['seq_no'] >= 0) { ?>
                                                            <?php if ($btnV == 'Add') { ?>
                                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                    <?= $btnV ?>Visit
                                                                </button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                    <?= $btnV ?>Visit
                                                                </button>
                                                            <?php }
                                                            ?>
                                                    </td>
                                                <?php } ?>
                                                </tr>
                                                <div class="modal fade" id="addVisit<?= $visit['id'] ?>">
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
                                                                                <input value="<?php if ($visit['status'] != 0) {
                                                                                                    echo $visit['visit_date'];
                                                                                                } ?>" class="validate[required,custom[date]]" type="text" name="visit_date" id="visit_date" />
                                                                                <span>Example: 2010-12-01</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label>Comments / Remarks / Notes
                                                                                    :
                                                                                </label>
                                                                                <textarea name="comments" id="comments" cols="20%" rows="3" placeholder="Type Comments..." onkeyup="myFunction()" required><?php if ($visit['comments']) {
                                                                                                                                                                                                                print_r($visit['comments']);
                                                                                                                                                                                                            }  ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input type="hidden" name="id" value="<?= $visit['id'] ?>">
                                                                    <input type="hidden" name="vc" value="<?= $visit['visit_code'] ?>">
                                                                    <input type="hidden" name="study_id" value="<?= $study_id['study_id'] ?>">
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
                                                <th width="2%">#</th>
                                                <th width="8%">Visit Name</th>
                                                <th width="3%">Visit Code</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Expected Date</th>
                                                <th width="10%">Visit Date</th>
                                                <th width="5%">Status</th>
                                                <th width="15%">Action</th>
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