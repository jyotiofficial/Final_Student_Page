<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

include "../../connect/connect.php";

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // ... (previous code here)

    // Retrieve the announcement ID based on the announcement title
    $sqlGetAnnouncementId = "SELECT announcement_id FROM new_announcement WHERE announcement_title = ?";
    $stmtGetAnnouncementId = $db_connection->prepare($sqlGetAnnouncementId);
    $stmtGetAnnouncementId->bind_param("s", $announcementTitle);
    $stmtGetAnnouncementId->execute();
    $resultGetAnnouncementId = $stmtGetAnnouncementId->get_result();

    // Initialize the variable
    $announcementId = 1; // Set a default announcement ID

    // Check if there is any announcement with the given title
    if ($resultGetAnnouncementId && $resultGetAnnouncementId->num_rows > 0) {
        // Fetch the announcement ID
        $rowGetAnnouncementId = $resultGetAnnouncementId->fetch_assoc();
        $announcementId = $rowGetAnnouncementId['announcement_id'];
    } else {
        // If no announcement is found with the given title, insert a new record with the announcement title
        $sqlInsertNewAnnouncement = "INSERT INTO new_announcement (announcement_title) VALUES (?)";
        $stmtInsertNewAnnouncement = $db_connection->prepare($sqlInsertNewAnnouncement);
        $stmtInsertNewAnnouncement->bind_param("s", $announcementTitle);
        $stmtInsertNewAnnouncement->execute();
        $stmtInsertNewAnnouncement->close();

        // Retrieve the newly inserted announcement ID
        $announcementId = $db_connection->insert_id;
    }

    // Now $announcementId holds the correct announcement_id associated with the provided announcement_title

    // Move the uploaded file to the target directory
    if (move_uploaded_file($resume['tmp_name'], $targetDirectory . $filename)) {
        // Insert the data into the "Applications" table
        $sql = "INSERT INTO applications (student_name, admission_no, contact_no, student_location, cv_file, application_date, company_name, announcement_id) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $db_connection->prepare($sql);
        $stmt->bind_param("ssssssi", $userName, $admissionNo, $contact, $studentLocation, $filename, $announcementTitle, $announcementId);
        $stmt->execute();
        $stmt->close();

        // Display success message
        $successMessage = "Successfully applied for $announcementTitle.<br>You have successfully registered for $announcementTitle. Please keep checking your email inbox for further updates.";
    } else {
        // Display error message
        $errorMessage = "Failed to move the uploaded file.";
    }

    // Close the statement
    $stmtGetAnnouncementId->close();
}


// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve the form data
    $userName = $_POST['userName'];
    $admissionNo = $_POST['admissionNo'];
    $contact = $_POST['Contact'];
    $studentLocation = $_POST['StudentLocation'];
    $resume = $_FILES['resume'];

    // Check if a file is selected
    if (isset($resume) && $resume['error'] === UPLOAD_ERR_OK) {
        // Specify the target directory to store the uploaded files
        $targetDirectory = __DIR__. "/CV_Uploads/";

        // Generate a unique filename based on the given format
        $filename = str_replace(' ', '_', $userName) . "_" . str_replace(' ', '_', $announcementTitle) . "_" . str_replace(' ', '_', $admissionNo) . ".pdf";

        // Move the uploaded file to the target directory
        if (move_uploaded_file($resume['tmp_name'], $targetDirectory . $filename)) {
            // Insert the data into the "Applications" table
            $sql = "INSERT INTO applications (student_name, admission_no, contact_no, student_location, cv_file, application_date, company_name, announcement_id) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)";
            $stmt = $db_connection->prepare($sql);
            $stmt->bind_param("ssssssi", $userName, $admissionNo, $contact, $studentLocation, $filename, $announcementTitle, $id);
            $stmt->execute();
            $stmt->close();

            // Display success message
            $successMessage = "Successfully applied for $announcementTitle.<br>You have successfully registered for $announcementTitle. Please keep checking your email inbox for further updates.";
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
$db_connection->close();
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Applying for <?php echo $announcementTitle; ?></p>
    </div>

    <div class="container my-3" id="content">
        <div class="container my-3 text-justify" id="content">
            <div class="bg-light p-5 rounded">
                <?php if (isset($successMessage)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php elseif (isset($errorMessage)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>  

                <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
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
                        <strong for="resume" class="form-label">Upload CV</strong>
                        <input type="file" accept=".pdf" class="form-control" spellcheck="false" required autocomplete="off" name="resume" id="resume">
                        <br>
                        <div class="text">
                            <strong for="resume" class="form-label">Note! :-</strong>
                            <small for="resume" class="form-label">
                                <i>
                                    CV format
                                    <br>
                                    <b class="text-danger bg-warning">Student-name_Company-name_Admission-no.pdf</b>
                                    <br>
                                    (JohnDoe_MarkIndustries_2000PE0400.pdf)
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
