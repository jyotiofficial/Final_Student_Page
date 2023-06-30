<?php
require 'C:\xampp\htdocs\internship-portal-final\internship-portal\connect\connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $address = $_POST['address'] ?? '';

    if (update_existing_data($db_connection, $fullName, $email, $age, $mobile, $address)) {
        // Redirect to the desired URL after the data update
        header('Location: http://localhost/internship-portal-final/internship-portal/pages/student/');
        exit();
    } else {
        echo "Failed to update data.";
    }
}

function update_existing_data($con, $fullName, $email, $age, $mobile, $address)
{
    $query = "UPDATE student_profile SET fullName = ?, email = ?, age = ?, mobile = ?, address = ? WHERE id = 1";

    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ssiss', $fullName, $email, $age, $mobile, $address);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}
?>
