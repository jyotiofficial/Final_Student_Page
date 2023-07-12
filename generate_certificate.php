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

// Get student ID and certificate type from the form submission
$studentId = isset($_POST['student_id']) ? $_POST['student_id'] : null;
$certificateType = isset($_POST['certificate_type']) ? $_POST['certificate_type'] : null;

// Check if student ID and certificate type are provided
if ($studentId === null || $certificateType === null) {
    die("Error: Student ID or certificate type is missing.");
}

// Generate the certificate file name
$certificateFileName = 'certificate_' . $studentId . '.pdf';

// Save the certificate entry in the database
$sql = "INSERT INTO internship_certificates (student_id, certificate_type, certificate_file) VALUES ('$studentId', '$certificateType', '$certificateFileName')";
if ($conn->query($sql) === false) {
    die("Error: " . $sql . "<br>" . $conn->error);
}

// Generate the certificate content and save it as a PDF file
// Code to generate certificate content goes here...

// Save the certificate PDF file on the server
// Code to save the certificate file goes here...

// Close the database connection
$conn->close();

// Display the generated certificate or success message
echo "Certificate generated successfully!";
?>
