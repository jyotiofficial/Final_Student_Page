<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $sql = "INSERT INTO internship_certificates (student_id, certificate_type, certificate_file, certificate_view) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Convert the PDF file content to binary data
    $certificateView = file_get_contents($_FILES['certificate_file']['tmp_name']);

    // Bind parameters
    $stmt->bind_param("sssb", $studentId, $certificateType, $certificateFileName, $certificateView);
    
    if ($stmt->execute() === false) {
        die("Error: " . $sql . "<br>" . $conn->error);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Display the generated certificate or success message
    echo "Certificate generated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Certificate</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Validate certificate file upload
            $('form').submit(function(e) {
                var certificateFile = $('#certificate_file').val();
                
                if (certificateFile === '') {
                    alert('Please upload a certificate file.');
                    e.preventDefault();
                }
            });
        });
    </script>
    <style>
        /* ... Your CSS styles here ... */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 450px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="radio"],
        .form-group input[type="file"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }

        /* Styles for the radio buttons and their labels */
        .radio-group {
            display: flex;
            align-items: center;
        }

        .radio-group input[type="radio"] {
            margin-right: 5px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Generate Certificate</h2>
        <form action="generate_certificate.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" id="student_id" required>
            </div>

            <div class="form-group">
                <label for="certificate_type">Certificate Type:</label>
                <div class="radio-group">
                    <input type="radio" name="certificate_type" value="Insider" required>
                    <label for="certificate_type">Insider</label>
                </div>
                <div class="radio-group">
                    <input type="radio" name="certificate_type" value="Outsider" required>
                    <label for="certificate_type">Outsider</label>
                </div>
            </div>

            <div class="form-group">
                <label for="certificate_file">Certificate File:</label>
                <input type="file" id="certificate_file" name="certificate_file" required accept=".pdf">
            </div>

            <button class="submit-btn" type="submit">Generate Certificate</button>
        </form>
    </div>
</body>
</html>
