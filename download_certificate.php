<?php
// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship_portal";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the certificate file name from the query string
if (isset($_GET['file'])) {
    $certificateFileName = $_GET['file'];

    // Retrieve the certificate record from the database using prepared statement
    $sql = "SELECT certificate_file FROM internship_certificates WHERE certificate_file = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $certificateFileName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // File exists, so send it for download
        $row = $result->fetch_assoc();
        $filePath = $row['certificate_file'];

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $certificateFileName . '"');
        readfile($filePath);
    } else {
        http_response_code(404);
        die('Certificate not found.');
    }
} else {
    http_response_code(400);
    die('Invalid request.');
}

// Close the database connection
$stmt->close();
$conn->close();
?>
