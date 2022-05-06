<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login | AttendanceQR</title>
 
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

  <script src="vendor/jquery/jquery.min.js"></script>

</head>

<body class="bg-dark" style="background: url('images/background.jpg'); background-size: cover;">
<?php
    if (isset($_POST['submit'])&&isset($_POST['username'])&&isset($_POST['password'])) {
        require 'connection.php';
        $username = $conn->real_escape_string(strip_tags($_POST['username']));
        $password = md5($conn->real_escape_string(strip_tags($_POST['password'])));
        $sql = "SELECT * FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows) {
          $access = $result->fetch_assoc()['access'];
          if ($access == "restricted") {
?>
    <div class="modal fade" id="restrictedModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Access Denied!</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">You are not authorized to enter. If you think you are seeing this message by mistake, please contact the Administrator.</div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load',function(){
            $('#restrictedModal').modal('show');
        });
    </script>
<?php
          }
          elseif ($access == "admin") {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $conn->close();
            header('Location: admin/home.php');
            die();
          }
          else {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $conn->close();
            header('Location: home.php');
            die();
          }
        }
        else {
?>
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Access Denied!</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">The username/password provided is incorrect. Please check the credentials and try again.</div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
        $(window).on('load',function(){
            $('#loginModal').modal('show');
        });
    </script>
<?php
        }
    }
?>
  <div class="container">
    <div class="card card-login mx-auto mt-5" style="background: rgba(0,0,0,0.7);">
      <div class="card-body">
        <center><img src="images/logo.png" class="image image-responsive" height="150" width="150"></center>
        <br />
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="inputEmail" name="username" class="form-control" placeholder="Username" required="required" autofocus="autofocus">
              <label for="inputEmail">Username</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="required">
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>
