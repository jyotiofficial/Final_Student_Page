<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");
require '../../Libraries/fpdf/fpdf.php';

// Connect to your database (replace "your_host", "your_username", "your_password", and "your_database" with the appropriate values)
$connection = mysqli_connect("localhost", "root", "", "internship_portal");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if a search query is submitted
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // Query to fetch specific applications based on ID from the "internship_applications" table (adjust the query as per your table structure)
    $query = "SELECT * FROM internship_applications WHERE ID = '$searchQuery'";
} else {
    // Query to fetch all previous applications from the "internship_applications" table (adjust the query as per your table structure)
    $query = "SELECT * FROM internship_applications";
}

// Execute the query
$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch all rows from the result as an associative array
$previousApplications = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Function to generate and save the letter as a PDF
function generateLetter($refrenceNumber, $date, $name, $applicationID, $start_date, $end_date, $year, $branch, $academicYear, $company, $companyaddress)
{
    // Create a new PDF document
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
    $pdfFilePath = './letters/' . $pdfFileName;
    $pdf->Output($pdfFilePath, "F");

    // Return the file path to be saved in the database
    return $pdfFilePath;
}

// ... (previous code remains the same) ...
foreach ($previousApplications as $application) {
    // ...

    // Generate the letter content and save as PDF
    $letterFilePath = generateLetter(
        isset($application['RefrenceNumber']) ? $application['RefrenceNumber'] : '',
        isset($application['Date']) ? $application['Date'] : '',
        isset($application['Name']) ? $application['Name'] : '',
        $application['ID'],
        isset($application['startDate']) ? $application['startDate'] : '',
        isset($application['endDate']) ? $application['endDate'] : '',
        isset($application['Year']) ? $application['Year'] : '',
        isset($application['Branch']) ? $application['Branch'] : '',
        isset($application['AcademicYear']) ? $application['AcademicYear'] : '',
        isset($application['CompanyName']) ? $application['CompanyName'] : '',
        isset($application['CompanyAddress']) ? $application['CompanyAddress'] : ''
    );

    // Update the database with the generated letter path
    $updateQuery = "UPDATE internship_applications SET Letter = '$letterFilePath' WHERE ID = " . $application['ID'];
    mysqli_query($connection, $updateQuery);

    // ...
}

// Check if $previousApplications is not empty before using it in the foreach loop
if (!empty($previousApplications)) {
    foreach ($previousApplications as $application) {
        // Check if the 'Status' key exists in the $application array
        if (isset($application['Status'])) {
            // Assign the value of the 'Status' key to the $status variable
            $status = $application['Status'];
        } else {
            // Set a default value for $status (e.g., 'pending')
            $status = 'pending';
        }

        // ... (previous columns remain the same) ...
        $tdContent = '';
        if ($status === 'rejected') {
            // Displaying an empty cell for "Rejected" status
            $tdContent = '---';
        } elseif ($status === 'approved') {
            if (!empty($application['Letter'])) {
                // "View" button for "Approved" status with no download access
                $tdContent = '<a href="' . $application['Letter'] . '" target="_blank">View Letter</a>';
            } else {
                // Generate the letter content and save as PDF
                $letterFilePath = generateLetter(
                    isset($application['RefrenceNumber']) ? $application['RefrenceNumber'] : '',
                    isset($application['Date']) ? $application['Date'] : '',
                    isset($application['Name']) ? $application['Name'] : '',
                    $application['ID'],
                    isset($application['startDate']) ? $application['startDate'] : '',
                    isset($application['endDate']) ? $application['endDate'] : '',
                    isset($application['Year']) ? $application['Year'] : '',
                    isset($application['Branch']) ? $application['Branch'] : '',
                    isset($application['AcademicYear']) ? $application['AcademicYear'] : '',
                    isset($application['CompanyName']) ? $application['CompanyName'] : '',
                    isset($application['CompanyAddress']) ? $application['CompanyAddress'] : ''
                );

                // Update the database with the generated letter path
                $updateQuery = "UPDATE internship_applications SET Letter = '$letterFilePath' WHERE ID = " . $application['ID'];
                mysqli_query($connection, $updateQuery);

                // "View" button for the generated letter with no download access
                $tdContent = '<a href="' . $letterFilePath . '" target="_blank">View Letter</a>';
            }
        } else {
            // "No Letter" for "Pending" status
            $tdContent = 'No Letter';
        }

        // ... (remaining columns remain the same) ...
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $style; ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon; ?>">
    <style>
        .status-pending {
            color: gray;
        }

        .status-approved {
            color: green;
        }

        .status-rejected {
            color: red;
        }
    </style>
</head>
<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <p>Previous Applications</p>
    </div>

    <div class="container my-3" id="content">
        <form class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by ID" value="<?php echo $searchQuery; ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">
                <strong>Success!</strong> Letter uploaded successfully.
            </div>
        <?php endif; ?>

        <div class="bg-light rounded">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Letter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($previousApplications as $application): ?>
                        <tr>
                            <td><?php echo $application['ID']; ?></td>
                            <td><?php echo $application['CompanyName']; ?></td>
                            <td><?php echo $application['startDate']; ?></td>
                            <td>
                                <?php
                                // Ensure the "Status" value is one of the specified options
                                $status = $application['Status'];
                                if ($status === 'approved' || $status === 'rejected') {
                                    echo '<span class="status-' . $status . '">' . ucfirst($status) . '</span>';
                                } else {
                                    echo '<span class="status-pending">Pending</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($status === 'rejected'): ?>
                                    <!-- Displaying an empty cell for "Rejected" status -->
                                    <?php echo '---'; ?>
                                <?php elseif ($status === 'approved' && !empty($application['Letter'])): ?>
                                    <!-- "View" button for "Approved" status with no download access -->
                                    <a href="<?php echo $application['Letter']; ?>" target="_blank">View Letter</a>
                                <?php else: ?>
                                    <!-- "No Letter" for "Approved" status with no download access -->
                                    No Letter
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($status === 'approved' && empty($application['Letter'])): ?>
                                    <!-- Upload form for "Approved" status with no existing letter -->
                                    <form action="upload_letter.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="application_id" value="<?php echo $application['ID']; ?>">
                                        <input type="file" name="letter" accept=".pdf" required>
                                        <button type="submit">Upload</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
