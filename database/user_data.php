<?php
session_start();
include 'includes/header.php';
include 'database/db_connect.php';

if(isset($_POST['save_profile'])){
    // Get all input values and escape
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // secure hashing

    $height = $conn->real_escape_string($_POST['height']);
    $weight = $conn->real_escape_string($_POST['weight']);
    $figure = $conn->real_escape_string($_POST['figure']);
    $appearance = $conn->real_escape_string($_POST['appearance']);
    $complexion = $conn->real_escape_string($_POST['complexion']);
    $status = $conn->real_escape_string($_POST['status']);
    $education = $conn->real_escape_string($_POST['education']);
    $career = $conn->real_escape_string($_POST['career']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $ethnicity = $conn->real_escape_string($_POST['ethnicity']);
    $caste = $conn->real_escape_string($_POST['caste']);
    $social_class = $conn->real_escape_string($_POST['social_class']);
    $residency = $conn->real_escape_string($_POST['residency']);
    $family = $conn->real_escape_string($_POST['family']);
    $smoking = $conn->real_escape_string($_POST['smoking']);
    $drinking = $conn->real_escape_string($_POST['drinking']);
    $children = $conn->real_escape_string($_POST['children']);
    $personality = $conn->real_escape_string($_POST['personality']);
    $first_date = $conn->real_escape_string($_POST['first_date_preference']);
    $living = $conn->real_escape_string($_POST['living_arrangements']);

    // Insert into database
    $sql = "INSERT INTO user_data
        (username, email, password, height, weight, figure, appearance, complexion, status,
        education, career, religion, ethnicity, caste, social_class, residency, family,
        smoking, drinking, children, personality, first_date_preference, living_arrangements)
        VALUES
        ('$username', '$email', '$password', '$height', '$weight', '$figure', '$appearance', '$complexion', '$status',
        '$education', '$career', '$religion', '$ethnicity', '$caste', '$social_class', '$residency', '$family',
        '$smoking', '$drinking', '$children', '$personality', '$first_date', '$living')";

    if($conn->query($sql)){
        $_SESSION['user'] = $email; // log the user in
        echo "<script>alert('Profile saved successfully!'); window.location='profilepage.php';</script>";
    } else {
        echo "<script>alert('Error saving profile: ".$conn->error."');</script>";
    }
}
?>
