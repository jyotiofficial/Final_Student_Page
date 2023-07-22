<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

if (isset($_POST['submit'])) {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'internship_portal');

    // Try connecting to the Database
    $db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the connection
    if ($db_connection === false) {
        die('Error: Cannot connect');
    }

    // Get the group details
    $CompanyName = mysqli_real_escape_string($db_connection, $_POST['CompanyName']);
    $CompanyAddress = mysqli_real_escape_string($db_connection, $_POST['CompanyAddress']);
    $CompanyLocation = mysqli_real_escape_string($db_connection, $_POST['CompanyLocation']);
    $startDate = mysqli_real_escape_string($db_connection, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($db_connection, $_POST['endDate']);
    $branch = mysqli_real_escape_string($db_connection, $_POST['branch']);
    $semester = mysqli_real_escape_string($db_connection, $_POST['semester']);
    $Stipend = mysqli_real_escape_string($db_connection, $_POST['Stipend']);
    $Location = mysqli_real_escape_string($db_connection, $_POST['Location']);

    // Prepare the query for group details
    $groupQuery = "INSERT INTO internship_applications (CompanyName, CompanyAddress, CompanyLocation, startDate, endDate, branch, semester, Stipend, Location) 
              VALUES ('$CompanyName', '$CompanyAddress', '$CompanyLocation', '$startDate', '$endDate', '$branch', '$semester', '$Stipend', '$Location')";

    // Execute the group details query
    if (mysqli_query($db_connection, $groupQuery)) {
        $groupSuccess = true;
        $groupId = mysqli_insert_id($db_connection); // Get the ID of the inserted group application
    } else {
        $groupSuccess = false;
    }

    // Get the student details
    $studentNames = $_POST['studentName'];
    $studentAdmNumbers = $_POST['studentAdmNumber'];

    // Prepare the query for student details
    $studentQuery = "INSERT INTO group_students (groupId, studentName, studentAdmNumber) VALUES ";

    // Generate the values for the student details query
    $values = array();
    for ($i = 0; $i < count($studentNames); $i++) {
        $name = mysqli_real_escape_string($db_connection, $studentNames[$i]);
        $AdmNumber = mysqli_real_escape_string($db_connection, $studentAdmNumbers[$i]);
        $values[] = "('$groupId', '$name', '$AdmNumber')";
    }

    // Combine the values and execute the student details query
    if (!empty($values)) {
        $studentQuery .= implode(", ", $values);
        if (mysqli_query($db_connection, $studentQuery)) {
            $studentSuccess = true;
        } else {
            $studentSuccess = false;
        }
    }

    // Close the database connection
    mysqli_close($db_connection);

    // Redirect to a success or failure page
    if ($groupSuccess && $studentSuccess) {
        header("Location: success.php"); // Redirect to success page
        exit();
    } else {
        header("Location: failure.php"); // Redirect to failure page
        exit();
    }
}
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Group Application</p>
    </div>

    <!-- Display success or failure messages -->
    <?php if (isset($_POST['submit'])): ?>
        <?php if ($groupSuccess && $studentSuccess): ?>
            <div class="alert alert-success container col-8" role="alert">
                <h2 class="alert-heading">Application Success</h2>
                <hr>
                <p>Your group application has been submitted successfully.</p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger container col-8" role="alert">
                <h2 class="alert-heading">Application Failed</h2>
                <hr>
                <p><b>Something went wrong</b><br>
                We cannot register your application at this moment. Please try again later or contact TPO for further updates.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="container my-3" id="content">
        <div class="bg-light p-5 rounded">
            <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">

                <!-- Form fields -->
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
                    <label for="Stipend" class="form-label">Stipend</label>
                    <input type="number" class="form-control" id="Stipend" name="Stipend" required>
                </div>
                <div class="col-md-6">
                    <label for="Location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="Location" name="Location" required>
                </div>

                <!-- Student details -->
                <div class="col-md-12">
                    <h4 class="mb-3">Student Details</h4>
                </div>

                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="col-md-6">
                        <label for="studentName<?php echo $i ?>" class="form-label">Student Name <?php echo $i ?></label>
                        <input type="text" class="form-control" id="studentName<?php echo $i ?>" name="studentName[]" required>
                    </div>
                    <div class="col-md-6">
                        <label for="studentRollNumber<?php echo $i ?>" class="form-label">Admission Number <?php echo $i ?></label>
                        <input type="text" class="form-control" id="studentAdmNumber<?php echo $i ?>" name="studentAdmNumber[]" required>
                    </div>
                <?php endfor; ?>

                <!-- Submit button -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="submit">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
