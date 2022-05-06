<?php
    session_start();
    require '../connection.php';
    if (isset($_SESSION['username'])&&isset($_SESSION['password'])) {
        extract($_SESSION);
        $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' AND `access` = 'admin'";
        if ($conn->query($sql)->num_rows == 0) {
            session_destroy();
            header('Location: ../index.php');
            die();
        }
    }
    else {
        session_destroy();
        header('Location: ../index.php');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Admin Dashboard | AttendanceQR</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="home.php">Attendance QR</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
      <div class="input-group" style="visibility: hidden;">
        <input type="text" class="form-control" disabled placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
          <button class="btn btn-primary" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="change-password.php">Change Password</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
        </div>
      </li>
    </ul>

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="add-user.php">
          <i class="fas fa-fw fa-user"></i>
          <span>Add User</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="scan.php">
          <i class="fas fa-fw fa-qrcode"></i>
          <span>Scan QR</span>
        </a>
      </li>
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>
<?php
  $sql = "SELECT * FROM `users`";
  $result = $conn->query($sql);
  $users = array();
  $admin = $full = $regular = $restricted = 0;
  while ($row = $result->fetch_assoc()) {
    if ($row['access'] == "admin") {
      $admin++;
    }
    if ($row['access'] == "full") {
      $full++;
    }
    if ($row['access'] == "regular") {
      $regular++;
    }
    if ($row['access'] == "restricted") {
      $restricted++;
    }
    array_push($users, $row);
  }
?>
        <!-- Icon Cards-->
        <div class="row">
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-primary o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-user-tie"></i>
                </div>
                <div class="mr-5"><?php echo $admin; ?> <?php if ($admin == 1) { echo "Admin"; } else { echo "Admins"; } ?></div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="view-users.php?u=admin">
                <span class="float-left">View All</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-user-secret"></i>
                </div>
                <div class="mr-5"><?php echo $regular; ?> <?php if ($regular == 1) { echo "Regular Users"; } else { echo "Regular Users"; } ?></div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="view-users.php?u=regular">
                <span class="float-left">View All</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-users"></i>
                </div>
                <div class="mr-5"><?php echo $full; ?> <?php if ($full == 1) { echo "Full User"; } else { echo "Full Users"; } ?></div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="view-users.php?u=full">
                <span class="float-left">View All</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
          <div class="col-xl-3 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
              <div class="card-body">
                <div class="card-body-icon">
                  <i class="fas fa-fw fa-user-slash"></i>
                </div>
                <div class="mr-5"><?php echo $restricted; ?> <?php if ($restricted == 1) { echo "Restricted User"; } else { echo "Restricted Users"; } ?></div>
              </div>
              <a class="card-footer text-white clearfix small z-1" href="view-users.php?u=restricted">
                <span class="float-left">View All</span>
                <span class="float-right">
                  <i class="fas fa-angle-right"></i>
                </span>
              </a>
            </div>
          </div>
        </div>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-users"></i>
            All Users Data</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered text-center" id="dataTable" width="150%" cellspacing="0">
                <thead>
                  <tr>
                    <th  style="vertical-align: middle;">Name</th>
                    <th  style="vertical-align: middle;">Mobile No.</th>
                    <th  style="vertical-align: middle;">Address</th>
                    <th  style="vertical-align: middle;">Blood Group</th>
                    <th  style="vertical-align: middle;">Emergency Contact Person</th>
                    <th  style="vertical-align: middle;">Emergency Mobile No.</th>
                    <th  style="vertical-align: middle;">Start Time</th>
                    <th  style="vertical-align: middle;">End Time</th>
                    <th  style="vertical-align: middle;">Access</th>
                    <th  style="vertical-align: middle;">Access</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th  style="vertical-align: middle;">Name</th>
                    <th  style="vertical-align: middle;">Mobile No.</th>
                    <th  style="vertical-align: middle;">Address</th>
                    <th  style="vertical-align: middle;">Blood Group</th>
                    <th  style="vertical-align: middle;">Emergency Contact Person</th>
                    <th  style="vertical-align: middle;">Emergency Mobile No.</th>
                    <th  style="vertical-align: middle;">Start Time</th>
                    <th  style="vertical-align: middle;">End Time</th>
                    <th  style="vertical-align: middle;">Access</th>
                    <th  style="vertical-align: middle;">Access</th>
                  </tr>
                </tfoot>
                <tbody>
<?php
  foreach ($users as $user) {
?>
                  <tr>
                    <td style="vertical-align: middle;"> <?php echo $user['name']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['phone_no']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['address']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['blood_group']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['emergency_person']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['emergency_phone_no']; ?> </td>
                    <td style="vertical-align: middle;"> <?php echo date('h:i A', strtotime($user['start_time'])); ?> </td>
                    <td style="vertical-align: middle;"> <?php echo date('h:i A', strtotime($user['end_time'])); ?> </td>
                    <td style="vertical-align: middle;"> <?php echo $user['access']; ?> </td>
                    <td style="vertical-align: middle; text-align: justify;">
                      <div class="btn-group" role="group" aria-label="Basic example">
                        <button onclick="location.href='edit-user.php?i=<?php echo base64_encode($user['uid']); ?>'" class="btn btn-primary" style="width: 40px;"><i class="fas fa-edit text-white"></i></button>
<?php
    if ($user['access'] != "admin") {
?>
                        <button onclick="location.href='attendance-details.php?i=<?php echo base64_encode($user['uid']); ?>'" class="btn btn-warning" style="width: 40px;"><i class="fas fa-info text-white"></i></button>
<?php
    }
?>
                        <button onclick="if(confirm('Are you sure you want to delete? This action cannot be undone.')) { location.href='delete.php?i=<?php echo $user['uid']; ?>'; }" class="btn btn-danger" style="width: 40px;"><i class="fas fa-trash text-white"></i></button>
                      </div>
                    </td>
                  </tr>
<?php
  }
?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <footer class="sticky-footer">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright © AttendanceQR 2019. Made with <i class="fas fa-heart text-danger"></i> by Interns at <a href="http://www.birdhouse.co.in/">Birdhouse Shelter Private Limited</a>.</span>
          </div>
        </div>
      </footer>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="../logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="../js/demo/datatables-demo.js"></script>
  <script src="../js/demo/chart-area-demo.js"></script>

</body>

</html>
