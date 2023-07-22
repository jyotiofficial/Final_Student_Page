<?php
$title = "Dashboard";
$style = "./styles/global.css";
$favicon = "../../assets/favicon.ico";
include_once("../../components/head.php");

// Database connection setup - Replace with your database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "internship_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student_name from the 'applications' table
$student_name = "";
$sql = "SELECT student_name FROM applications WHERE id = 1"; // Assuming you want to fetch the name for the application with ID 1
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $student_name = $row["student_name"];
}

$conn->close();
?>

<body>
    <?php include_once("../../components/navbar/index.php"); ?>

    <div class="container my-2 greet">
        <?php
        // Display the fetched student name
        if (!empty($student_name)) {
            echo "<p>Welcome, " . $student_name . "</p>";
        } else {
            echo "<p>Welcome, Guest</p>";
        }
        ?>
    </div>

    <div class="container text-center">
        <div class="row mx-auto">
            <div class="col mt-3">
                <div class="dropdown">
                    <button class="btn btn-primary btn-lg dropdown-toggle col-md-12 p-sm-4" type="button" id="applicationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Application
                    </button>
                    <ul class="dropdown-menu text-center" aria-labelledby="applicationDropdown" style="min-width: 100%;">
                        <li><a class="dropdown-item" href="./new_individual.php?id=1">Individual Application</a></li>
                        <li><a class="dropdown-item" href="./new_group.php?id=2">Group Application</a></li>
                    </ul>
                </div>
            </div>
            <div class="col my-3">
                <a href="./previous.php" class="btn btn-warning btn-lg col-md-12 p-sm-4" role="button">Previous
                    Applications</a>
            </div>
        </div>
    </div>

    <?php
    include_once("../../components/announcement/index.php");
    ?>

    <?php
    include_once("../../components/student-profile/index.php");
    ?>

    <div class="gj"></div>

    <style>
        .cv {
            height: 100vh;
            width: 100vw;
            z-index: 10000;
            position: absolute;
            top: 0;
        }
    </style>

    <script>
        const gj = document.querySelector('.gj')
        gj.addEventListener('click', () => {
            gj.classList.add('cv');
        })
    </script>
</body>
</html>
