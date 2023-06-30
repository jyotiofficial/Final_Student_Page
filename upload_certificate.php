<?php
// upload_certificate.php
// Assuming you have a database connection established

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the application ID and uploaded file
    $applicationID = $_POST['application_id'];
    $file = $_FILES['certificate'];

    // Check if a file is uploaded
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Define the target directory to store the uploaded files
        $targetDir = 'certificate_uploads/';

        // Create the target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate a unique file name for the uploaded certificate
        $fileName = uniqid('certificate_') . '_' . $file['name'];
        $targetPath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Update the corresponding database record with the certificate file path
            $query = "UPDATE internship_applications SET Certificate_LOR = ? WHERE ID = ?";
            $stmt = $db_connection->prepare($query);
            $stmt->bind_param("si", $targetPath, $applicationID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Redirect to the previous applications page with a success message
                header("Location: ../student/previous.php?success=1&note=Certificate successfully uploaded.");
                exit();
            } else {
                // Redirect to the previous applications page with a failure message
                header("Location: ../student/previous.php?failure=1");
                exit();
            }
        } else {
            // Redirect to the previous applications page with an error message
            header("Location: ../student/previous.php?error=1");
            exit();
        }
    } else {
        // Redirect to the previous applications page with an error message
        header("Location: ../student/previous.php?error=1");
        exit();
    }
}
?>
