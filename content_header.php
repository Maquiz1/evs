      <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0">
                          <?php
                            if ($_GET['title'] == 1) {
                                echo 'Dashboard v1';
                            } elseif ($_GET['title'] == 2) {
                                echo 'Dashboard v2';
                            } elseif ($_GET['title'] == 3) {
                                echo 'Dashboard v3';
                            } else {
                                echo 'Dashboard v1';
                            }
                            ?>
                      </h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="#">Home</a></li>
                          <li class="breadcrumb-item active">
                              <?php
                                if ($_GET['title'] == 1) {
                                    echo 'Dashboard v1';
                                } elseif ($_GET['title'] == 2) {
                                    echo 'Dashboard v2';
                                } elseif ($_GET['title'] == 3) {
                                    echo 'Dashboard v3';
                                }else{
                                    echo 'Dashboard v1'; 
                                }
                                ?>
                          </li>
                      </ol>
                  </div><!-- /.col -->
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>