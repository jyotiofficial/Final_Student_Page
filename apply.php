<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship_portal";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the announcement titles from the new_announcement table
$sql = "SELECT announcement_id, announcement_title FROM new_announcement";
$result = $conn->query($sql);

// Prepare the success and error message variables
$successMessage = "";
$errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $userName = $_POST["userName"];
    $admissionNo = $_POST["admissionNo"];
    $contactNo = $_POST["Contact"];
    $studentLocation = $_POST["StudentLocation"];
    $announcementId = $_POST["announcementId"];
    $resume = $_FILES["resume"];

    // Check if a file is selected
    if (isset($resume) && $resume["error"] === UPLOAD_ERR_OK) {
        // Specify the target directory to store the uploaded files
        $targetDirectory = __DIR__ . "/CV_Uploads/";

        // Retrieve the selected announcement title based on the announcement ID
        $stmt = $conn->prepare("SELECT announcement_title FROM new_announcement WHERE announcement_id = ?");
        $stmt->bind_param("i", $announcementId);
        $stmt->execute();
        $stmt->bind_result($announcementTitle);
        $stmt->fetch();
        $stmt->close();

        // Generate a unique filename based on the given format
        $filename = $userName . "_" . $announcementTitle . "_" . $admissionNo . ".pdf";

        // Move the uploaded file to the target directory
        if (move_uploaded_file($resume["tmp_name"], $targetDirectory . $filename)) {
            // Insert the data into the "Applications" table
$sql = "INSERT INTO Applications (student_name, admission_no, contact_no, student_location, resume, cv_file, company_name, application_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $userName, $admissionNo, $contactNo, $studentLocation, $filename, $filename, $announcementTitle);
$stmt->execute();
$stmt->close();


            // Display success message
            $successMessage = "Applying for " . $announcementTitle . " has been successful.";
                } else {
            // Display error message
            $errorMessage = "Failed to move the uploaded file.";
        }
    } else {
        // Display error message
        $errorMessage = "Please select a valid PDF file.";
    }
}

// Close the database connection
$conn->close();
?>

<body>
    <?php
    include_once("../../components/navbar/index.php");
    ?>

    <div class="container my-2 greet">
        <p>Applying for <?php echo isset($announcementTitle) ? $announcementTitle : ""; ?></p>
    </div>

    <div class="container my-3" id="content">
        <div class="container my-3 text-justify" id="content">
            <div class="bg-light p-5 rounded">
                <?php if (!empty($successMessage)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errorMessage)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <form class="row g-3" action="<?php echo htmlentities($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                    <div class="col-12">
                        <strong for="userName" class="form-label">Student Full Name</strong>
                        <input type="text" class="form-control" spellcheck="false" required autocomplete="off" name="userName" id="userName" placeholder="John Richard Doe">
                    </div>
                    <div class="col-12">
                        <strong for="admissionNo" class="form-label">Admission Number</strong>
                        <input type="text" class="form-control" spellcheck="false" required autocomplete="off" name="admissionNo" id="admissionNo" placeholder="2099SM4004">
                    </div>
                    <div class="col-12">
                        <strong for="Contact" class="form-label">Contact No.</strong>
                        <input type="text" class="form-control" spellcheck="false" required autocomplete="off" name="Contact" id="Contact" placeholder="987654210">
                    </div>
                    <div class="col-12">
                        <strong for="StudentLocation" class="form-label">Student Location</strong>
                        <input type="text" class="form-control" spellcheck="false" required autocomplete="off" name="StudentLocation" id="StudentLocation" placeholder="e.g. Panvel">
                    </div>
                    <div class="col-12">
                        <strong for="announcementId" class="form-label">Company Name (Announcement Title)</strong>
                        <select class="form-select" name="announcementId" id="announcementId" required>
                            <option value="">Select Announcement</option>
                            <?php
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['announcement_id'];
                                    $title = $row['announcement_title'];
                                    echo "<option value=\"$id\">$title</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <strong for="resume" class="form-label">Upload CV</strong>
                        <input type="file" accept=".pdf" class="form-control" spellcheck="false" required autocomplete="off" name="resume" id="resume">
                        <br>
                        <div class="text">
                            <strong for="resume" class="form-label">Note! :-</strong>
                            <small for="resume" class="form-label">
                                <i>
                                    CV format
                                    <br>
                                    <b class="text-danger bg-warning">Student-name_Announcement-title_Admission-no.pdf</b>
                                    <br>
                                    (JohnDoe_<?php echo isset($announcementTitle) ? $announcementTitle : ""; ?>_2000PE0400.pdf)
                                </i>
                            </small>
                        </div>
                    </div>
                    <div class="container text-center">
                        <div class="row mx-auto">
                            <div class="col mt-3">
                                <button class="btn btn-primary btn-lg col-md-12" role="button" name="submit">Apply</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
