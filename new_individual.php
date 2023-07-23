<?php
// Ensure that error messages are displayed during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validate and sanitize user input function
function sanitize_input($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user input
    $StudentName = sanitize_input($_POST['StudentName']);
    $AcademicYear = sanitize_input($_POST['AcademicYear']);
    $CompanyName = sanitize_input($_POST['CompanyName']);
    $CompanyAddress = sanitize_input($_POST['CompanyAddress']);
    $CompanyLocation = sanitize_input($_POST['CompanyLocation']);
    $startDate = sanitize_input($_POST['startDate']);
    $endDate = sanitize_input($_POST['endDate']);
    $branch = sanitize_input($_POST['branch']);
    $semester = sanitize_input($_POST['semester']);
    $Stipend = sanitize_input($_POST['Stipend']);
    $Location = sanitize_input($_POST['Location']);
    $year = sanitize_input($_POST['year']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ... (previous code)
    
        if (empty($StudentName) || empty($AcademicYear) || empty($CompanyName) || empty($CompanyAddress) || empty($CompanyLocation) || empty($startDate) || empty($endDate) || empty($branch) || empty($semester) || empty($Stipend) || empty($Location) || empty($year)) {
            $errors[] = "All fields are required.";
        }
    
        // Additional validation rules (if any)
        // ...
    
        if (empty($errors)) {
            // Insert the data into the database
            require_once('connect.php'); // Assuming the database configuration is in this file
    
            // Use prepared statement to prevent SQL injection
            $query = "INSERT INTO individual_student (StudentName, AcademicYear, CompanyName, CompanyAddress, CompanyLocation, startDate, endDate, branch, semester, Stipend, Location, Year) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = mysqli_prepare($db_connection, $query);
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $StudentName, $AcademicYear, $CompanyName, $CompanyAddress, $CompanyLocation, $startDate, $endDate, $branch, $semester, $Stipend, $Location, $year);
    
            if (mysqli_stmt_execute($stmt)) {
                $success = true;
            } else {
                // Provide more informative error messages for database-related errors
                $errors[] = "Failed to insert the data. Error: " . mysqli_error($db_connection);
            }
    
            // Close the statement and database connection
            mysqli_stmt_close($stmt);
            mysqli_close($db_connection);
        }
    }
}

$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Individual Application</p>
    </div>

    <!-- Display success or error messages -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if (empty($errors)): ?>
            <div class="alert alert-success container col-8" role="alert">
                <h2 class="alert-heading">Application Success</h2>
                <hr>
                <p>You have successfully requested an NOC letter for <b><?php echo $CompanyName; ?></b>.<br>
                    Please keep checking your email inbox for further updates.</p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger container col-8" role="alert">
                <h2 class="alert-heading">Application Failed</h2>
                <hr>
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
                <p><b>Please fix the errors above and try again.</b></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="container my-3" id="content">
        <div class="bg-light p-5 rounded">
            <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
                <!-- Form fields -->
                <div class="col-md-6">
                    <label for="StudentName" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="StudentName" name="StudentName" required>
                </div>
                <div class="col-md-6">
                    <label for="AcademicYear" class="form-label">Academic Year</label>
                    <input type="text" class="form-control" id="AcademicYear" name="AcademicYear" required>
                </div>
                <div class="col-md-6">
                    <label for="CompanyName" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="CompanyName" name="CompanyName" required>
                </div>
                <div class="col-md-6">
                    <label for="CompanyAddress" class="form-label">Company Address</label>
                    <input type="text" class="form-control" id="CompanyAddress" name="CompanyAddress" required>
                </div>
                <div class="col-md-6">
                    <label for="CompanyLocation" class="form-label">Company Location</label>
                    <input type="text" class="form-control" id="CompanyLocation" name="CompanyLocation" required>
                </div>
                <div class="col-md-6">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                </div>
                <div class="col-md-6">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                </div>
                <div class="col-md-6">
                    <label for="branch" class="form-label">Branch</label>
                    <select class="form-select" id="branch" name="branch" required>
                        <option value="Automobile Engineering">Automobile Engineering</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Electronics and Computer Science">Electronics and Computer Science</option>
                        <option value="Electronics and Telecommunication">Electronics and Telecommunication</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select" id="semester" name="semester" required>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                        <option value="Semester 3">Semester 3</option>
                        <option value="Semester 4">Semester 4</option>
                        <option value="Semester 5">Semester 5</option>
                        <option value="Semester 6">Semester 6</option>
                        <option value="Semester 7">Semester 7</option>
                        <option value="Semester 8">Semester 8</option>
                    </select>
                </div>
                <div class="col-md-6">
    <label for="year" class="form-label">Year</label>
    <select class="form-select" id="year" name="year" required>
        <option value="First Year">First Year</option>
        <option value="Second Year">Second Year</option>
        <option value="Third Year">Third Year</option>
        <option value="Fourth Year">Fourth Year</option>
    </select>
</div>

                <div class="col-md-6">
                    <label for="Stipend" class="form-label">Stipend</label>
                    <input type="number" class="form-control" id="Stipend" name="Stipend" required>
                </div>
                <div class="col-md-6">
                    <label for="Location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="Location" name="Location" required>
                </div>

                <div class="container text-center">
                    <div class="row mx-auto">
                        <div class="col mt-3">
                            <button class="btn btn-primary btn-lg col-md-12" name="submit" role="button">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
