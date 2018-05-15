<?php 
<?php
require_once "pdo.php";
require_once "register_handler.php";
session_start();

// p' OR '1' = '1

if ( isset($_POST['email']) && isset($_POST['password'])  ) {
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    $email = htmlentities($_POST['email']); 
    $pass = htmlentities($_POST['password']);

    if ( strlen($email) < 1 || strlen($pass) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header('Location: login.php');
        return;
    }

    //Check if email exists
    elseif (filter_var($email, FILTER_SANITIZE_EMAIL)) {
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);
      $sql = "SELECT email FROM users 
        WHERE email = :em AND password = :pw";

      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
        ':em' => $_POST['email'], 
        ':pw' => $_POST['password']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if ( $row === FALSE ) {

        try {
            throw new Exception("Login fail ".$email);
          }
        catch (Exception $e) {
          error_log($e->getMessage());
        }

        $_SESSION["error"] = "Incorrect email or password.";
        header("Location: login.php");
        return;
      } 

      else { 

        try {
          throw new Exception("Login success ".$email);
        }
        catch (Exception $ex) {
          error_log($ex->getMessage());
        }

        $_SESSION['email'] = $_POST['email'];        
        $_SESSION["success"] = "Logged in.";
        header("Location: index.php");
        return;
      }

    }
    
}
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="assets/js/jquery-3.3.1.min.js"></script>
  <script src="assets/js/register.js"></script>
</head>
<body>
<?php

  if(isset($_POST['register_button'])) {
    echo '
    <script>
    $(document).ready(function() {
      $("#first").hide();
      $("#second").show();
    });
    </script>
    ';
  }
  ?>

<div class="wrapper">
  <div class="login_box">
    <div class="login_header">
    <p>Please Login or Signup Below</p>
    </div><br>
    <?php 
    if ( isset($_SESSION["error"]) ) {
          echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
          unset($_SESSION["error"]);
    }
    if(isset($_SESSION['success'])) {
      echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
      unset($_SESSION['success']);
    } 
    
    ?>
    <div id="first">
      <form action="login.php" method="post">
        <p>Email:
        <input type="text" size="40" name="email" value="<?php
        if(isset($_SESSION['email'])) {
          echo $_SESSION['email'];
        }
        ?>" required></p>
        <p>Password:
        <input type="text" size="40" name="password"></p>
        <p><input type="submit" value="Login"/></p>
        <a href="#" id="signup" class="signup">Don't have an account? Sign up!</a>
      </form>
    </div>

    <div id="second">
        <form action="login.php" method="POST">

          <input type="text" name="reg_fname" placeholder="First Name" value="<?php
          if(isset($_SESSION['reg_fname'])) {
            echo $_SESSION['reg_fname'];
          }
          ?>" required>
          <br>
          <?php 

          if (isset($_SESSION['error'])) {
              echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
              unset($_SESSION['error']);
          } 
          ?>

          <input type="text" name="reg_lname" placeholder="last Name" value="<?php
          if(isset($_SESSION['reg_lname'])) {
            echo $_SESSION['reg_lname'];
          }
          ?>" required>
          <br>
          <?php 

          if (isset($_SESSION['error'])) {
              echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
              unset($_SESSION['error']);
          }
          ?>

          <input type="email" name="reg_email" placeholder="Email" value="<?php
          if(isset($_SESSION['reg_email'])) {
            echo $_SESSION['reg_email'];
          }
          ?>" required>
          <br>
          <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
          if(isset($_SESSION['reg_email2'])) {
            echo $_SESSION['reg_email2'];
          }
          ?>" required>
          <br>
          <?php 
          if (isset($_SESSION['error'])) {
              echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
              unset($_SESSION['error']);
          }
          ?>

          <input type="password" name="reg_password" placeholder="Password" required>
          <br>
          <input type="password" name="reg_password2" placeholder="Confirm Password" required><br>
          <?php 
          if (isset($_SESSION['error'])) {
              echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
              unset($_SESSION['error']);
          }
          ?>

          <input type="submit" name="register_button" value="Register">
          <br>

          
          <a href="#" id="signin" class="signin">Already have an account? sign in here!</a>
        </form>
      </div>
  </div>
</div>
</body>
</html>


?>