<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

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

// Close the database connection
mysqli_close($connection);
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
