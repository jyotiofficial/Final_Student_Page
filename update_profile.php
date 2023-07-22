<?php
require './../../components/student-profile/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $address = $_POST['address'] ?? '';

    if (update_existing_data($con, $fullName, $email, $age, $mobile, $address)) {
        // Redirect to the desired URL after the data update
        header('Location: http://localhost/internship-portal-final/internship-portal/pages/student/');
        exit();
    } else {
        echo "Failed to update data.";
    }
}

// Fetch the student data
$update = get_student_data($con);
?>
