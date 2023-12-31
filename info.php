<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$numRec = 3;

$successMessage = null;
$errorM = false;
$errorMessage = null;

if ($user->isLoggedIn()) {
    if (Input::exists('post')) {
        if (Input::get('SelectSensitization')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'select_date' => array(
                    'required' => true,
                ),
                // 'project_id' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                if ($_GET['project_id'] == 0) {
                    $project_id = Input::get('project_id');
                } else {
                    $project_id = $_GET['project_id'];
                }

                if ($project_id) {
                    if (count(Input::get('checkname')) >= 1) {
                        try {
                            $i = 0;
                            foreach (Input::get('checkname') as $value) {
                                if (Input::get('checkname')[$i]) {
                                    $user->updateRecord('clients', array(
                                        'project_id' => $project_id,
                                        'available' => 0,
                                        'sensitization' => 1,
                                    ), $value);

                                    $user->createRecord('sensitization', array(
                                        'select_date' => Input::get('select_date'),
                                        'project_id' => $project_id,
                                        'client_id' => $value,
                                        'status' => 1,
                                        'staff_id' => $user->data()->id,
                                        'create_on' => date('Y-m-d H:i:s'),
                                        'update_id' => $user->data()->id,
                                        'update_on' => date('Y-m-d H:i:s'),
                                        'site_id' => $user->data()->site_id,
                                    ));
                                }
                                $i++;
                            }
                            $successMessage = 'Client Selected Successful';
                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    } else {
                        $errorMessage = 'Please select Project Name First';
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('RemoveSensitization')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'sensitization_date' => array(
                    'required' => true,
                ),
                'project_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (count(Input::get('checkname')) >= 1) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'project_id' => 0,
                                    'available' => 1,
                                    'sensitization' => 0,
                                    'screened' => 2,
                                ), $value);
                            }
                            $i++;
                        }

                        $j = 0;
                        foreach (Input::get('sensitization_id') as $sensitization_id) {

                            if (Input::get('sensitization_id')[$j]) {

                                $user->updateRecord('sensitization', array(
                                    'status' => 0,
                                ), $sensitization_id['id']);
                            }
                            $j++;
                        }
                        $successMessage = 'Client Selection For Sensitization Removed Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_sensitization')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'sensitization_date' => array(
                    'required' => true,
                ),
                'sensitization_no' => array(
                    'required' => true,
                ),
                'eligible' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $sensitization = $override->get4('sensitization', 'status', 1, 'client_id', Input::get('cid'), 'project_id', Input::get('project_id'), 'sensitization_no', Input::get('sensitization_no'));
                if (Input::get('btn') == 'Add') {
                    if (!$sensitization) {
                        try {
                            $user->updateRecord('sensitization', array(
                                'sensitization_date' => Input::get('sensitization_date'),
                                'sensitization_no' => Input::get('sensitization_no'),
                                'eligible' => Input::get('eligible'),
                                'comments' => Input::get('comments'),
                                'update_id' => $user->data()->id,
                                'update_on' => date('Y-m-d H:i:s'),
                            ), Input::get('id'));

                            // $user->updateRecord('clients', array(
                            //     'project_id' => 0,
                            //     'available' => 1,
                            //     'sensitization' => 0,
                            // ), $value);
                            $successMessage = 'Client Screening Added Successful';
                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    } else {
                        $errorMessage = 'Sensitization number Already used Please assign other number';
                    }
                } elseif (Input::get('btn') == 'Update') {
                    $user->updateRecord('sensitization', array(
                        'sensitization_date' => Input::get('sensitization_date'),
                        'sensitization_no' => Input::get('sensitization_no'),
                        'eligible' => Input::get('eligible'),
                        'comments' => Input::get('comments'),
                        'update_id' => $user->data()->id,
                        'update_on' => date('Y-m-d H:i:s'),
                    ), Input::get('id'));
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('SelectScreening')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'screening_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 0,
                                    'sensitization' => 0,
                                    'screening' => 1,
                                ), $value);

                                $sensitization = $override->getNews('sensitization', 'client_id', $value, 'project_id', $_GET['project_id']);

                                $user->updateRecord('sensitization', array(
                                    'screened' => 1,
                                    'update_id' => $user->data()->id,
                                    'update_on' => date('Y-m-d H:i:s'),
                                ), $sensitization[0]['id']);
                            }
                            $i++;
                        }

                        $successMessage = 'Client Selection for Screening Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('RemoveScreening')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'screening_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 1,
                                    'project_id' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                ), $value);

                                $sensitization = $override->getNews('sensitization', 'client_id', $value, 'project_id', $_GET['project_id']);

                                $user->updateRecord('sensitization', array(
                                    'screened' => 0,
                                    'update_id' => $user->data()->id,
                                    'update_on' => date('Y-m-d H:i:s'),
                                ), $sensitization[0]['id']);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Selection for Screening Removed Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {

                $pageError = $validate->errors();
            }
        } elseif (Input::get('add_screening')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'screening_date' => array(
                    'required' => true,
                ),
                'screening_id' => array(
                    'required' => true,
                ),
                'eligible' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $sensitization = $override->get4('screening', 'status', 1, 'client_id', Input::get('cid'), 'project_id', Input::get('project_id'), 'screening_id', Input::get('screening_id'));
                if (Input::get('btn') == 'Add') {
                    if (!$sensitization) {
                        try {
                            $user->updateRecord('screening', array(
                                'screening_date' => Input::get('screening_date'),
                                'screening_id' => Input::get('screening_id'),
                                'eligible' => Input::get('eligible'),
                                'comments' => Input::get('comments'),
                                'update_id' => $user->data()->id,
                                'update_on' => date('Y-m-d H:i:s'),
                            ), Input::get('id'));

                            $successMessage = 'Client Screening Added Successful';
                        } catch (Exception $e) {
                            die($e->getMessage());
                        }
                    } else {
                        $errorMessage = 'Screening number Already used Please assign other number';
                    }
                } elseif (Input::get('btn') == 'Update') {
                    $user->updateRecord('screening', array(
                        'screening_date' => Input::get('screening_date'),
                        'screening_id' => Input::get('screening_id'),
                        'eligible' => Input::get('eligible'),
                        'comments' => Input::get('comments'),
                        'update_id' => $user->data()->id,
                        'update_on' => date('Y-m-d H:i:s'),
                    ), Input::get('id'));
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('SelectEligible')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'eligible_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                    'eligible' => 1,
                                ), $value);

                                // $user->updateRecord('clients', array(
                                //     'available' => Input::get('checkname'),
                                //     'sensitization' => 0,
                                //     'screening' => 1,
                                // ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Selection for Eligible Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('RemoveEligible')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'eligible_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 1,
                                    'project_id' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                    'eligible' => 0,
                                ), $value);

                                // $user->updateRecord('clients', array(
                                //     'available' => Input::get('checkname'),
                                //     'sensitization' => 0,
                                //     'screening' => 1,
                                // ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Removed from Eligible Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('SelectEnrollment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'enrollment_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                    'eligible' => 0,
                                    'enrollment' => 1,
                                ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Selection for Enrollment Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('RemoveEnrollment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'enrollment_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 1,
                                    'project_id' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                    'eligible' => 0,
                                    'enrollment' => 0,
                                ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Enrollment Removed Successful';
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('AddEnrollment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                // 'id' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                // print_r(count(Input::get('checkname')));

                // print_r(Input::get('checkname'));
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 0,
                                    'sensitization' => 0,
                                    'sensitization1' => 0,
                                    'sensitization2' => 0,
                                    'screening' => 0,
                                    'screening1' => 0,
                                    'screening2' => 0,
                                    'enrollment' => 0,
                                    'enrolled' => 1,
                                ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Added for Enrollment Successful';
                        // Redirect::to('info.php?id=1&status=3');
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('EndEnrollment')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                // 'id' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                // print_r(count(Input::get('checkname')));

                // print_r(Input::get('checkname'));
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 0,
                                    'sensitization' => 0,
                                    'sensitization1' => 0,
                                    'sensitization2' => 0,
                                    'screening' => 0,
                                    'screening1' => 0,
                                    'screening2' => 0,
                                    'enrollment' => 0,
                                    'enrolled' => 0,
                                    'locked' => 1,
                                ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client End for Enrollment Successful';
                        // Redirect::to('info.php?id=1&status=3');
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('UnLock')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'unlock_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                if (Input::get('checkname')) {
                    try {
                        $i = 0;
                        foreach (Input::get('checkname') as $value) {
                            if (Input::get('checkname')[$i]) {
                                $user->updateRecord('clients', array(
                                    'available' => 1,
                                    'project_id' => 0,
                                    'sensitization' => 0,
                                    'screening' => 0,
                                    'eligible' => 0,
                                    'enrollment' => 0,
                                    'locked' => 0,
                                ), $value);
                            }
                            $i++;
                        }
                        $successMessage = 'Client Unlocked Successfully';
                        // Redirect::to('info.php?id=1&status=3');
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                } else {
                    $errorMessage = 'Please select ataleast One Patient to Submit';
                }
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('addSchedule')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'enrollment_date' => array(
                    'required' => true,
                ),
                'study_id' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    // $dob_date = date('Y-m-d', strtotime(Input::get('enrollment_date')));
                    $user->updateRecord('clients', array(
                        'locked' => 1,
                    ), Input::get('cid'));
                    if (Input::get('btn') == 'Add') {
                        if (Input::get('project_id') == 1) {
                            $user->generateSchedulePQP(Input::get('study_id'), Input::get('project_id'), Input::get('cid'), Input::get('enrollment_date'), 1, 'c', Input::get('comments'), Input::get('site_id'));
                        } elseif (Input::get('project_id') == 2) {
                            $user->generateScheduleCEPI(Input::get('study_id'), Input::get('project_id'), Input::get('cid'), Input::get('enrollment_date'), 1, 'c', Input::get('comments'), Input::get('site_id'));
                        }
                    } elseif (Input::get('btn') == 'Update') {
                        if (Input::get('project_id') == 1) {
                            $user->updateSchedulePQP(Input::get('study_id'), Input::get('project_id'), Input::get('cid'), Input::get('enrollment_date'), 1, 'u', Input::get('comments'), Input::get('site_id'));
                        } elseif (Input::get('project_id') == 2) {
                            $user->updateScheduleCEPI(Input::get('study_id'), Input::get('project_id'), Input::get('cid'), Input::get('enrollment_date'), Input::get('dose'), 'u', Input::get('comments'), Input::get('site_id'));
                        }
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }

                $successMessage = 'Patient Enrolled Successful';
                Redirect::to('info.php?id=2&cid=' . $_GET['cid']);
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('delete_schedule')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                // 'visit_date' => array(
                //     'required' => true,
                // ),
                // 'visit_status' => array(
                //     'required' => true,
                // ),
            ));
            if ($validate->passed()) {
                try {
                    if (Input::get('project_id') == 1) {
                        $user->deleteSchedulePQP(Input::get('id'), Input::get('project_id'));
                    } elseif (Input::get('project_id') == 2) {
                        $user->deleteScheduleCEPI(Input::get('id'), Input::get('project_id'));
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
                $successMessage = 'Patient Visit Deleted Successful';
            } else {
                $pageError = $validate->errors();
            }
        } elseif (Input::get('addVisit')) {
            $validate = new validate();
            $validate = $validate->check($_POST, array(
                'visit_date' => array(
                    'required' => true,
                ),
                'visit_status' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $visit_date = date('Y-m-d', strtotime(Input::get('visit_date')));
                    $user->updateRecord('visit', array(
                        'visit_date' => $visit_date,
                        'visit_status' => Input::get('visit_status'),
                        'comments' => Input::get('comments'),
                        'update_id' => $user->data()->id,
                        'update_on' => date('Y-m-d H:i:s'),
                    ), Input::get('id'));
                } catch (Exception $e) {
                    die($e->getMessage());
                }
                $successMessage = 'Patient Visit Updated Successful';
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
                    <?php if ($_GET['id'] == 1) { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <?php if ($_GET['status'] == 1) { ?>
                                                List of Registered Clients
                                            <?php } elseif ($_GET['status'] == 2) { ?>
                                                List of Available Clients
                                            <?php } elseif ($_GET['status'] == 3) { ?>
                                                List of Selected Clients for Sensitization on Study
                                                <?=
                                                $override->get('study', 'id', $_GET['project_id'])[0]['name'];
                                                ?>
                                            <?php } elseif ($_GET['status'] == 4) { ?>
                                                List of Selected for Screening on Study
                                                <?=
                                                $override->get('study', 'id', $_GET['project_id'])[0]['name'];
                                                ?>
                                            <?php } elseif ($_GET['status'] == 5) { ?>
                                                List of Eligible Clients on Study
                                                <?=
                                                $override->get('study', 'id', $_GET['project_id'])[0]['name'];
                                                ?>
                                            <?php } elseif ($_GET['status'] == 6) { ?>
                                                List of Enrolled Clients on Study
                                                <?=
                                                $override->get('study', 'id', $_GET['project_id'])[0]['name'];
                                                ?>
                                            <?php } elseif ($_GET['status'] == 7) { ?>
                                                List of Locked Clients on Study
                                                <?=
                                                $override->get('study', 'id', $_GET['project_id'])[0]['name'];
                                                ?>
                                            <?php } ?>
                                            <?php
                                            $pagNum = 0;
                                            if ($_GET['status'] == 1) {
                                                $pagNum = $override->getCount('clients', 'status', 1);
                                            } elseif ($_GET['status'] == 2) {
                                                $pagNum = $override->getCount2Not('clients', 'status', 1, 'available', 1, 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['status'] == 3) {
                                                $pagNum = $override->getCount2('clients', 'status', 1, 'sensitization', 1, 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['status'] == 4) {
                                                $pagNum = $override->getCount2('clients', 'status', 1, 'screening', 1, 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['status'] == 5) {
                                                $pagNum = $override->getCount2('clients', 'status', 1, 'eligible', 1, 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['status'] == 6) {
                                                $pagNum = $override->getCount2('clients', 'status', 1, 'enrollment', 1, 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['status'] == 7) {
                                                $pagNum = $override->getCount2('clients', 'status', 1, 'locked', 1, 'project_id', $_GET['project_id']);
                                            }

                                            $pages = ceil($pagNum / $numRec);
                                            if (!$_GET['page'] || $_GET['page'] == 1) {
                                                $page = 0;
                                            } else {
                                                $page = ($_GET['page'] * $numRec) - $numRec;
                                            }


                                            if ($_GET['status'] == 1) {
                                                $clients = $override->getWithLimit('clients', 'status', 1, $page, $numRec);
                                            } elseif ($_GET['status'] == 2) {
                                                // $project_id = $int = intval($string);
                                                $clients = $override->getCount2NotNews('clients', 'status', 1, 'available', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            } elseif ($_GET['status'] == 3) {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'sensitization', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            } elseif ($_GET['status'] == 4) {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'screening', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            } elseif ($_GET['status'] == 5) {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'eligible', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            } elseif ($_GET['status'] == 6) {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'enrollment', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            } elseif ($_GET['status'] == 7) {
                                                $clients = $override->getWithLimit3('clients', 'status', 1, 'locked', 1, 'project_id', $_GET['project_id'], $page, $numRec);
                                            }
                                            ?>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <form id="validation" method="post">
                                            <?php if ($_GET['status'] == 2) { ?>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="select_date" id="select_date" value="" />
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($_GET['project_id'] == 0) {
                                                    ?>
                                                        <div class="col-sm-2">
                                                            <!-- text input -->
                                                            <div class="form-group">
                                                                <label>Study </label>
                                                                <select name="project_id" class="form-control">
                                                                    <option value="">Select</option>
                                                                    <?php foreach ($override->getData2('study', 'status', 1) as $study) { ?>
                                                                        <option value=" <?= $study['id'] ?>"><?= $study['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Submit </label>
                                                            <input type="submit" class="form-control btn btn-info btn-clean" name="SelectSensitization" value="Select for Sensitization">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Discard </label>
                                                            <input type="submit" class="form-control btn btn-warning btn-clean" name="RemoveSensitization" value="Remove">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($_GET['status'] == 3) { ?>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="screening_date" id="screening_date" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Submit </label>
                                                            <input type="submit" class="form-control btn btn-info btn-clean" name="SelectScreening" value="Select for Screening">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Discard </label>
                                                            <input type="submit" class="form-control btn btn-warning btn-clean" name="RemoveScreening" value="Remove">
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } elseif ($_GET['status'] == 4) { ?>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="eligible_date" id="eligible_date" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Submit </label>
                                                            <input type="submit" class="form-control btn btn-info btn-clean" name="SelectEligible" value="Select Eligible List">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Discard </label>
                                                            <input type="submit" class="form-control btn btn-warning btn-clean" name="RemoveEligible" value="Remove">
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } elseif ($_GET['status'] == 5) { ?>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="enrollment_date" id="enrollment_date" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Submit </label>
                                                            <input type="submit" class="btn btn-info btn-clean" name="SelectEnrollment" value="Select for Enrollment">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Discard </label>
                                                            <input type="submit" class="form-control btn btn-warning btn-clean" name="RemoveEnrollment" value="Remove">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($_GET['status'] == 7) { ?>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input type="date" class="form-control fas fa-calendar input-prefix" name="unlock_date" id="unlock_date" value="" />
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2">
                                                        <div class="form-group">
                                                            <label>Submit </label>
                                                            <input type="submit" class="form-control btn btn-info btn-clean" name="UnLock" value="UnLock Clients">
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <?php if ($_GET['status'] != 1 & $_GET['status'] != 6) { ?>
                                                            <th></th>
                                                        <?php } ?>
                                                        <th>No.</th>
                                                        <?php if ($_GET['status'] == 6) { ?>
                                                            <th>Patient Id</th>
                                                        <?php } ?>
                                                        <th>First Name</th>
                                                        <th>Middle Name</th>
                                                        <th>Last Name</th>
                                                        <th>Sex</th>
                                                        <th>Age</th>
                                                        <?php if ($_GET['status'] != 1 && $_GET['status'] != 2) { ?>
                                                            <th>Study</th>
                                                        <?php } ?>
                                                        <th>Phone</th>
                                                        <?php if ($_GET['status'] == 1 || $_GET['status'] == 3 || $_GET['status'] == 4) { ?>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        <?php } ?>
                                                        <?php if ($_GET['status'] == 6) { ?>
                                                            <th>Schedules</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $x = 1;
                                                    foreach ($clients as $value) {
                                                        $dob = $value['dob'];
                                                        $age = $user->dateDiffYears(date('Y-m-d'), $dob);
                                                        $project_name = $override->get('study', 'id', $value['project_id'])[0];
                                                        $client = $override->get('clients', 'id', $value['client_id'])[0];
                                                        $sensitization = $override->getNews('sensitization', 'client_id', $value['id'], 'project_id', $value['project_id'])[0];
                                                        $screening = $override->getNews('screening', 'client_id', $value['id'], 'project_id', $value['project_id'])[0];
                                                        $enrollment = $override->getNews('enrollment', 'client_id', $value['id'], 'project_id', $value['project_id'])[0];
                                                        // $schedule = $override->getNews('clients', 'client_id', $value['id'], 'project_name', $value['project_id'])[0];
                                                        $study_id = $override->getlastRow1('visit', 'patient_id', $value['id'], 'project_id', $value['project_id'], 'id')[0];
                                                    ?>
                                                        <tr>
                                                            <?php if ($_GET['status'] != 1 & $_GET['status'] != 6) { ?>
                                                                <td>
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="hidden" name="id[]" value="<?= $value['id']; ?>">
                                                                        <input type="hidden" name="sensitization_id[]" value="<?= $sensitization['id']; ?>">
                                                                        <input type="hidden" name="screening_id[]" value="<?= $screening['id']; ?>">
                                                                        <input type="hidden" name="enrollment_id[]" value="<?= $enrollment['id']; ?>">
                                                                        <input type="checkbox" name="checkname[]" value="<?= $value['id']; ?>">
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                            <td><?= $x; ?></td>
                                                            <?php if ($_GET['status'] == 6) { ?>
                                                                <td><?= $study_id['study_id']; ?></td>
                                                            <?php } ?>
                                                            <td><?= $value['fname']; ?></td>
                                                            <td><?= $value['mname']; ?></td>
                                                            <td><?= $value['lname']; ?></td>

                                                            <?php if ($value['gender'] == 1) { ?>
                                                                <td>Male</td>
                                                            <?php } elseif ($value['gender'] == 2) { ?>
                                                                <td>Female</td>
                                                            <?php } ?>
                                                            <td><?= $age; ?></td>
                                                            <?php if ($_GET['status'] != 1 && $_GET['status'] != 2) { ?>
                                                                <td><?= $project_name['name']; ?></td>
                                                            <?php } ?>
                                                            <td><?= $value['phone1']; ?></td>
                                                            <?php if ($_GET['status'] == 1) { ?>
                                                                <?php if ($value['status'] == 1) { ?>
                                                                    <td>Active</td>
                                                                <?php } else { ?>
                                                                    <td>Not Active</td>
                                                                <?php } ?>
                                                                <td>
                                                                    <div class=" btn-group btn-group-xs"><a href="add.php?id=1&cid=<?= $value['id'] ?>&btn=Edit" class="btn btn-primary btn-clean"><span class="icon-eye-open"></span> Edit</a>
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if ($_GET['status'] == 3) { ?>
                                                                <?php if ($sensitization['eligible'] == 1) {
                                                                    $btn = 'Update';
                                                                    $btnClass = 'btn btn-success'; ?>
                                                                    <td>Eligible for Screening</td>
                                                                <?php } elseif ($sensitization['eligible'] == 2) {
                                                                    $btn = 'Update';
                                                                    $btnClass = 'btn btn-warning'; ?>
                                                                    <td>Not Eligible for Screening</td>
                                                                <?php } else {
                                                                    $btn = 'Add';
                                                                    $btnClass = 'btn btn-default'; ?>
                                                                    <td>Pending</td>
                                                                <?php } ?>
                                                                <td>
                                                                    <button type="button" class="<?= $btnClass; ?>" data-toggle="modal" data-target="#updateSensitization<?= $sensitization['id'] ?>">
                                                                        <?= $btn; ?> Sensitization
                                                                    </button>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if ($_GET['status'] == 4) { ?>
                                                                <?php if ($screening['eligible'] == 1) {
                                                                    $btn = 'Update';
                                                                    $btnClass = 'btn btn-success'; ?>
                                                                    <td>Eligible for Enrollment</td>
                                                                <?php } elseif ($screening['eligible'] == 2) {
                                                                    $btn = 'Update';
                                                                    $btnClass = 'btn btn-warning'; ?>
                                                                    <td>Not Eligible for Enrollment</td>
                                                                <?php } else {
                                                                    $btn = 'Add';
                                                                    $btnClass = 'btn btn-default'; ?>
                                                                    <td>Pending</td>
                                                                <?php } ?>
                                                                <td>
                                                                    <button type="button" class="<?= $btnClass; ?>" data-toggle="modal" data-target="#updateScreening<?= $screening['id'] ?>">
                                                                        <?= $btn; ?> Screening
                                                                    </button>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if ($_GET['status'] == 6) { ?>
                                                                <td>
                                                                    <?php if ($value['schedule'] == 1) { ?>
                                                                        <div class="btn-group btn-group-xs"><a href="info.php?id=3&cid=<?= $value['id'] ?>&project_id=<?= $value['project_id'] ?>&site_id=<?= $value['site_id'] ?>&btn=Update" class="btn btn-primary btn-clean"><span class="icon-eye-open"></span> Update</a> </div>

                                                                    <?php } else { ?>
                                                                        <div class="btn-group btn-group-xs"><a href="info.php?id=3&cid=<?= $value['id'] ?>&project_id=<?= $value['project_id'] ?>&site_id=<?= $value['site_id'] ?>&btn=Add" class="btn btn-default btn-clean"><span class="icon-eye-open"></span> Add</a> </div>

                                                                    <?php } ?>
                                                                    <div class="btn-group btn-group-xs"><a href="info.php?id=2&cid=<?= $value['id'] ?>" class="btn btn-primary btn-clean"><span class="icon-eye-open"></span> View</a>
                                                                    </div>
                                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteSchedule<?= $value['id'] ?>">
                                                                        Delete Visit
                                                                    </button>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                        <div class="modal fade" id="deleteSchedule<?= $value['id'] ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form id="validation" method="post">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Delete Schedule</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-8">
                                                                                    Are you sure you want to delete this schedule?
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <input type="hidden" name="id" value="<?= $value['id'] ?>">
                                                                            <input type="hidden" name="project_id" value="<?= $value['project_id'] ?>">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <input type="submit" name="delete_schedule" class="btn btn-warning" value="Delete">
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                        </div>
                                                        <div class="modal fade" id="updateSensitization<?= $sensitization['id'] ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title"><?= $btn; ?> Sensitization</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="container-fluid">
                                                                            <div class="row">
                                                                                <!-- general form elements disabled -->
                                                                                <div class="card card-warning">
                                                                                    <div class="card-header">
                                                                                        <h3 class="card-title">Sensitization for <?= $sensitization['client_id'] ?></h3>
                                                                                    </div>
                                                                                    <!-- /.card-header -->
                                                                                    <div class="card-body">
                                                                                        <form id="validation" method="post">
                                                                                            <div class="row">
                                                                                                <div class="col-sm-6">
                                                                                                    <!-- text input -->
                                                                                                    <div class="form-group">
                                                                                                        <label>Date</label>
                                                                                                        <input type="date" class="form-control" name='sensitization_date' placeholder="Enter sensitization_date..." value="<?php if ($sensitization['sensitization_date']) {
                                                                                                                                                                                                                                print_r($sensitization['sensitization_date']);
                                                                                                                                                                                                                            }  ?>" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class=" col-sm-6">
                                                                                                    <div class="form-group">
                                                                                                        <label>Sensitization Number</label>
                                                                                                        <input type="number" class="form-control" placeholder="Enter sensitization_number..." name='sensitization_no' value="<?php if ($sensitization['sensitization_no']) {
                                                                                                                                                                                                                                    print_r($sensitization['sensitization_no']);
                                                                                                                                                                                                                                }  ?>" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">
                                                                                                <div class="col-sm-6">
                                                                                                    <div class="form-group">
                                                                                                        <label>Eligible for Screening ?</label>
                                                                                                        <select name="eligible" style="width: 100%;">
                                                                                                            <option value="<?= $sensitization['eligible'] ?>"><?php if ($sensitization) {
                                                                                                                                                                    if ($sensitization['eligible'] == 1) {
                                                                                                                                                                        echo 'Yes';
                                                                                                                                                                    } elseif ($sensitization['eligible'] == 2) {
                                                                                                                                                                        echo 'No';
                                                                                                                                                                    }
                                                                                                                                                                } else {
                                                                                                                                                                    echo 'Select';
                                                                                                                                                                } ?></option>
                                                                                                            <option value="1">Yes</option>
                                                                                                            <option value="2">No</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-6">
                                                                                                    <!-- textarea -->
                                                                                                    <div class="form-group">
                                                                                                        <label>Comments / Remarks </label>
                                                                                                        <textarea class="form-control" rows="3" placeholder="Enter ..." name='comments' value=''>
                                                                                                            <?php if ($sensitization['comments']) {
                                                                                                                print_r($sensitization['comments']);
                                                                                                            }  ?>
                                                                                                        </textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer justify-content-between">
                                                                                                <input type="hidden" name="id" value="<?= $sensitization['id'] ?>">
                                                                                                <input type="hidden" name="cid" value="<?= $sensitization['client_id'] ?>">
                                                                                                <input type="hidden" name="project_id" value="<?= $sensitization['project_id'] ?>">
                                                                                                <input type="hidden" name="btn" value="<?= $btn ?>">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <input type="submit" name="add_sensitization" value="<?= $btn; ?>">
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                    <!-- /.card-body -->
                                                                                </div>
                                                                                <!-- /.card-body -->
                                                                            </div>
                                                                            <!-- /.row -->
                                                                        </div>
                                                                        <!-- /.container-fluid -->
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>
                                                        </div>

                                                        <!-- /.modal -->
                                                        <div class="modal fade" id="updateScreening<?= $screening['id'] ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title"><?= $btn; ?> Screening</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="container-fluid">
                                                                            <div class="row">
                                                                                <!-- general form elements disabled -->
                                                                                <div class="card card-warning">
                                                                                    <div class="card-header">
                                                                                        <h3 class="card-title">Screening for <?= $screening['client_id'] ?></h3>
                                                                                    </div>
                                                                                    <!-- /.card-header -->
                                                                                    <div class="card-body">
                                                                                        <form id="validation" method="post">
                                                                                            <div class="row">
                                                                                                <div class="col-sm-6">
                                                                                                    <!-- text input -->
                                                                                                    <div class="form-group">
                                                                                                        <label>Date</label>
                                                                                                        <input type="date" class="form-control" name='screening_date' placeholder="Enter screening_date..." value="<?php if ($screening['screening_date']) {
                                                                                                                                                                                                                        print_r($screening['screening_date']);
                                                                                                                                                                                                                    }  ?>" required>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class=" col-sm-6">
                                                                                                    <div class="form-group">
                                                                                                        <label>Screening ID</label>
                                                                                                        <input type="text" class="form-control" placeholder="Enter screening_id..." name='screening_id' value="<?php if ($screening['screening_id']) {
                                                                                                                                                                                                                    print_r($screening['screening_id']);
                                                                                                                                                                                                                }  ?>">
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">
                                                                                                <div class="col-sm-6">
                                                                                                    <div class="form-group">
                                                                                                        <label>Eligible for Screening ?</label>
                                                                                                        <select name="eligible" style="width: 100%;">
                                                                                                            <option value="<?= $screening['eligible'] ?>"><?php if ($screening) {
                                                                                                                                                                if ($screening['eligible'] == 1) {
                                                                                                                                                                    echo 'Yes';
                                                                                                                                                                } elseif ($screening['eligible'] == 2) {
                                                                                                                                                                    echo 'No';
                                                                                                                                                                }
                                                                                                                                                            } else {
                                                                                                                                                                echo 'Select';
                                                                                                                                                            } ?></option>
                                                                                                            <option value="1">Yes</option>
                                                                                                            <option value="2">No</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-6">
                                                                                                    <!-- textarea -->
                                                                                                    <div class="form-group">
                                                                                                        <label>Comments / Remarks </label>
                                                                                                        <textarea class="form-control" rows="3" placeholder="Enter ..." name='comments' value=''>
                                                                                                            <?php if ($screening['comments']) {
                                                                                                                print_r($screening['comments']);
                                                                                                            }  ?>
                                                                                                        </textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer justify-content-between">
                                                                                                <input type="hidden" name="id" value="<?= $screening['id'] ?>">
                                                                                                <input type="hidden" name="cid" value="<?= $screening['client_id'] ?>">
                                                                                                <input type="hidden" name="project_id" value="<?= $screening['project_id'] ?>">
                                                                                                <input type="hidden" name="btn" value="<?= $btn ?>">
                                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                <input type="submit" name="add_screening" value="<?= $btn; ?>">
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                    <!-- /.card-body -->
                                                                                </div>
                                                                                <!-- /.card-body -->
                                                                            </div>
                                                                            <!-- /.row -->
                                                                        </div>
                                                                        <!-- /.container-fluid -->
                                                                    </div>
                                                                    <!-- /.modal-content -->
                                                                </div>
                                                                <!-- /.modal-dialog -->
                                                            </div>
                                                        </div>

                                                        <!-- /.modal -->
                                                    <?php
                                                        $x++;
                                                    } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <?php if ($_GET['status'] != 1 & $_GET['status'] != 6) { ?>
                                                            <th></th>
                                                        <?php } ?>
                                                        <th>No.</th>
                                                        <?php if ($_GET['status'] == 6) { ?>
                                                            <th>Patient Id</th>
                                                        <?php } ?>
                                                        <th>First Name</th>
                                                        <th>Middle Name</th>
                                                        <th>Last Name</th>
                                                        <th>Sex</th>
                                                        <th>Age</th>
                                                        <?php if ($_GET['status'] != 1 && $_GET['status'] != 2) { ?>
                                                            <th>Study</th>
                                                        <?php } ?>
                                                        <th>Phone</th>
                                                        <?php if ($_GET['status'] == 1 || $_GET['status'] == 3 || $_GET['status'] == 4) { ?>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        <?php } ?>
                                                        <?php if ($_GET['status'] == 6) { ?>
                                                            <th>Schedules</th>
                                                        <?php } ?>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>
                                        <!-- /.modal -->
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    <?php } elseif ($_GET['id'] == 2) { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            List of Visit Schedules
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->

                                    <div class="card-body">
                                        <form method="POST">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="2%">#</th>
                                                        <th width="8%">ID</th>
                                                        <th width="8%">Visit Name</th>
                                                        <th width="10%">Dose / Vaccine</th>
                                                        <th width="10%">Visit Type</th>
                                                        <th width="10%">Day</th>
                                                        <th width="10%">Expected Date</th>
                                                        <th width="10%">Visit Date</th>
                                                        <th width="10%">Remarks</th>
                                                        <th width="3%">Study</th>
                                                        <th width="5%">Status</th>
                                                        <th width="15%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $x = 1;
                                                    foreach ($override->get('visit', 'patient_id', $_GET['cid']) as $visit) {
                                                        $study = $override->get('study', 'id', $visit['project_id'])[0];

                                                        $visit_status = 0;
                                                        if ($visit['visit_status']) {
                                                        }

                                                        if ($visit['visit_status'] == 0) {
                                                            $btnV = 'Add';
                                                        } elseif ($visit['status'] == 1) {
                                                            $btnV = 'Edit';
                                                        } elseif ($visit['status'] == 2) {
                                                            $btnV = 'Edit';
                                                        }
                                                    ?>
                                                        <tr>
                                                            <td><?= $x ?></td>
                                                            <td> <?= $visit['study_id'] ?></td>
                                                            <td> <?= $visit['visit_name'] ?></td>
                                                            <td> <?= $visit['visit_group'] ?></td>
                                                            <td> <?= $visit['visit_type'] ?></td>
                                                            <td> <?= date('l', strtotime($visit['expected_date'])) ?> </td>
                                                            <td> <?= $visit['expected_date'] ?></td>
                                                            <td> <?= $visit['visit_date'] ?></td>
                                                            <td> <?= $visit['comments'] ?></td>
                                                            <td> <?= $study['name'] ?></td>
                                                            <td>
                                                                <?php if ($visit['visit_status'] == 1) { ?>
                                                                    <a href="#" role="button" class="btn btn-success">Done</a>
                                                                <?php } elseif ($visit['visit_status'] == 0) { ?>
                                                                    <a href="#" role="button" class="btn btn-warning">Pending</a>
                                                                <?php } elseif ($visit['visit_status'] == 2) { ?>
                                                                    <a href="#" role="button" class="btn btn-danger">Missed</a>
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($visit['visit_status'] == 1) { ?>
                                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                        <?= $btnV . ' - ' ?>Visit
                                                                    </button>
                                                                <?php } elseif ($visit['visit_status'] == 0) { ?>
                                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                        <?= $btnV . ' - ' ?>Visit
                                                                    </button>
                                                                <?php } elseif ($visit['visit_status'] == 2) { ?>
                                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                        <?= $btnV . ' - ' ?>Visit
                                                                    </button>
                                                                <?php } ?>

                                                            </td>
                                                        </tr>
                                                        <div class="modal fade" id="addVisit<?= $visit['id'] ?>">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <form id="validation" method="post">
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Visit Status</h4>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-sm-8">
                                                                                    <div class="form-group">
                                                                                        <label>Vist Date</label>
                                                                                        <input value="<?php if ($visit['status'] != 0) {
                                                                                                            echo $visit['visit_date'];
                                                                                                        } ?>" class="validate[required,custom[date]]" type="date" name="visit_date" id="visit_date" />
                                                                                    </div>

                                                                                    <div class="col-sm-4">
                                                                                        <div class="row-form clearfix">
                                                                                            <!-- select -->
                                                                                            <div class="form-group">
                                                                                                <label>Visit Status</label>
                                                                                                <select name="visit_status" id="visit_status" style="width: 100%;">
                                                                                                    <option value="<?= $visit['visit_status'] ?>"><?php if ($visit) {
                                                                                                                                                        if ($visit['visit_status'] == 1) {
                                                                                                                                                            echo 'Atended';
                                                                                                                                                        } elseif ($visit['visit_status'] == 2) {
                                                                                                                                                            echo 'Missed';
                                                                                                                                                        }
                                                                                                                                                    } else {
                                                                                                                                                        echo 'Select';
                                                                                                                                                    } ?>
                                                                                                    </option>
                                                                                                    <option value="1">Atended</option>
                                                                                                    <option value="2">Missed</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-6">
                                                                                    <div class="form-group">
                                                                                        <label>Comments / Remarks / Notes
                                                                                            :
                                                                                        </label>
                                                                                        <textarea name="comments" id="comments" cols="20%" rows="3" placeholder="Type Comments..."><?php if ($visit['comments']) {
                                                                                                                                                                                        print_r($visit['comments']);
                                                                                                                                                                                    }  ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer justify-content-between">
                                                                            <input type="hidden" name="id" value="<?= $visit['id'] ?>">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <input type="submit" name="addVisit" class="btn btn-warning" value="Save">
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
                                                        <th width="8%">ID</th>
                                                        <th width="8%">Visit Name</th>
                                                        <th width="10%">Dose / Vaccine</th>
                                                        <th width="10%">Visit Type</th>
                                                        <th width="10%">Day</th>
                                                        <th width="10%">Expected Date</th>
                                                        <th width="10%">Visit Date</th>
                                                        <th width="10%">Remarks</th>
                                                        <th width="3%">Study</th>
                                                        <th width="5%">Status</th>
                                                        <th width="15%">Action</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </form>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    <?php } elseif ($_GET['id'] == 3) { ?>
                        <?php
                        $schedule = $override->getNews('visit', 'patient_id', $_GET['cid'], 'project_id', $_GET['project_id'])[0];
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Add Schedule
                                        </h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <form id="validation" method="post">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Enrollment Date</label>
                                                        <input type="date" class="form-control fas fa-calendar input-prefix" name="enrollment_date" id="enrollment_date" value="<?php if ($schedule['visit_date']) {
                                                                                                                                                                                    print_r($schedule['visit_date']);
                                                                                                                                                                                }  ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Study ID</label>
                                                        <input type="text" class="form-control fas fa-calendar input-prefix" name="study_id" id="study_id" value="<?php if ($schedule['study_id']) {
                                                                                                                                                                        print_r($schedule['study_id']);
                                                                                                                                                                    }  ?>" required />
                                                    </div>
                                                </div>

                                                <?php if ($_GET['btn'] == 'Update' && $_GET['project_id'] == 2) { ?>
                                                    <div class="col-sm-3">
                                                        <!-- text input -->
                                                        <div class="form-group">
                                                            <label>Dose / Vaccine </label>
                                                            <select name="dose" class="form-control">
                                                                <option value="<?= $schedule['visit_group'] ?>"><?php if ($schedule['visit_group'] != 0) {
                                                                                                                    if ($schedule['visit_group'] == 1) {
                                                                                                                        echo '1';
                                                                                                                    } elseif ($schedule['visit_group'] == 2) {
                                                                                                                        echo '2';
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    echo 'Select';
                                                                                                                } ?>
                                                                </option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Comments / Remarks / Notes </label>
                                                        <textarea name="comments" id="comments" cols="20%" rows="3" placeholder="Type Comments...">
                                                        <?php if ($schedule['comments']) {
                                                            print_r($schedule['comments']);
                                                        }  ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="cid" value="<?= $_GET['cid'] ?>" />
                                                    <input type="hidden" name="site_id" value="<?= $_GET['site_id'] ?>" />
                                                    <input type="hidden" name="project_id" value="<?= $_GET['project_id'] ?>" />
                                                    <input type="hidden" name="btn" value="<?= $_GET['btn'] ?>" />
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary" name="addSchedule" value="Save changes" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                </div>
                <!-- /.row -->

            <?php } elseif ($_GET['id'] == 4) { ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    List of Visit Schedules
                                </h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <form method="POST">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="2%">#</th>
                                                <th width="8%">ID</th>
                                                <th width="8%">Visit Name</th>
                                                <th width="10%">Dose / Vaccine</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Day</th>
                                                <th width="10%">Expected Date</th>
                                                <th width="10%">Visit Date</th>
                                                <th width="10%">Remarks</th>
                                                <th width="3%">Study</th>
                                                <th width="5%">Status</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1;
                                            if ($_GET['day'] == 0) {
                                                $visits = $override->getCount3News('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d'), 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['day'] == 1) {
                                                $visits = $override->getCount3News('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days')), 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['day'] == 7) {
                                                $visits = $override->getCount3LessNews('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')), 'project_id', $_GET['project_id']);
                                            } elseif ($_GET['day'] == -1) {
                                                $visits = $override->getCount3LessNews('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 0 days')), 'project_id', $_GET['project_id']);
                                            }
                                            foreach ($visits as $visit) {
                                                $study = $override->get('study', 'id', $visit['project_id'])[0];

                                                $visit_status = 0;
                                                if ($visit['visit_status']) {
                                                }

                                                if ($visit['visit_status'] == 0) {
                                                    $btnV = 'Add';
                                                } elseif ($visit['status'] == 1) {
                                                    $btnV = 'Edit';
                                                } elseif ($visit['status'] == 2) {
                                                    $btnV = 'Edit';
                                                }
                                            ?>
                                                <tr>
                                                    <td><?= $x ?></td>
                                                    <td> <?= $visit['study_id'] ?></td>
                                                    <td> <?= $visit['visit_name'] ?></td>
                                                    <td> <?= $visit['visit_group'] ?></td>
                                                    <td> <?= $visit['visit_type'] ?></td>
                                                    <td> <?= date('l', strtotime($visit['expected_date'])) ?> </td>
                                                    <td> <?= $visit['expected_date'] ?></td>
                                                    <td> <?= $visit['visit_date'] ?></td>
                                                    <td> <?= $visit['comments'] ?></td>
                                                    <td> <?= $study['name'] ?></td>
                                                    <td>
                                                        <?php if ($visit['visit_status'] == 1) { ?>
                                                            <a href="#" role="button" class="btn btn-success">Done</a>
                                                        <?php } elseif ($visit['visit_status'] == 0) { ?>
                                                            <a href="#" role="button" class="btn btn-warning">Pending</a>
                                                        <?php } elseif ($visit['visit_status'] == 2) { ?>
                                                            <a href="#" role="button" class="btn btn-danger">Missed</a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($visit['visit_status'] == 1) { ?>
                                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                <?= $btnV . ' - ' ?>Visit
                                                            </button>
                                                        <?php } elseif ($visit['visit_status'] == 0) { ?>
                                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                <?= $btnV . ' - ' ?>Visit
                                                            </button>
                                                        <?php } elseif ($visit['visit_status'] == 2) { ?>
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#addVisit<?= $visit['id'] ?>">
                                                                <?= $btnV . ' - ' ?>Visit
                                                            </button>
                                                        <?php } ?>

                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="addVisit<?= $visit['id'] ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form id="validation" method="post">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Visit Status</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <div class="form-group">
                                                                                <label>Vist Date</label>
                                                                                <input value="<?php if ($visit['status'] != 0) {
                                                                                                    echo $visit['visit_date'];
                                                                                                } ?>" class="validate[required,custom[date]]" type="date" name="visit_date" id="visit_date" />
                                                                            </div>

                                                                            <div class="col-sm-4">
                                                                                <div class="row-form clearfix">
                                                                                    <!-- select -->
                                                                                    <div class="form-group">
                                                                                        <label>Visit Status</label>
                                                                                        <select name="visit_status" id="visit_status" style="width: 100%;">
                                                                                            <option value="<?= $visit['visit_status'] ?>"><?php if ($visit) {
                                                                                                                                                if ($visit['visit_status'] == 1) {
                                                                                                                                                    echo 'Atended';
                                                                                                                                                } elseif ($visit['visit_status'] == 2) {
                                                                                                                                                    echo 'Missed';
                                                                                                                                                }
                                                                                                                                            } else {
                                                                                                                                                echo 'Select';
                                                                                                                                            } ?>
                                                                                            </option>
                                                                                            <option value="1">Atended</option>
                                                                                            <option value="2">Missed</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-6">
                                                                            <div class="form-group">
                                                                                <label>Comments / Remarks / Notes
                                                                                    :
                                                                                </label>
                                                                                <textarea name="comments" id="comments" cols="20%" rows="3" placeholder="Type Comments..."><?php if ($visit['comments']) {
                                                                                                                                                                                print_r($visit['comments']);
                                                                                                                                                                            }  ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <input type="hidden" name="id" value="<?= $visit['id'] ?>">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <input type="submit" name="addVisit" class="btn btn-warning" value="Save">
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
                                                <th width="8%">ID</th>
                                                <th width="8%">Visit Name</th>
                                                <th width="10%">Dose / Vaccine</th>
                                                <th width="10%">Visit Type</th>
                                                <th width="10%">Day</th>
                                                <th width="10%">Expected Date</th>
                                                <th width="10%">Visit Date</th>
                                                <th width="10%">Remarks</th>
                                                <th width="3%">Study</th>
                                                <th width="5%">Status</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            <?php } ?>
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