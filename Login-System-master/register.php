<?php
# Include connection
require_once "./login_config.php";
// include_once("../../../components/navbar/index.php"); 

# Define variables and initialize with empty values
$fullname_err = $username_err = $email_err = $password_err = $address_err = $age_err = $mobile_err = "";
$fullname = $username = $email = $password = $address = $age = $mobile = "";

# Processing form data when form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  # Validate Full Name
  if (empty(trim($_POST["fullname"]))) {
    $fullname_err = "Please enter your full name.";
  } else {
    $fullname = trim($_POST["fullname"]);
  }

  # Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
      $username_err = "Username can only contain letters, numbers and symbols like '@', '_', or '-'.";
    } else {
      # Prepare a select statement
      $sql = "SELECT id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        # Set parameters
        $param_username = $username;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) {
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if username is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = "This username is already registered.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.')" . "</script>";
        }

        # Close statement 
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate email 
  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter an email address";
  } else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Please enter a valid email address.";
    } else {
      # Prepare a select statement
      $sql = "SELECT id FROM users WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        # Set parameters
        $param_email = $email;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) {
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if email is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "This email is already registered.";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        }

        # Close statement
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 8) {
      $password_err = "Password must contain at least 8 or more characters.";
    }
  }

    if (empty(trim($_POST["address"]))) {
    $address_err = "Please enter your address.";
  } else {
    $address = trim($_POST["address"]);
  }

  # Validate Age
  if (empty(trim($_POST["age"]))) {
    $age_err = "Please enter your age.";
  } else {
    $age = trim($_POST["age"]);
    if (!ctype_digit($age) || $age < 0) {
      $age_err = "Please enter a valid age.";
    }
  }

  # Validate Mobile Number
  if (empty(trim($_POST["mobile"]))) {
    $mobile_err = "Please enter your mobile number.";
  } else {
    $mobile = trim($_POST["mobile"]);
    if (!ctype_digit($mobile) || strlen($mobile) !== 10) {
      $mobile_err = "Please enter a valid 10-digit mobile number.";
    }
  }

# Check input errors before inserting data into the database
if (empty($fullname_err) && empty($username_err) && empty($email_err) && empty($password_err) && empty($address_err) && empty($age_err) && empty($mobile_err)) {
  # Prepare an insert statement
  $sql = "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    # Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssss", $param_fullname, $param_username, $param_email, $param_password);

    # Set parameters
    $param_fullname = $fullname;
    $param_username = $username;
    $param_email = $email;
    $param_password = password_hash($password, PASSWORD_DEFAULT);

    # Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      # Get the last inserted id from the 'users' table
      $user_id = mysqli_insert_id($link);

      # Insert data into the 'student' table using the same id
      $sql_student = "INSERT INTO student (s_id, s_name, s_email, s_age, s_mobile, s_address) VALUES (?, ?, ?, ?, ?, ?)";

      if ($stmt_student = mysqli_prepare($link, $sql_student)) {
        # Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt_student, "isssis", $param_s_id, $param_s_name, $param_s_email, $param_s_age, $param_s_mobile, $param_s_address);

        # Set parameters
        $param_s_id = $user_id;
        $param_s_name = $fullname;
        $param_s_email = $email;
        $param_s_age = $age;
        $param_s_mobile = $mobile;
        $param_s_address = $address;

        # Execute the prepared statement for the 'student' table
        if (mysqli_stmt_execute($stmt_student)) {
          echo "<script>alert('Registration completed successfully. Login to continue.');</script>";
          echo "<script>window.location.href='./login.php';</script>";
          exit;
        } else {
          echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
        }

        # Close 'student' statement
        mysqli_stmt_close($stmt_student);
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
    }

    # Close 'users' statement
    mysqli_stmt_close($stmt);
  }
}

# Close connection
mysqli_close($link);
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
  <script defer src="./js/script.js"></script>
</head>

<body>
  <?php include_once("../../../components/navbar/index.php"); ?>
  <div class="container">
    <div class="row min-vh-100 justify-content-center mt-4">
      <div class="col-lg-5">
        <div class="form-wrap border rounded p-4">
          <h1>Sign up</h1>
          <p>Please fill this form to register</p>
          <!-- form starts here -->
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" name="fullname" id="fullname" value="<?= $fullname; ?>">
              <small class="text-danger"><?= $fullname_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" name="username" id="username" value="<?= $username; ?>">
              <small class="text-danger"><?= $username_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
              <small class="text-danger"><?= $email_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
              <small class="text-danger"><?= $password_err; ?></small>
            </div>
             <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" name="address" id="address" value="<?= $address; ?>">
              <small class="text-danger"><?= $address_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="age" class="form-label">Age</label>
              <input type="number" class="form-control" name="age" id="age" value="<?= $age; ?>">
              <small class="text-danger"><?= $age_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="mobile" class="form-label">Mobile Number</label>
              <input type="tel" class="form-control" name="mobile" id="mobile" value="<?= $mobile; ?>">
              <small class="text-danger"><?= $mobile_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Show Password</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Sign Up">
            </div>
            <p class="mb-0">Already have an account ? <a href="./login.php">Log In</a></p>
          </form>
          <!-- form ends here -->
        </div>
      </div>
    </div>
  </div>
</body>

</html>