<?php
// Database Setup Script - Run this once to set up sample users
include 'database/db_connect.php';

echo "<h2>Database Setup</h2>";
echo "<p>Setting up sample users and online status tracking...</p>";

// Step 1: Add columns
echo "<h3>Step 1: Adding columns to users table</h3>";
$sql1 = "ALTER TABLE users ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL";
if ($conn->query($sql1)) {
    echo "✓ Added last_seen column<br>";
} else {
    echo "⚠ last_seen column: " . $conn->error . "<br>";
}

$sql2 = "ALTER TABLE users ADD COLUMN city VARCHAR(100) DEFAULT NULL";
if ($conn->query($sql2)) {
    echo "✓ Added city column<br>";
} else {
    echo "⚠ city column: " . $conn->error . "<br>";
}

$sql3 = "ALTER TABLE users ADD COLUMN country VARCHAR(100) DEFAULT NULL";
if ($conn->query($sql3)) {
    echo "✓ Added country column<br>";
} else {
    echo "⚠ country column: " . $conn->error . "<br>";
}

// Step 2: Delete old sample users
echo "<h3>Step 2: Cleaning up old sample users</h3>";
$sql4 = "DELETE FROM users WHERE email IN ('global@example.com', 'bluechip@example.com', 'dariush@example.com', 'buddika@example.com', 'channa@example.com', 'soulmate@example.com')";
if ($conn->query($sql4)) {
    echo "✓ Deleted old sample users<br>";
} else {
    echo "✗ Error: " . $conn->error . "<br>";
}

// Step 3: Insert sample users
echo "<h3>Step 3: Creating sample users</h3>";

$users = [
    ['Globalexplorer61', 'global@example.com', 'male', 15, 6, 1992, 'NOW()', 'Bandarangama', 'Sri Lanka'],
    ['Bluechip', 'bluechip@example.com', 'male', 20, 3, 1987, 'DATE_SUB(NOW(), INTERVAL 2 HOUR)', 'Colombo', 'Sri Lanka'],
    ['Dariush007', 'dariush@example.com', 'male', 10, 8, 1988, 'DATE_SUB(NOW(), INTERVAL 1 HOUR)', 'Colombo', 'Sri Lanka'],
    ['Buddika93', 'buddika@example.com', 'female', 25, 5, 1994, 'DATE_SUB(NOW(), INTERVAL 1 HOUR)', 'Newport', 'United Kingdom'],
    ['Channa23', 'channa@example.com', 'male', 12, 2, 1978, 'DATE_SUB(NOW(), INTERVAL 11 HOUR)', 'Minneapolis', 'United States'],
    ['Soulmateg', 'soulmate@example.com', 'male', 8, 9, 1995, 'DATE_SUB(NOW(), INTERVAL 11 HOUR)', 'Winnipeg', 'Canada']
];

foreach ($users as $user) {
    $sql = "INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) 
            VALUES ('{$user[0]}', '{$user[1]}', '\$2y\$10\$abcdefghijklmnopqrstuvwxyz123456789', '{$user[2]}', {$user[3]}, {$user[4]}, {$user[5]}, 1, {$user[6]}, '{$user[7]}', '{$user[8]}')";
    if ($conn->query($sql)) {
        echo "✓ Created user: {$user[0]}<br>";
    } else {
        echo "✗ Error creating {$user[0]}: " . $conn->error . "<br>";
    }
}

// Step 4: Insert user data
echo "<h3>Step 4: Adding user profile data</h3>";

$profiles = [
    ['global@example.com', '5\'3" - 165 cm', 'Separated/Divorced', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Sinhala but not Buddhist/Sinhalese'],
    ['bluechip@example.com', '5\'9" - 175 cm', 'Never Married', 'Bachelors degree', 'Banking/Finance', 'Buddhist', 'Buddhist/Sinhalese (Southern)'],
    ['dariush@example.com', '5\'8" - 173 cm', 'Separated/Divorced', 'Professional qualification', 'Transport/Manufacturing', 'Catholic', 'Catholic/Sinhalese (Western)'],
    ['buddika@example.com', '5\'6" - 173 cm', 'Never Married', 'Bachelors degree', 'Doctor/Medical Officer', 'Buddhist', 'Buddhist/Sinhalese'],
    ['channa@example.com', '5\'10" - 178 cm', 'Separated/Divorced', 'Professional qualification', 'Other/Self Employed', 'Buddhist', 'Buddhist/Sinhalese'],
    ['soulmate@example.com', '5\'11" - 180 cm', 'Never Married', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Buddhist/Sinhalese (Southern)']
];

foreach ($profiles as $profile) {
    $sql = "INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
            SELECT id, '{$profile[1]}', '{$profile[2]}', '{$profile[3]}', '{$profile[4]}', '{$profile[5]}', '{$profile[6]}' 
            FROM users WHERE email = '{$profile[0]}'";
    if ($conn->query($sql)) {
        echo "✓ Added profile data for: {$profile[0]}<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
}

// Step 5: Add profile photos
echo "<h3>Step 5: Adding profile photos</h3>";

$photos = [
    ['dariush@example.com', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=240&fit=crop'],
    ['buddika@example.com', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=240&fit=crop']
];

foreach ($photos as $photo) {
    $sql = "INSERT INTO user_photos (user_id, photo_path) 
            SELECT id, '{$photo[1]}' FROM users WHERE email = '{$photo[0]}'";
    if ($conn->query($sql)) {
        echo "✓ Added photo for: {$photo[0]}<br>";
    } else {
        echo "✗ Error: " . $conn->error . "<br>";
    }
}

echo "<h3>✅ Setup Complete!</h3>";
echo "<p><a href='search.php'>Go to Search Page</a> to see the users!</p>";
echo "<p><strong>Note:</strong> You can delete this file (setup_database.php) after running it.</p>";

$conn->close();
?>
