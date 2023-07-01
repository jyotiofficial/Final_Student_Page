<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve the form data
    $userName = $_POST['userName'];
    $contact = $_POST['Contact'];
    $studentLocation = $_POST['StudentLocation'];
    $resume = $_FILES['resume'];

    // Check if a file is selected
    if(isset($resume) && $resume['error'] === UPLOAD_ERR_OK) {
        // Specify the target directory to store the uploaded files
        $targetDirectory = __DIR__ . "/CV_Uploads/";

        // Generate a unique filename based on the given format
        $filename = str_replace(' ', '_', $userName) . "_" . str_replace(' ', '_', "XYZPvtLtd") . "_" . str_replace(' ', '_', "2000PE0400") . ".pdf";

        // Move the uploaded file to the target directory
        if(move_uploaded_file($resume['tmp_name'], $targetDirectory . $filename)) {
            // Display success message
            $successMessage = "Applying for XYZ Pvt Ltd has been successful.";
        } else {
            // Display error message
            $errorMessage = "Failed to move the uploaded file.";
        }
    } else {
        // Display error message
        $errorMessage = "Please select a valid PDF file.";
    }
}
?>

<body>
    <?php
    include_once("../../components/navbar/index.php");
    ?>

    <div class="container my-2 greet">
        <p>Applying for XYZ Pvt Ltd</p>
    </div>

    <div class="container my-3" id="content">
        <div class="container my-3 text-justify" id="content">
            <div class="bg-light p-5 rounded">
                <?php if(isset($successMessage)): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php elseif(isset($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php else: ?>
                    <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
                        <div class="col-12">
                            <strong for="userName" class="form-label">Student Full Name</strong>
                            <input type="text" class="form-control" spellcheck="false" required autocomplete="off" name="userName" id="userName" placeholder="John Richard Doe">
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
