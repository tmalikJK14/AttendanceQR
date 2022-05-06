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

  <title>Change Password | AttendanceQR</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <script src="vendor/jquery/jquery.min.js"></script>

</head>

<body class="bg-dark">
<?php
    if (isset($_POST['submit'])&&isset($_POST['current_password'])&&isset($_POST['new_password'])&&isset($_POST['confirm_password'])) {
    extract($_POST);
    $current_password = md5($conn->real_escape_string(strip_tags($current_password)));
    $new_password = md5($conn->real_escape_string(strip_tags($new_password)));
    $confirm_password = md5($conn->real_escape_string(strip_tags($confirm_password)));
    if ($current_password == $user['password']) {
      if ($new_password == $confirm_password) {
        $uid = $user['uid'];
        $sql = "UPDATE `users` SET `password` = '$new_password' WHERE `uid` = '$uid'";
        if ($conn->query($sql)) {
          $_SESSION['password'] = $new_password;
          $conn->close();
          header('Location: home.php');
          die();
        }
        else {
          $conn->close();
?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Error</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Could not update the password. Please try again!</div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load',function(){
            $('#errorModal').modal('show');
        });
    </script>
<?php
        }
      }
      else {
        $conn->close();
?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Could not update the Password</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Passwords do not match!</div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load',function(){
            $('#errorModal').modal('show');
        });
    </script>
<?php
      }
    }
    else {
      $conn->close();
?>
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Could not update the Password</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">The password you provided is incorrect.</div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load',function(){
            $('#errorModal').modal('show');
        });
    </script>
<?php
    }
  }
?>
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Change Password</div>
      <div class="card-body">
        <div class="text-center mb-4">
          <h4>Want to change your password?</h4>
          <p>Enter your current password below to change it with your new password.</p>
        </div>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
          <div class="form-group">
            <input type="password" class="form-control" required name="current_password" placeholder="Current Password" autofocus>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" required name="new_password" placeholder="New Password">
          </div>
          <div class="form-group">
            <input type="password" class="form-control" required name="confirm_password" placeholder="Confirm New Password">
          </div>
          <button class="btn btn-primary btn-block" type="submit" name="submit">Change Password</button>
        </form>
        <div class="text-center">
          <a class="d-block small" style="text-decoration: none;" href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Go back</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>
