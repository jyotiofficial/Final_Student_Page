<?php
// Connect to the database
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
    $certificateFileName = basename($_GET['file']); // Sanitize the file name

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT certificate_file FROM internship_certificates WHERE certificate_file = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $certificateFileName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // File exists, fetch PDF data from the database
        $row = $result->fetch_assoc();
        $pdfData = $row['certificate_file'];

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Send PDF data to the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $certificateFileName . '"');
        echo $pdfData;
        exit;
    } else {
        http_response_code(404);
        die('Certificate not found.');
    }
} else {
    http_response_code(400);
    die('Invalid request.');
}
?>
