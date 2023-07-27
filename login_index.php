<?php
# Initialize the session
session_start();

# If the user is not logged in, then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== TRUE) {
    header("Location: ./login.php");
    exit;
}

// Your login authentication code goes here

// For example, if your authentication logic sets a variable $login_successful to true on successful login:
$login_successful = true;

// If the student login is successful, redirect to the index page
if ($login_successful) {
    header("Location: internship-portal/pages/student/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User login system</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <div class="alert alert-success my-5">
            Welcome! You are now signed in to your account.
        </div>
        <!-- User profile -->
        <div class="row justify-content-center">
            <div class="col-lg-5 text-center">
                <img src="./img/blank-avatar.jpg" class="img-fluid rounded" alt="User avatar" width="180">
                <h4 class="my-4">Hello, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
                <a href="./logout.php" class="btn btn-primary">Log Out</a>
            </div>
        </div>
    </div>
</body>

</html>
