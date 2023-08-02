<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

// No need to start session here since it's already started in "connect.php"

include "../../connect/connect.php";

$user_email = $_SESSION["email"] ?? null; // Use the null coalescing operator to handle the case when $_SESSION["email"] is not set
// Rest of the code remains unchanged...

// Retrieve the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : 1;

// Retrieve the announcement title from the new_annoucement table
$sql = "SELECT announcement_title FROM new_annoucement WHERE announcement_id = $id";
$result = $db_connection->query($sql);

// Initialize the variable
$announcementTitle = "";

// Check if there is any announcement title
if ($result && $result->num_rows > 0) {
    // Fetch the announcement title
    $row = $result->fetch_assoc();
    $announcementTitle = $row['announcement_title'];
} else {
    $announcementTitle = "XYZ Pvt Ltd"; // Set a default announcement title
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
        $uploadFolder = "./CV_Uploads/";

        //original filename
        $originalFilename = $resume['name'];

        // Generate a filename based on the given format
        $filename = $userName . "" . $announcementTitle . "" . $admissionNo . ".pdf";

        //Read contents of the uploadled file 
        $fileContents = file_get_contents($resume['tmp_name']);

        //convert the file contents to base64
        $pdfUrl = "data:application/pdf;base64," . base64_encode($fileContents);

     
        // Move the uploaded file to the target directory
        if (move_uploaded_file($resume['tmp_name'], $uploadFolder . $filename)) {
           
            $sql = "INSERT INTO applications (student_name, admission_no, contact_no, student_location, cv_file, application_date, company_name, announcement_id, resume, email) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
            $stmt = $db_connection->prepare($sql);

            // $stmt->bind_param("ssssssiss", $userName, $admissionNo, $contact, $studentLocation, $filename, $announcementTitle, $id, $pdfUrl, $user_email);
            // $stmt->execute();
            // $stmt->close();
            if (!$stmt) {
                die("Error: " . $db_connection->error); // Display error if prepare() fails
            }

            $stmt->bind_param("ssssssiss", $userName, $admissionNo, $contact, $studentLocation, $filename, $announcementTitle, $id, $pdfUrl, $user_email);
            
            if (!$stmt->execute()) {
                die("Error: " . $stmt->error); // Display error if execute() fails
            }
            
            $stmt->close();
                // Display success message
            $successMessage = "Successfully applied for $announcementTitle.<br>You have successfully registered for $announcementTitle. Please keep checking your email inbox for further updates.";
        } else {
            
            $errorMessage = "Please select a valid PDF file.";
            }}}
      

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