<?php
require_once 'php/core/init.php';


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
        <li class="nav-item d-none d-sm-inline-block">
            <a href="import.php" class="nav-link">Upload Data</a>
        </li>
    </ul>

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