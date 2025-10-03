<?php
include 'database/db_connect.php';

echo "<h2>Database Fix Script</h2>";
echo "<p>This will update your database structure...</p>";

// Drop existing tables to recreate with correct structure
$drop_profiles = "DROP TABLE IF EXISTS user_data";
$drop_users = "DROP TABLE IF EXISTS users";

echo "<p>Dropping old tables...</p>";
if($conn->query($drop_profiles)){
    echo "<p style='color:green;'>✓ Dropped user_data table</p>";
}

if($conn->query($drop_users)){
    echo "<p style='color:green;'>✓ Dropped users table</p>";
}

// Create users table with all columns
$create_users = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) DEFAULT NULL,
    gender ENUM('male', 'female') DEFAULT NULL,
    day INT DEFAULT NULL,
    month INT DEFAULT NULL,
    year INT DEFAULT NULL,
    profile_completed TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

echo "<p>Creating users table...</p>";
if($conn->query($create_users)){
    echo "<p style='color:green;'>✓ Created users table successfully!</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating users table: " . $conn->error . "</p>";
}

// Create user_data table
$create_profiles = "CREATE TABLE user_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    height VARCHAR(50),
    weight VARCHAR(50),
    figure VARCHAR(50),
    appearance VARCHAR(50),
    complexion VARCHAR(50),
    status VARCHAR(50),
    education VARCHAR(100),
    career VARCHAR(100),
    religion VARCHAR(50),
    ethnicity VARCHAR(50),
    caste VARCHAR(50),
    social_class VARCHAR(50),
    residency VARCHAR(100),
    family VARCHAR(50),
    smoking VARCHAR(50),
    drinking VARCHAR(50),
    children VARCHAR(50),
    personality VARCHAR(50),
    first_date_preference VARCHAR(100),
    living_arrangements VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

echo "<p>Creating user_data table...</p>";
if($conn->query($create_profiles)){
    echo "<p style='color:green;'>✓ Created user_data table successfully!</p>";
} else {
    echo "<p style='color:red;'>✗ Error creating user_data table: " . $conn->error . "</p>";
}

echo "<h3>Database is now ready!</h3>";
echo "<p><a href='signin.php'>Go to Sign Up Page</a></p>";
echo "<p><a href='test_db.php'>Test Database</a></p>";

$conn->close();
?>
