<!-- connect to MySQL Database -->
<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "project_se";
$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
?>

<?php
//php session
session_start();

//redirect to dashboard if user is logged in
if (isset($_SESSION['username'])) {
  header("Location: index.php");
}

//check if Register form is submitted
if (isset($_POST['submit'])) {
  //to prevent SQL INJECTION ATTACK
  $username = trim(mysqli_real_escape_string($connection, $_POST["username"]));
  $password = trim(mysqli_real_escape_string($connection, $_POST["password"]));
  $full_name = trim(ucwords(mysqli_real_escape_string($connection, $_POST["full_name"])));

  $options = [
    'cost' => 12
  ];
  $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options) . "\n";

  //make sure that the username and fullname don't already exist
  $sql = "SELECT * FROM `accounts` WHERE `Username`='$username' OR `Fullname`='$full_name';";
  $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
  if (mysqli_num_rows($result) >= 1) {
    $message = "User account already exists.";
  } else {
    //make a new entry in "accounts" TABLE
    $sql = "INSERT INTO `accounts` VALUES ('{$username}','{$hashed_password}','{$full_name}');";
    $result = mysqli_query($connection, $sql) or die("Database Connection Error: " . mysqli_connect_error() . " (" . mysqli_connect_errno() . ")");
    if (!$result) {
      die("INSERT INTO `accounts` . . . , failed: " . mysqli_error($connection));
    } else {
      //display registration successful message and redirect to Login page
      $message = "Registered as \"" . $username . "\", redirecting . . .";
      header("refresh:3;url=login.php");
    }
  }
} else {
  //default username
  $username = "";
  //default message
  $message = "<span class=\"brand\">Conspectus </span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description"
	content="Conspectus - Syllabus Planning web application.">
  <meta name="author" content="Ashesh Kumar Singh (ashesh.app@outlook.com)">
  <title>Conspectus</title>
  <!-- Twitter Bootstrap Core CSS -->
  <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300|Grand+Hotel" rel="stylesheet" type="text/css"/>
  <!-- Flat-UI for Bootstrap -->
  <link href="bower_components/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet">
  <!-- Custom CSS-->
  <link href="css/main.css" rel="stylesheet">
  <link href="css/login-register.css" rel="stylesheet">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png?v=bOOKmG78np">
  <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png?v=bOOKmG78np">
  <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png?v=bOOKmG78np">
  <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png?v=bOOKmG78np">
  <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png?v=bOOKmG78np" sizes="32x32">
  <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png?v=bOOKmG78np" sizes="96x96">
  <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png?v=bOOKmG78np" sizes="16x16">
  <link rel="manifest" href="/favicons/manifest.json?v=bOOKmG78np">
  <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg?v=bOOKmG78np" color="#5bbad5">
  <link rel="shortcut icon" href="/favicons/favicon.ico?v=bOOKmG78np">
  <meta name="msapplication-TileColor" content="#00aba9">
  <meta name="msapplication-config" content="/favicons/browserconfig.xml?v=bOOKmG78np">
  <meta name="theme-color" content="#ffffff">
  <link rel="shortcut icon" href="favicon.ico">
</head>
<body>
<iframe src="https://ghbtns.com/github-btn.html?user=user501254&repo=Conspectus&type=star&count=true&size=large" frameborder="0" scrolling="0" width="150px" height="30px" style="margin-top: 15px;padding-left: 21px;"></iframe>
<iframe src="https://ghbtns.com/github-btn.html?user=user501254&repo=Conspectus&type=fork&count=true&size=large" frameborder="0" scrolling="0" width="150px" height="30px" style="margin-top: 15px;"></iframe>
<!-- Registeration Form -->
<form id="register-form" action="register.php" method="POST">
  <a href="index.php">
    <img src="PNGs/Notebook.png" alt=""/>
    <?php
    echo "<p id=\"messages\">" . $message . "</p>";
    ?>
  </a>
  <div class="form-group input-group-lg">
    <input type="text" name="full_name" required="" pattern="^[A-Za-z. ]{3,36}" placeholder="Your full name"
           class="form-control"
           oninvalid="setCustomValidity('must be between 3 to 36 characters long; can have alphabets, dots and spaces.')"
           onchange="try{setCustomValidity('')}catch(e){}">
  </div>
  <div class="input-group input-group-lg">
    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user"></i></span>
    <input type="text" name="username" class="form-control" autocomplete="off" required="" pattern="^[a-z0-9.]{3,24}$"
           placeholder="enter desired Username" aria-describedby="sizing-addon1"
           oninvalid="setCustomValidity('must be between 3 to 24 characters long; can have lowercase alphabets, numbers and dots.')"
           onchange="try{setCustomValidity('')}catch(e){}">
  </div>
  <div class="input-group input-group-lg">
    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
    <input type="text" name="password" class="form-control" autocomplete="off" required=""
           pattern="^(?=^.{4,}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$" placeholder="enter some Password"
           aria-describedby="sizing-addon1"
           oninvalid="setCustomValidity('must be atleast 4 characters long; must be a combination of upercase & lowercase alphabets and digit(s); other characters are optional.')"
           onchange="try{setCustomValidity('')}catch(e){}">
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Make account</button>
</form>
<div class="footer">
  <hr/>
  <a class="input-group" href="login.php">Already have an account?</a>
</div>

<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Twitter Bootstrap Core JS -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
<!-- close database connection -->
<?php
mysqli_close($connection);
?>
