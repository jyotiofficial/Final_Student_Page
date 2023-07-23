<?php
require_once 'C:/xampp/htdocs/internship-portal-final/internship-portal/pages/student/FPDF-master/fpdf.php';

// Configuration for dynamic elements
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
$host = "localhost";
$username = "root";
$password = "";
$database = "internship_portal";
$lettersDirectory = "C:/xampp/htdocs/internship-portal-final/internship-portal/pages/student/letters"; // Directory to save generated letters


// Function to generate and save the letter as a PDF
function generateLetter($lettersDirectory, $referenceNumber, $date, $name, $applicationID, $start_date, $end_date, $year, $branch, $academicYear, $company, $companyaddress)
{
    // Create a new PDF document
    $pdf = new FPDF('P', 'mm', 'Letter');
    $pdf->SetLeftMargin(30);
    $pdf->SetRightMargin(30);
    $pdf->SetTopMargin(40);
    $pdf->AddPage();
    $pdf->SetFont('Times', '');
    $pdf->Cell(70, 20, "Ref. No.:" . $referenceNumber, 0, 0, "L");
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

    $pdf->Write(8, "With reference to above subject we request you to permit our student ");
    $pdf->SetFont('Times', 'B');
    $pdf->Write(8, $name);
    $pdf->SetFont('Times', '');
    $pdf->Write(8, " , who have appeared for " . $year . " ");
    $pdf->SetFont('Times', 'B');
    $pdf->Write(8, $branch);
    $pdf->SetFont('Times', '');
    $pdf->Write(8, " examinations during a.y." . $academicYear . "to undertake internship training in your esteemed organization during their vacation ");
    $pdf->SetFont('Times', '');
    $pdf->Write(8, $start_date . " to " . $end_date);
    $pdf->SetFont('Times', '');
    $pdf->Write(8, " and also on Saturdays, Sundays and Public Holidays, as the case may be.");
    $pdf->Cell(0, 20, "", 0, 1);
    $pdf->Write(8, "We will be grateful if your esteemed organization would help us to provide practical training for our student.");
    $pdf->Cell(0, 15, "", 0, 1);
    $pdf->Write(8, "This certificate is issued on request of student for Internship purpose.");
    $pdf->Cell(0, 15, "", 0, 1);

    $pdf->Cell(0, 10, "Thank you.", 0, 1);
    $pdf->Cell(0, 20, "Yours faithfully", 0, 1);

    // Save the PDF to a file with a unique name
    $pdfFileName = 'letter_' . $applicationID . '.pdf';
    $pdfFilePath = $lettersDirectory . $pdfFileName;
    $pdf->Output($pdfFilePath, "F");

    // Return the file path to be saved in the database
    return $pdfFilePath;
}
?>
