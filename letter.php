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

// Fetch the value of 'student_name' and 'application_date' from table 'individual_student'
$tableName = 'individual_student';
$columnName = 'StudentName, created_at';
$query = "SELECT $columnName FROM $tableName";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $studentName = $row['StudentName']; // Fetch the 'student_name'
    $created_at = $row['created_at']; // Fetch the 'application_date'
} else {
    echo "Failed to fetch student name and application date.";
}

// Fetch the value of 'ID', 'startDate', 'endDate', 'year', 'branch', 'AcademicYear', 'CompanyName', and 'CompanyAddress' from table 'individual_student'
$tableName = 'individual_student';
$columnName = 'ID, startDate, endDate, year, branch, AcademicYear, CompanyName, CompanyAddress';
$query = "SELECT $columnName FROM $tableName ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);

    // Extract values from the fetched data
    $refrenceNumber = "CE/INTERN/" . sprintf("%04d", intval($row['ID'])) . "/" . date('Y') . "-" . (date('y') + 1);
    $date = $created_at;
    $name = $studentName; // Using the fetched student name
    $applicationID = $row['ID'];
    $start_date = $row['startDate'];
    $end_date = $row['endDate'];
    $year = $row['year'];
    $branch = $row['branch'];
    $academicYear = $row['AcademicYear'];
    $company = $row['CompanyName'];
    $companyaddress = $row['CompanyAddress'];

    // Close the database connection
    $conn->close();

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
    $pdf->Output("I", "Intern_Application_" . $applicationID);
} else {
    echo "No data found in the database.";
}

?>
