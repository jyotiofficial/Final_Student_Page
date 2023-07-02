<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'internship_portal');

// Try connecting to the Database
$db_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($db_connection === false) {
    die('Error: Cannot connect to the database');
}

// Check if the new application form is submitted
if (isset($_POST['submit'])) {
    $CompanyName = mysqli_real_escape_string($db_connection, $_POST['CompanyName']);
    $CompanyAddress = mysqli_real_escape_string($db_connection, $_POST['CompanyAddress']);
    $CompanyLocation = mysqli_real_escape_string($db_connection, $_POST['CompanyLocation']);
    $startDate = mysqli_real_escape_string($db_connection, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($db_connection, $_POST['endDate']);
    $branch = mysqli_real_escape_string($db_connection, $_POST['branch']);
    $semester = mysqli_real_escape_string($db_connection, $_POST['semester']);
    $Stipend = mysqli_real_escape_string($db_connection, $_POST['Stipend']);
    $Location = mysqli_real_escape_string($db_connection, $_POST['Location']);

    // Insert the new application data into the database
    $query = "INSERT INTO internship_applications (CompanyName, CompanyAddress, CompanyLocation, StartDate, EndDate, Branch, Semester, Stipend, Location) VALUES ('$CompanyName', '$CompanyAddress', '$CompanyLocation', '$startDate', '$endDate', '$branch', '$semester', '$Stipend', '$Location')";
    $result = mysqli_query($db_connection, $query);

    if ($result) {
        // Redirect to a success page
        header("Location: success.php");
        exit();
    } else {
        // Redirect to a failure page
        header("Location: failure.php");
        exit();
    }
}

// Fetch previous applications from the database
$query = "SELECT * FROM internship_app";
$result = mysqli_query($db_connection, $query);
$previousApplications = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>New Application</p>
    </div>

    <!-- Display success or failure messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success container col-8" role="alert">
            <h2 class="alert-heading">Application Success</h2>
            <hr>
            <p>You have successfully submitted the application.</p>
        </div>
    <?php elseif (isset($_GET['failure'])): ?>
        <div class="alert alert-danger container col-8" role="alert">
            <h2 class="alert-heading">Application Failed</h2>
            <hr>
            <p>Something went wrong. Please try again later.</p>
        </div>
    <?php endif; ?>

    <div class="container my-3" id="content">
        <div class="bg-light p-5 rounded">
            <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
                <!-- Form fields -->
                <!-- ... -->

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

    <div class="container my-2 greet">
        <p>Previous Applications</p>
    </div>

    <div class="container my-3" id="content">
        <div class="bg-light rounded">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Certificate/LOR</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($previousApplications as $application): ?>
                        <tr>
                            <td><?php echo $application['ID']; ?></td>
                            <td><?php echo $application['CompanyName']; ?></td>
                            <td><?php echo $application['Date']; ?></td>
                            <td><?php echo $application['Status']; ?></td>
                            <td><?php echo $application['Comment']; ?></td>
                            <td>
                                <?php if (!empty($application['CertificateLOR'])): ?>
                                    <a href="<?php echo $application['CertificateLOR']; ?>" target="_blank">View Certificate</a>
                                <?php else: ?>
                                    No Certificate
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($application['CertificateLOR'])): ?>
                                    <a href="<?php echo $application['CertificateLOR']; ?>" download class="btn btn-primary">Download Certificate</a>
                                <?php else: ?>
                                    <!-- Upload certificate form -->
                                    <form action="upload_certificate.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="application_id" value="<?php echo $application['ID']; ?>">
                                        <input type="file" name="certificate" required>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
