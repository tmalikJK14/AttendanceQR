<?php
    session_start();
    date_default_timezone_set("Asia/Kolkata");
    require 'connection.php';
    if (isset($_SESSION['username'])&&isset($_SESSION['password'])) {
      extract($_SESSION);
      $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' AND `access` != 'admin' AND `access` != 'restricted'";
      $result = $conn->query($sql);
      if ($result->num_rows == 0) {
        session_destroy();
        header('Location: index.php');
        die();
      }
      else {
        $user = $result->fetch_assoc();
      }
    }
    else {
      session_destroy();
      header('Location: index.php');
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

  <title>Dashboard | AttendanceQR</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

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
        <a class="nav-link" href="my-attendance.php">
          <i class="fas fa-fw fa-qrcode"></i>
          <span>My Attendance</span>
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

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-user"></i>
            My Profile</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-responsive">
                <tr>
                  <th>Name</th>
                  <td> <?php echo $user['name']; ?> </td>
                </tr>
                <tr>
                  <th>Mobile No.</th>
                  <td> <?php echo $user['phone_no']; ?> </td>
                </tr>
                <tr>
                  <th>Address</th>
                  <td> <?php echo $user['address']; ?> </td>
                </tr>
                <tr>
                  <th>Blood Group</th>
                  <td> <?php echo $user['blood_group']; ?> </td>
                </tr>
                <tr>
                  <th>Emergency Contact Person</th>
                  <td> <?php echo $user['emergency_person']; ?> </td>
                </tr>
                <tr>
                  <th>Emergency Mobile No.</th>
                  <td> <?php echo $user['emergency_phone_no']; ?> </td>
                </tr>
                <tr>
                  <th>Attendance QR</th>
                  <td> <img class="img-responsive img-thumbnail" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&amp;data=<?php echo base64_encode(base64_encode(base64_encode('?id='.$user['uid'].'&time='.date('Y-m-d H:i:s', time())))); ?>"> </td>
                </tr>
                
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
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>

</body>

</html>
