<?php
// Include your database connection
include 'db_connect.php'; // adjust the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape input to prevent SQL injection
    $email = $conn->real_escape_string($_POST['email']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $day = (int)$_POST['day'];
    $month = (int)$_POST['month'];
    $year = (int)$_POST['year'];

    $sql = "INSERT INTO users (email, gender, day, month, year) 
            VALUES ('$email', '$gender', $day, $month, $year)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
