<?php
require '../../Libraries/fpdf/fpdf.php';

// Replace these with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship_portal";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    // Display detailed error message
    die("Connection failed: " . $conn->connect_error . " (Error code: " . $conn->connect_errno . ")");
}

// Fetch the application ID from the URL parameter
if (isset($_GET['ID'])) {
    $applicationID = $_GET['ID'];
} else {
    echo "No application ID provided.";
    exit();
}

// Define the directory path where the letters are stored
$lettersDirectory = "C:/xampp/htdocs/internship-portal-final/internship-portal/pages/student/letters/"; // Adjust the path to the actual directory where the letters are stored

// Fetch the value of 'student_name' and 'application_date' from table 'applications' for the specific application ID
$tableName = 'applications';
$columnName = 'student_name';
$query = "SELECT $columnName FROM $tableName WHERE ID = $applicationID";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $studentName = $row['student_name']; // Fetch the 'student_name'
} else {
    echo "Failed to fetch student name.";
    exit();
}

// Fetch the value of 'startDate', 'endDate', 'year', 'branch', 'AcademicYear', 'CompanyName', and 'CompanyAddress' from table 'internship_applications' for the specific application ID
$tableName = 'internship_applications';
$columnName = 'startDate, endDate, year, branch, AcademicYear, CompanyName, CompanyAddress, ActionDate';
$query = "SELECT $columnName FROM $tableName WHERE ID = $applicationID";
$result = mysqli_query($conn, $query);

if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);

    // Extract values from the fetched data
    $refrenceNumber = "CE/INTERN/" . sprintf("%04d", intval($applicationID)) . "/" . date('Y') . "-" . (date('y') + 1);
    $date = $row['ActionDate'];
    $name = $studentName; // Using the fetched student name
    $start_date = $row['startDate'];
    $end_date = $row['endDate'];
    $year = $row['year'];
    $branch = $row['branch'];
    $academicYear = $row['AcademicYear'];
    $company = $row['CompanyName'];
    $companyaddress = $row['CompanyAddress'];

        // Create the PDF
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->SetLeftMargin(30);
        $pdf->SetRightMargin(30);
        $pdf->SetTopMargin(40);
        $pdf->AddPage();
        $pdf->SetFont('Times', '');
        $pdf->Cell(70, 20, "Ref. No.:" . $refrenceNumber, 0, 0, "L");
        $pdf->Cell(90, 20, $date, 0, 1, "R");
        $pdf->SetFont('Times', 'B');
        $pdf->Cell(60, 6, "Manager", 0, 1, "L");
        $pdf->SetFont('Times', '');
        $pdf->Cell(60, 6, $company, 0, 1, "L");
        $pdf->MultiCell(65, 6, $companyaddress . ",", 0, "L");
        $pdf->SetFont('Times', 'B');
        $pdf->Cell(0, 5, "", 0, 1);
        $pdf->Cell(50, 15, "Subject :", 0, 0, "R");
        $pdf->SetFont('Times', 'BU');
        $pdf->Cell(80, 15, "Permission for Internship Training.", 0, 1, "L");
        $pdf->SetFont('Times', '');
        $pdf->Cell(70, 15, "Dear Sir,", 0, 1, "L");
    
        $pdf->Write(8, "With reference to above subject, we request you to permit our student ");
        $pdf->SetFont('Times', 'B');
        $pdf->Write(8, $name);
        $pdf->SetFont('Times', '');
        $pdf->Write(8, ", who has appeared for " . $year . " ");
        $pdf->SetFont('Times', 'B');
        $pdf->Write(8, $branch);
        $pdf->SetFont('Times', '');
        $pdf->Write(8, " examinations during a.y. " . $academicYear . " to undertake internship training in your esteemed organization during their vacation ");
        $pdf->SetFont('Times', '');
        $pdf->Write(8, $start_date . " to " . $end_date);
        $pdf->SetFont('Times', '');
        $pdf->Write(8, " and also on Saturdays, Sundays, and Public Holidays, as the case may be.");
        $pdf->Cell(0, 20, "", 0, 1);
        $pdf->Write(8, "We will be grateful if your esteemed organization would help us provide practical training for our student.");
        $pdf->Cell(0, 15, "", 0, 1);
        $pdf->Write(8, "This certificate is issued on the request of the student for Internship purposes.");
        $pdf->Cell(0, 15, "", 0, 1);
    
        $pdf->Cell(0, 10, "Thank you.", 0, 1);
        $pdf->Cell(0, 20, "Yours faithfully", 0, 1);

    // Output the PDF inline
    $pdfFileName = "letter_" . $applicationID . ".pdf";
    $pdfFilePath = $lettersDirectory . $pdfFileName;
    $pdf->Output("F", $pdfFilePath); // Save the PDF file

    // Now, update the database with the file name in the 'Letter' attribute
    $escapedFileName = mysqli_real_escape_string($conn, $pdfFileName);
    $updateQuery = "UPDATE internship_applications SET Letter = '$escapedFileName' WHERE ID = '$applicationID'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if (!$updateResult) {
        die("Update query failed: " . mysqli_error($conn));
    }

// Output the PDF inline
$pdfFileName = "letter_" . $applicationID . ".pdf";
$pdfFilePath = $lettersDirectory . $pdfFileName;
if (file_exists($pdfFilePath)) {
    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=" . $pdfFileName);
    readfile($pdfFilePath);
    exit();
} else {
    echo "PDF not found for the specified application ID.";
    exit();
}
} else {
    echo "No data found in the database for the specified application ID.";
    exit();
}