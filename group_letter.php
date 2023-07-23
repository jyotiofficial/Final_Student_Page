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

// Fetch the value of 'student_name' and 'application_date' from table 'applications'
$tableName = 'applications';
$columnName = 'student_name, application_date';
$query = "SELECT $columnName FROM $tableName";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $studentName = $row['student_name']; // Fetch the 'student_name'
    $applicationDate = $row['application_date']; // Fetch the 'application_date'
} else {
    echo "Failed to fetch student name and application date.";
}

// Fetch the value of 'ID', 'startDate', 'endDate', 'branch', 'semester', 'CompanyName', and 'CompanyAddress' from table 'internship_applications'
$tableName = 'internship_applications';
$columnName = 'ID, startDate, endDate, branch, semester, CompanyName, CompanyAddress';
$query = "SELECT $columnName FROM $tableName ORDER BY ID DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result->num_rows > 0) {
    $row = mysqli_fetch_assoc($result);

    // Extract values from the fetched data
    $refrenceNumber = "CE/INTERN/" . sprintf("%04d", intval($row['ID'])) . "/" . date('Y') . "-" . (date('y') + 1);
    $date = $applicationDate;
    $start_date = $row['startDate'];
    $end_date = $row['endDate'];
    $branch = $row['branch'];
    $semester = $row['semester'];
    $company = $row['CompanyName'];
    $companyaddress = $row['CompanyAddress'];

    // Set default values if data is not found in the database
    if (!isset($degree)) {
        $degree = "Bachelor of Engineering";
    }

    if (!isset($degreeYears)) {
        $degreeYears = "4 Years";
    }

    // Fetch intern names and group IDs from 'group_students' table
    $tableName = 'group_students';
    $columnName = 'studentName, groupId';
    $query = "SELECT $columnName FROM $tableName";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $internNames = array();
        $groupID = null;
        while ($row = mysqli_fetch_assoc($result)) {
            $internNames[] = $row['studentName'];
            if ($groupID === null) {
                $groupID = $row['groupId'];
            }
        }
    } else {
        echo "Failed to fetch intern names and group IDs.";
    }

    // Close the database connection
    $conn->close();

    // Create the PDF
    $pdf = new FPDF('P', 'mm', 'Letter');
    $pdf->SetLeftMargin(25);
    $pdf->SetRightMargin(25);
    $pdf->SetTopMargin(25);

    $pdf->AddPage();
    $pdf->SetFont('Times', '');
    $pdf->Cell(70, 20, "Ref. No.: " . $refrenceNumber, 0, 0, "L");
    $pdf->Cell(90, 20, "Date: " . $date, 0, 1, "R");
    $pdf->SetFont('Times', 'B');
    $pdf->Cell(60, 6, $company, 0, 1, "L");
    $pdf->Cell(60, 6, $companyaddress, 0, 1, "L");
    $pdf->SetFont('Times', '');
    $pdf->Cell(0, 5, "", 0, 1);
    $pdf->Cell(50, 15, "Subject :", 0, 0, "R");
    $pdf->SetFont('Times', 'BU');
    $pdf->Cell(80, 15, "Permission for Internship Training.", 0, 1, "L");
    $pdf->SetFont('Times', '');
    $pdf->Cell(70, 15, "Dear Sir,", 0, 1, "L");

    // Using the fetched intern names and the groupID
    $pdf->Write(8, "With reference to the above subject, the following students of semester ".$semester.", ".$branch. " would like to undertake internship training in your esteemed organization:");
$pdf->Cell(0, 10, "", 0, 1);
$pdf->SetLeftMargin(35);
$pdf->SetFont('Times', 'B');
    $pdf->SetLeftMargin(45);
    for ($i = 0; $i < count($internNames); $i++) {
        $pdf->Write(8, chr(97 + $i) . ") " . $internNames[$i]);
        $pdf->Ln(8);
    }

    $pdf->SetLeftMargin(55);
    $pdf->Write(8, "Group ID: " . $groupID);
    $pdf->Ln(8);

    $pdf->SetLeftMargin(35);
    $pdf->Cell(0, 2, "", 0, 1);

    $pdf->SetLeftMargin(35);
    $pdf->Cell(0, 10, "2) Degree: " . $degree, 0, 1);
    $pdf->Cell(0, 10, "3) Total No. of Years of the Degree Programme: " . $degreeYears, 0, 1);
    $pdf->Cell(0, 10, "4) Discipline/Subject: " . $branch, 0, 1);
    $pdf->Cell(0, 10, "5) Desirable Period of Training/Internship: " . $start_date . " to " . $end_date, 0, 1);
    $pdf->SetLeftMargin(25);
    $pdf->SetFont('Times', '');
    $pdf->Cell(0, 5, "", 0, 1);
    $pdf->Write(8, "We will be grateful if your esteemed organization would help us to provide practical training to our students.");
    $pdf->Cell(0, 10, "", 0, 1);
    $pdf->Write(8, "This certificate is issued on request of the students for Internship purpose.");
    $pdf->Cell(0, 10, "", 0, 1);
    $pdf->Write(8, "Thank you.");
    $pdf->Cell(0, 10, "", 0, 1);

    $pdf->Cell(0, 10, "Yours faithfully,", 0, 1, "L");

    // Output the PDF file as inline display
    $pdf->Output("I", "Intern_Application_" . $groupID);
}
?>
