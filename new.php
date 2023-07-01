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

    $CompanyName = mysqli_real_escape_string($db_connection, $_POST['CompanyName']);
    $CompanyAddress = mysqli_real_escape_string($db_connection, $_POST['CompanyAddress']);
    $CompanyLocation = mysqli_real_escape_string($db_connection, $_POST['CompanyLocation']);
    $startDate = mysqli_real_escape_string($db_connection, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($db_connection, $_POST['endDate']);
    $branch = mysqli_real_escape_string($db_connection, $_POST['branch']);
    $semester = mysqli_real_escape_string($db_connection, $_POST['semester']);
    $Stipend = mysqli_real_escape_string($db_connection, $_POST['Stipend']);
    $Location = mysqli_real_escape_string($db_connection, $_POST['Location']);

    // Insert the data into the database
    $query = "INSERT INTO internship_applications (CompanyName, CompanyAddress, CompanyLocation, startDate, endDate, branch, semester, Stipend, Location) 
              VALUES ('$CompanyName', '$CompanyAddress', '$CompanyLocation', '$startDate', '$endDate', '$branch', '$semester', '$Stipend', '$Location')";
              
    if(mysqli_query($db_connection, $query)) {
        $success = true;
    } else {
        $success = false;
    }
    
    // Close the database connection
    mysqli_close($db_connection);

    // Redirect to a success or failure page
    if ($success) {
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
        <p>Application</p>
    </div>

    <!-- Display success or failure messages -->
    <?php if (isset($_POST['submit'])): ?>
        <?php if ($success): ?>
            <div class="alert alert-success container col-8" role="alert">
                <h2 class="alert-heading">Application Success</h2>
                <hr>
                <p>You have successfully requested NOC letter for <b><?php echo $CompanyName; ?></b>.<br>
                Please keep checking your email inbox for further updates.</p>
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
                        <option value="AUTO">AUTO</option>   
                        <option value="COMP">COMP</option>
                        <option value="IT">IT</option>
                        <option value="ECS">ECS</option>   
                        <option value="EXTC">EXTC</option>
                        <option value="MECH">MECH</option>

                        <!-- Add more options as needed -->
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

                        <!-- Add more options as needed -->
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