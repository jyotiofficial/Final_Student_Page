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

// Initialize the announcement title variable
$announcementTitle = '';

// Retrieve the announcement titles and cv_file values from the tables
$sql = "SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.cv_file, '_', -2), '_', 1) AS announcement_title, a.cv_file
        FROM applications AS a
        INNER JOIN new_annoucement AS n ON n.announcement_title = SUBSTRING_INDEX(SUBSTRING_INDEX(a.cv_file, '_', -2), '_', 1)";
$result = $conn->query($sql);

// Check if there are any matching rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcementTitle = $row['announcement_title'];
        $cvFile = $row['cv_file'];

        // Update the company_name column in the applications table with the announcement title
        $updateSql = "UPDATE applications SET company_name=? WHERE cv_file=?";
        $stmtUpdate = $conn->prepare($updateSql);
        $stmtUpdate->bind_param("ss", $announcementTitle, $cvFile);
        $stmtUpdate->execute();
        $stmtUpdate->close();
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

                <form class="row g-3" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
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
