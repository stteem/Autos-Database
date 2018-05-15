<?php // Do not put any HTML above this line

session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}


//$failure = false;  // If we have no POST data
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123
// Check to see if we have some POST data, if we do process it
if ( isset($_POST['who']) && isset($_POST['pass']) ) {
  unset($_SESSION["who"]);
  $who = htmlentities($_POST['who']); 
  $pass = htmlentities($_POST['pass']);
    if ( strlen($who) < 1 || strlen($pass) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
    } 

    elseif (filter_var($who, FILTER_VALIDATE_EMAIL)) {

      $check = hash('md5', $salt.$pass);
      if ( $check == $stored_hash ) {
        $_SESSION["who"] = $_POST["who"];
        $_SESSION["success"] = "Logged in.";

        try {
          throw new Exception("Login success ".$who);
        }
        catch (Exception $ex) {
          error_log($ex->getMessage());
        }
        
        // Redirect the browser to game.php
        header("Location: view.php?name=".urlencode($who));
        return;
            
      } else {

          try {
            throw new Exception("Login fail ".$who." $check");
          }
          catch (Exception $e) {
            error_log($e->getMessage());
          }
          
          $_SESSION["error"] = "Incorrect password.";
          header("Location: login.php");
          return;

      }
      
    }
    
    else {
        $_SESSION['error'] = "Email must have an at-sign (@)";
    }    
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<title>Uwem Effiong Uke</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION["error"]) ) {
      echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
      unset($_SESSION["error"]);
  }

if ( isset($_SESSION["success"]) ) {
    echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
    unset($_SESSION["success"]);
}
?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="pass" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Disregard this hint. Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>
