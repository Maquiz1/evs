<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();

$now = date('Y-m-d');
$nxt_day = date('Y-m-d', strtotime($now . ' + 1 days'));
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.php" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="evs LOGO" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Volunteer Database</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php if ($user->data()->gender == 1) { ?>
                    <img src="dist/img/avatar.png" class="img-circle elevation-2" alt="User Image">
                <?php } elseif ($user->data()->gender == 2) { ?>
                    <img src="dist/img/avatar3.png" class="img-circle elevation-2" alt="User Image">
                <?php } else { ?>
                    <img src="dist/img/avatar4.png" class="img-circle elevation-2" alt="User Image">
                <?php } ?>
            </div>
            <div class="info">
                <a href="profile.php" class="d-block"><?= $user->data()->firstname . ' - ' . $user->data()->lastname ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index1.php?title=1" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Home </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="info.php?id=1&status=1" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registered<span class="badge badge-info right"><?= $override->getCount('clients', 'status', 1) ?></span></p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="info.php?id=1&status=2" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Available
                            <i class="fas fa-angle-left right"></i>
                            <p><span class="badge badge-info right"></span></p>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="info.php?id=1&status=2&project_id=0" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Studies<span class="badge badge-info right"><?= $override->getCount2('clients', 'status', 1, 'available', 1, 'project_id', 0) ?></span></p>
                            </a>
                        </li>
                        <?php foreach ($override->get('study', 'status', 1) as $available) { ?>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=2&project_id=<?= $available['id']; ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $available['name'] ?><span class="badge badge-info right"><?= $override->getCount2Not('clients', 'status', 1, 'available', 1, 'project_id', $available['id']) ?></span></p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Sensitizations
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $sensitization ?></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php
                        foreach ($override->get('study', 'status', 1) as $study1) {
                        ?>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=3&project_id=<?= $study1['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $study1['name'] ?><span class="badge badge-info right"><?= $override->getCount2('clients', 'status', 1, 'sensitization', 1, 'project_id', $study1['id']) ?></span></p>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Screening
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $Screening ?></span>
                        </p>
                    </a>
                    <?php
                    foreach ($override->get('study', 'status', 1) as $study2) {
                    ?>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=1&status=4&project_id=<?= $study2['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $study2['name'] ?><span class="badge badge-info right"><?= $override->getCount2('clients', 'status', 1, 'screening', 1, 'project_id', $study2['id']) ?></span></p>
                                </a>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            ELigible
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $Screening ?></span>
                        </p>
                    </a>
                    <?php
                    foreach ($override->get('study', 'status', 1) as $study3) {
                    ?>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="info.php?id=1&status=5&project_id=<?= $study3['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $study3['name'] ?><span class="badge badge-info right"><?= $override->getCount2('clients', 'status', 1, 'eligible', 1, 'project_id', $study3['id']) ?></span></p>
                                </a>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Enrollment
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $Enrollment ?></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <?php
                        foreach ($override->get('study', 'status', 1) as $study4) {
                        ?>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=6&project_id=<?= $study4['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $study4['name'] ?><span class="badge badge-info right"><?= $override->getCount2('clients', 'status', 1, 'enrollment', 1, 'project_id', $study4['id']) ?></span></p>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Locked
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"><?= $Locked ?></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php
                        foreach ($override->get('study', 'status', 1) as $study5) {
                        ?>
                            <li class="nav-item">
                                <a href="info.php?id=1&status=7&project_id=<?= $study5['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= $study5['name'] ?><span class="badge badge-info right"><?= $override->getCount2Not('clients', 'status', 1, 'locked', 1, 'project_id', $study5['id']) ?></span></p>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-plus-square"></i>
                        <p>
                            Schedules
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php
                        foreach ($override->get('study', 'status', 1) as $study6) {
                        ?>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        <?= $study6['name'] ?> <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="info.php?id=4&project_id=<?= $study6['id'] ?>&day=0" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Toady Visits<span class="badge badge-info right"><?= $override->getCount3('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d'), 'project_id', $study6['id']) ?></span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="info.php?id=4&project_id=<?= $study6['id'] ?>&day=1" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Tommorow Visits<span class="badge badge-info right"><?= $override->getCount3('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 days')), 'project_id', $study6['id']) ?></span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="info.php?id=4&project_id=<?= $study6['id'] ?>&day=7" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Next 7 Days Visits<span class="badge badge-info right"><?= $override->getCount3Less('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 days')), 'project_id', $study6['id']) ?></span></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="info.php?id=4&project_id=<?= $study6['id'] ?>&day=-1" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Pending Visits<span class="badge badge-info right"><?= $override->getCount3Less('visit', 'status', 1, 'visit_status', 0, 'expected_date', date('Y-m-d', strtotime(date('Y-m-d') . ' + 0 days')), 'project_id', $study6['id']) ?></span></p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>