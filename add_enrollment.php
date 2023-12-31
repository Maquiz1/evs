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
        if ($_GET['btn'] == 'add_enrollment') {
            $validate = $validate->check($_POST, array(
                'enrollment_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $client_study = $override->getNews('clients', 'id', $_GET['cid'], 'status', 1)[0];
                $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
                $schedule = $override->getData('schedule')[0];

                // $screening_id = $override->getNews('screening', 'client_id', Input::get('id'), 'status', 1)[0];
                // $lab_id = $override->getNews('lab', 'client_id', Input::get('id'), 'status', 1)[0];
                if (!$client_study['study_id']) {
                    $study_id = $std_id['study_id'];
                } else {
                    $study_id = $client_study['study_id'];
                }
                if (!$override->get('visit', 'client_id', $_GET['cid'])) {
                    $user->createRecord('visit', array(
                        'project_name' => Input::get('project_name'),
                        'visit_name' => 'Vaccination 1',
                        'visit_code' => 'D1',
                        'study_id' => $study_id,
                        'expected_date' => Input::get('enrollment_date'),
                        'visit_date' => '',
                        'visit_window' => 2,
                        'window1' => $schedule['window1'],
                        'window2' => $schedule['window2'],
                        'status' => 0,
                        'client_id' => $_GET['cid'],
                        'created_on' => date('Y-m-d'),
                        'seq_no' => 0,
                        'redcap' => 0,
                        'comments' => Input::get('comments'),
                        'site_id' => $user->data()->site_id,
                        'staff_id' => $user->data()->id,
                        'visit_status' => 0
                    ));

                    $user->updateRecord('clients',array(
                            'enrolled' => Input::get('enrollment')
                        ),$_GET['cid']);
                }

                if ($override->getCount('visit', 'client_id', $_GET['cid']) == 1) {
                    try {
                        if (!$client_study['study_id']) {
                            $user->visit3($_GET['cid'], 1, $std_id['study_id'], $user->data()->site_id, Input::get('project_name'), $user->data()->id);
                            $user->updateRecord('study_id', array('status' => 1, 'client_id' => $_GET['cid']), $std_id['id']);
                            $user->updateRecord('clients', array('study_id' => $std_id['study_id']), $_GET['cid']);
                        } else {
                            $user->visit3($_GET['cid'], 1, $client_study['study_id'], $user->data()->site_id, Input::get('project_name'), $user->data()->id);
                        }
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }
                // if (!$client_study['study_id']) {
                //     $user->updateRecord('screening', array('study_id' => $std_id['study_id']), $screening_id['id']);
                //     $user->updateRecord('lab', array('study_id' => $std_id['study_id']), $lab_id['id']);
                // } else {
                //     $user->updateRecord('screening', array('study_id' => $client_study['study_id']), $screening_id['id']);
                //     $user->updateRecord('lab', array('study_id' => $client_study['study_id']), $lab_id['id']);
                // }
                $successMessage = 'Enrollment  Added Successful';
                // Redirect::to('info.php?id=3&status=2');
            } else {
                $pageError = $validate->errors();
            }
        } elseif ($_GET['btn'] == 'update_enrollment') {
            $validate = $validate->check($_POST, array(
                'enrollment_date' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                $std_id = $override->getNews('study_id', 'site_id', $user->data()->site_id, 'status', 0)[0];
                $visit_date = $override->firstRow('visit', 'visit_date', 'id', 'client_id', $_GET['cid'])[0];
                if ($override->get('visit', 'client_id', $_GET['cid'])) {
                    if (Input::get('enrollment_date') != $visit_date['visit_date']) {
                        $user->deleteRecord('visit', 'client_id', $_GET['cid']);
                        $user->createRecord('visit', array(
                            'project_name' => Input::get('project_name'),
                            'visit_name' => 'Day 1',
                            'visit_code' => 'D1',
                            'study_id' => $study_id,
                            'expected_date' => Input::get('enrollment_date'),
                            'visit_date' => '',
                            'visit_window' => 2,
                            'window1' => $schedule['window1'],
                            'window2' => $schedule['window2'],
                            'status' => 0,
                            'client_id' => $_GET['cid'],
                            'created_on' => date('Y-m-d'),
                            'seq_no' => 1,
                            'redcap' => 0,
                            'comments' => Input::get('comments'),
                            'site_id' => $user->data()->site_id,
                            'staff_id' => $user->data()->id,
                            'visit_status' => 0
                        ));
                    }

                    $user->updateRecord('clients', array(
                        'enrolled' => Input::get('enrollment')
                    ), $_GET['cid']);
                }
                if ($override->getCount('visit', 'client_id', $_GET['cid']) == 1) {
                    try {
                        $user->visit3($_GET['cid'], 1, $std_id['study_id'], $user->data()->site_id, Input::get('project_name'), $user->data()->id);
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }
                }
                $user->updateRecord('clients', array('enrolled' => Input::get('enrollment')), $_GET['cid']);
                $successMessage = 'Enrollment  Updated Successful';
                // Redirect::to('info.php?id=3&status=2');
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
                            <h1>Enrollment Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Enrollment Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- <div class="container-fluid"> -->
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
                    <!-- </div> -->
                    <!-- /.container-fluid -->
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <?php
                            $enrollment = $override->get('clients', 'id', $_GET['cid'])[0];
                            $study = $override->firstRow('visit', 'study_id', 'id', 'client_id', $_GET['cid'])[0];
                            $visit_date = $override->firstRow('visit', 'visit_date', 'id', 'client_id', $_GET['cid'])[0];
                            $comments = $override->firstRow('visit', 'comments', 'id', 'client_id', $_GET['cid'])[0];
                            ?>

                            <!-- general form elements disabled -->
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Enrollment Client</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <form id="validation" enctype="multipart/form-data" method="post" autocomplete="off">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Enrollment Done</label>
                                                    <?php
                                                    $value = $override->get('yes_no', 'id', $enrollment['enrolled'])[0]  ?>
                                                    <select id="enrollment" name="enrollment" class="form-control" required>
                                                        <option value="<?= $value['id'] ?>"><?php if ($enrollment['enrolled']) {
                                                                                                print_r($value['name']);
                                                                                            } else {
                                                                                                echo 'select';
                                                                                            }  ?>
                                                        </option>
                                                        <?php foreach ($override->getData('yes_no') as $gender) { ?>
                                                            <option value="<?= $gender['id'] ?>"><?= $gender['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Enrollment Date</label>
                                                    <input type="date" class="form-control fas fa-calendar input-prefix" name="enrollment_date" id="enrollment_date" value="<?php if ($visit_date['visit_date']) {
                                                                                                                                                                                print_r($visit_date['visit_date']);
                                                                                                                                                                            }  ?>" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Study</label>
                                                    <?php
                                                    $value = $override->get('study', 'id', $study['study_id'])[0]  ?>
                                                    <select id="project_name" name="project_name" class="form-control" required>
                                                        <option value="<?= $value['id'] ?>"><?php if ($study['study_id']) {
                                                                                                print_r($value['name']);
                                                                                            } else {
                                                                                                echo 'select';
                                                                                            }  ?>
                                                        </option>
                                                        <?php foreach ($override->getData('study') as $gender) { ?>
                                                            <option value="<?= $gender['id'] ?>"><?= $gender['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Comments / Remarks / Notes
                                                        :
                                                    </label>
                                                    <textarea name="comments" id="comments" cols="64%" rows="3" placeholder="Type Comments..." onkeyup="myFunction()" required><?php if ($comments['comments']) {
                                                                                                                                                                                    print_r($comments['comments']);
                                                                                                                                                                                }  ?></textarea>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>

                                            <input type="hidden" name="cid" value="<?= $_GET['cid']; ?>">
                                            <input type="hidden" name="btn" value="<?php if ($_GET['btn'] == 'update_enrollment') {
                                                                                        echo 'update_enrollment';
                                                                                    } else {
                                                                                        echo 'add_enrollment';
                                                                                    }; ?>">
                                            <?php if ($_GET['btn'] != 'view') { ?>
                                                <input type="submit" name="register" value="<?php if ($_GET['btn'] == 'update_enrollment') {
                                                                                                echo 'Update Client Enrollment Info';
                                                                                            } else {
                                                                                                echo 'Enrollment Client';
                                                                                            } ?>" class="btn btn-success btn-clean">
                                            <?php } ?>
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

        function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        /*An array containing all the country names in the world:*/
        // var countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Anguilla", "Antigua & Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia & Herzegovina", "Botswana", "Brazil", "British Virgin Islands", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central Arfrican Republic", "Chad", "Chile", "China", "Colombia", "Congo", "Cook Islands", "Costa Rica", "Cote D Ivoire", "Croatia", "Cuba", "Curacao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands", "Faroe Islands", "Fiji", "Finland", "France", "French Polynesia", "French West Indies", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauro", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russia", "Rwanda", "Saint Pierre & Miquelon", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "St Kitts & Nevis", "St Lucia", "St Vincent", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor L'Este", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks & Caicos", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Virgin Islands (US)", "Yemen", "Zambia", "Zimbabwe"];
        // var getUid = $(this).val();
        function myFunction() {

            fetch('fetch_firstname.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    // console.log(data);
                    autocomplete(document.getElementById("fname"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });

            fetch('fetch_middlename.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    // console.log(data);
                    autocomplete(document.getElementById("mname"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });


            fetch('fetch_lastname.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    autocomplete(document.getElementById("lname"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });

            fetch('fetch_village.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    autocomplete(document.getElementById("village"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });

            fetch('fetch_hamlet.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    autocomplete(document.getElementById("hamlet"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });

            fetch('fetch_comments.php')
                .then(response => response.json())
                .then(data => {
                    // Process the data received from the PHP script
                    autocomplete(document.getElementById("comments"), data);
                })
                .catch(error => {
                    // Handle any errors that occurred during the fetch request
                    console.error('Error:', error);
                });
        }




        $(document).ready(function() {

            $("#add_crf6").click(function(e) {
                // if ($("#validation")[0].checkValidity()) {
                //   PREVENT PAGE TO REFRESH
                // e.preventDefault();



                // if($("#FDATE").val() == ''){
                //     $("#FDATEError").text('* Date is empty');
                // };
                // if($("#cDATE").val() == ''){
                //     $("#cDATEError").text('* Date is empty');
                // };
                // if($("#cpersid").val() == ''){
                //     $("#cpersidError").text('* NAME is empty');
                // };


                if ($("#renal_urea").val() == '') {
                    $("#renal_ureaError").text('* Renal Urea is empty');
                };

                if ($("#renal_urea_units").val() == '') {
                    $("#renal_urea_unitsError").text('* Renal Urea Units is empty');
                };

                // if ($("#password1").val() != $("#password2").val()) {
                //     $("#passError").text('* Passowrd do not match');
                //     //console.log("Not matched"); 
                //     $("#register-btn").val('Sign Up');
                // }
                // }
            });

            $('#weight, #height').on('input', function() {
                setTimeout(function() {
                    var weight = $('#weight').val();
                    var height = $('#height').val() / 100; // Convert cm to m
                    var bmi = weight / (height * height);
                    $('#bmi').text(bmi.toFixed(2));
                }, 1);
            });

            $("#one").on("input", null, null, function(e) {
                if ($("#one").val().length == 2) {
                    setTimeout(function() {
                        $("#two").focus();
                    }, 1);
                }
            });
            $("#three").click(function() {
                $("#four").focus();
            });
            $("#five").on("input", null, null, function() {
                if ($("#five").val().length == 2) {
                    $("#six").val("It works!");
                }
            });


            $('#fl_wait').hide();
            $('#wait_ds').hide();
            $('#region').change(function() {
                var getUid = $(this).val();
                $('#wait_ds').show();
                $.ajax({
                    url: "process.php?cnt=region",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#ds_data').html(data);
                        $('#wait_ds').hide();
                    }
                });
            });
            $('#wait_wd').hide();
            $('#ds_data').change(function() {
                $('#wait_wd').hide();
                var getUid = $(this).val();
                $.ajax({
                    url: "process.php?cnt=district",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#wd_data').html(data);
                        $('#wait_wd').hide();
                    }
                });

            });

            $('#a_cc').change(function() {
                var getUid = $(this).val();
                $('#wait').show();
                $.ajax({
                    url: "process.php?cnt=payAc",
                    method: "GET",
                    data: {
                        getUid: getUid
                    },
                    success: function(data) {
                        $('#cus_acc').html(data);
                        $('#wait').hide();
                    }
                });

            });

            $('#study_id').change(function() {
                var getUid = $(this).val();
                var type = $('#type').val();
                $('#fl_wait').show();
                $.ajax({
                    url: "process.php?cnt=study",
                    method: "GET",
                    data: {
                        getUid: getUid,
                        type: type
                    },

                    success: function(data) {
                        console.log(data);
                        $('#s2_2').html(data);
                        $('#fl_wait').hide();
                    }
                });

            });

        });
    </script>
</body>

</html>