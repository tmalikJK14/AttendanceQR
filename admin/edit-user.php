<?php
    session_start();
    require '../connection.php';
    if (isset($_SESSION['username'])&&isset($_SESSION['password'])) {
        extract($_SESSION);
        $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password' AND `access` = 'admin'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            session_destroy();
            header('Location: ../index.php');
            die();
        }
        else {
          $user_details = $result->fetch_assoc();
        }
    }
    else {
        session_destroy();
        header('Location: ../index.php');
        die();
    }

    if (!isset($_GET['i']) || empty($_GET['i'])) {
      header('Location: home.php');
    }
    else {
      $uid = base64_decode($_GET['i']);
      if ($uid == $user_details['uid']) {
        $sql = "SELECT * FROM `users` WHERE `uid` = '$uid' AND `access` = 'admin'";
      }
      else {
        $sql = "SELECT * FROM `users` WHERE `uid` = '$uid' AND `access` != 'admin'";
      }
      $result = $conn->query($sql);
      $user = array();
      if ($result->num_rows) {
        $user = $result->fetch_assoc();
      }
      else {
        header('Location: home.php');
      }
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

  <title>Add User | AttendanceQR</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">
<?php
  if (isset($_POST['submit'])&&isset($_POST['name'])&&isset($_POST['phone'])&&isset($_POST['uid'])&&isset($_POST['address'])&&isset($_POST['emergency_person'])&&isset($_POST['emergency_phone'])&&isset($_POST['start'])&&isset($_POST['end'])&&isset($_POST['blood_group'])&&isset($_POST['user_group'])) {
    extract($_POST);
    $sql = "UPDATE `users` SET `name`= '$name', `phone_no`= '$phone', `address`= '$address', `blood_group`= '$blood_group', `emergency_person`= '$emergency_person', `emergency_phone_no`= '$emergency_phone', `start_time`= '$start', `end_time`= '$end', `access`= '$user_group'  WHERE `uid` = '$uid'";
    if ($conn->query($sql)) {
      echo "<script>alert('User Updated Successfully!'); window.location.replace('home.php');</script>";
    }
  }
?>
  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="home.php">AttendanceQR</a>

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
      <li class="nav-item">
        <a class="nav-link" href="home.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item active">
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
            <a href="home.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Edit User</li>
        </ol>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-user"></i>
            Edit Details for <?php echo $user['name']; ?></div>
          <div class="card-body">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" required name="name" id="name" placeholder="Name" value="<?php echo $user['name']; ?>" autofocus>
                </div>
                <div class="form-group col-md-12">
                  <label for="phone">Mobile No.</label>
                  <input type="text" class="form-control" required name="phone" id="phone" value="<?php echo $user['phone_no']; ?>" placeholder="Mobile No.">
                </div>
                <div class="form-group col-md-6">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" readonly name="username" id="username" value="<?php echo $user['username']; ?>" placeholder="Username">
                </div>
                <div class="form-group col-md-6">
                  <input type="text" value="<?php echo $user['uid']; ?>" class="form-control" required hidden name="uid">
                </div>
              </div>
              <div class="form-group">
                <label for="inputAddress">Address</label>
                <input type="text" class="form-control" required name="address" id="inputAddress" value="<?php echo $user['address']; ?>" placeholder="Address">
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="emergency_person">Emergency Contact Person</label>
                  <input type="text" class="form-control" required name="emergency_person" id="emergency_person" value="<?php echo $user['emergency_person']; ?>" placeholder="Emergency Contact Person Name">
                </div>
                <div class="form-group col-md-6">
                  <label for="emergency_phone">Emergency Contact Mobile No.</label>
                  <input type="text" class="form-control" required name="emergency_phone" id="emergency_phone" value="<?php echo $user['emergency_phone_no']; ?>" placeholder="Emergency Contact Person Mobile No.">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="start">Start Time</label>
                  <input type="time" class="form-control" name="start" value="<?php echo $user['start_time']; ?>" required id="start">
                </div>
                <div class="form-group col-md-6">
                  <label for="end">End Time</label>
                  <input type="time" class="form-control" name="end" value="<?php echo $user['end_time']; ?>" required id="end">
                </div>
                <div class="form-group col-md-4">
                  <label for="bloodGroup">Blood Group</label>
                  <select name="blood_group" id="bloodGroup" class="form-control" required>
                    <option selected>Choose...</option>
                    <option <?php if ($user['blood_group'] == "A+") { echo "selected"; } ?> value="A+">A+</option>
                    <option <?php if ($user['blood_group'] == "A-") { echo "selected"; } ?> value="A-">A-</option>
                    <option <?php if ($user['blood_group'] == "B+") { echo "selected"; } ?> value="B+">B+</option>
                    <option <?php if ($user['blood_group'] == "B-") { echo "selected"; } ?> value="B-">B-</option>
                    <option <?php if ($user['blood_group'] == "O+") { echo "selected"; } ?> value="O+">O+</option>
                    <option <?php if ($user['blood_group'] == "O-") { echo "selected"; } ?> value="O-">O-</option>
                    <option <?php if ($user['blood_group'] == "AB+") { echo "selected"; } ?> value="AB+">AB+</option>
                    <option <?php if ($user['blood_group'] == "AB-") { echo "selected"; } ?> value="AB-">AB-</option>
                  </select>
                </div>
                <div class="form-group col-md-8">
                  <label for="userGroup">User Group</label>
                  <select name="user_group" id="userGroup" class="form-control" required>
                    <option selected>Choose...</option>
                    <option  <?php if ($user['access'] == "admin") { echo "selected"; } ?> value="admin">Admin</option>
                    <option  <?php if ($user['access'] == "full") { echo "selected"; } ?> value="full">Full</option>
                    <option  <?php if ($user['access'] == "regular") { echo "selected"; } ?> value="regular">Regular</option>
                    <option  <?php if ($user['access'] == "restricted") { echo "selected"; } ?> value="restricted">Restricted</option>
                  </select>
                </div>
              </div>
              <button name="submit" type="submit" class="btn btn-primary">Save Changes</button>
            </form>
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
