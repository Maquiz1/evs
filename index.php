<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$usr = null;
$email = new Email();
$st = null;
$random = new Random();
$pageError = null;
$successMessage = null;
$errorM = false;
$errorMessage = null;
if (!$user->isLoggedIn()) {
  if (Input::exists('post')) {
    if (Token::check(Input::get('token'))) {
      $validate = new validate();
      $validate = $validate->check($_POST, array(
        'username' => array('required' => true),
        'password' => array('required' => true)
      ));
      if ($validate->passed()) {
        $st = $override->get('user', 'username', Input::get('username'));
        if ($st) {
          if ($st[0]['count'] > 3) {
            $errorMessage = 'You Account have been deactivated,Someone was trying to access it with wrong credentials. Please contact your system administrator';
          } else {
            $login = $user->loginUser(Input::get('username'), Input::get('password'), 'user');
            if ($login) {
              $lastLogin = $override->get('user', 'id', $user->data()->id);
              if ($lastLogin[0]['last_login'] == date('Y-m-d')) {
              } else {
                try {
                  $user->updateRecord('user', array(
                    'last_login' => date('Y-m-d H:i:s'),
                    'count' => 0,
                  ), $user->data()->id);
                } catch (Exception $e) {
                }
              }
              try {
                $user->updateRecord('user', array(
                  'count' => 0,
                ), $user->data()->id);
              } catch (Exception $e) {
              }

              Redirect::to('dashboard.php');
            } else {
              $usr = $override->get('user', 'username', Input::get('username'));
              if ($usr && $usr[0]['count'] < 3) {
                try {
                  $user->updateRecord('user', array(
                    'count' => $usr[0]['count'] + 1,
                  ), $usr[0]['id']);
                } catch (Exception $e) {
                }
                $errorMessage = 'Wrong username or password';
              } else {
                try {
                  $user->updateRecord('user', array(
                    'count' => $usr[0]['count'] + 1,
                  ), $usr[0]['id']);
                } catch (Exception $e) {
                }
                $email->deactivation($usr[0]['email_address'], $usr[0]['lastname'], 'Account Deactivated');
                $errorMessage = 'You Account have been deactivated,Someone was trying to access it with wrong credentials. Please contact your system administrator';
              }
            }
          }
        } else {
          $errorMessage = 'Invalid username, Please check your credentials and try again';
        }
      } else {
        $pageError = $validate->errors();
      }
    }
  }
} else {
  Redirect::to('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title> Login - eVS </title>
  <?php include 'header.php' ?>
</head>

<body>
  <h1>Welcome. Please Sign In</h1>
  <div class="loginBlock" id="login" style="display: block;">
    <div class="dr"><span></span></div>
    <?php if ($errorMessage) { ?>
      <div class="alert alert-danger">
        <h4>Error!</h4>
        <?= $errorMessage ?>
      </div>
    <?php } elseif ($pageError) { ?>
      <div class="alert alert-danger">
        <h4>Error!</h4>
        <?php foreach ($pageError as $error) {
          echo $error . ' , ';
        } ?>
      </div>
    <?php } elseif ($successMessage) { ?>
      <div class="alert alert-success">
        <h4>Success!</h4>
        <?= $successMessage ?>
      </div>
    <?php } ?>

    <!-- Main content -->
    <div class="loginForm">
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <div class="col-md-6">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Volunterr Database</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" method="post" id="validation">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">User Name</label>
                      <input type="text" name="username" id="username" placeholder="Username" class="form-control validate[required]" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input type="password" name="password" id="password" placeholder="Password" class="form-control validate[required]" />
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
                    <input type="submit" value="Sign in" class="btn btn-default btn-block">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>


    <!-- /.card -->
</body>

</html>