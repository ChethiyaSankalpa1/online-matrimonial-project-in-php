<?php
include 'database/db_connect.php';

echo "<h2>Database Connection Test</h2>";

// Test connection
if($conn){
    echo "<p style='color:green;'>✓ Database connected successfully!</p>";
    echo "<p>Database: " . $conn->server_info . "</p>";
} else {
    echo "<p style='color:red;'>✗ Connection failed!</p>";
    die();
}

// Check if tables exist
echo "<h3>Tables Status:</h3>";

$tables = ['users', 'user_data'];
foreach($tables as $table){
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if($result && $result->num_rows > 0){
        echo "<p style='color:green;'>✓ Table '$table' exists</p>";
        
        // Count records
        $count_result = $conn->query("SELECT COUNT(*) as count FROM $table");
        $count = $count_result->fetch_assoc()['count'];
        echo "<p>&nbsp;&nbsp;&nbsp;Records: $count</p>";
    } else {
        echo "<p style='color:red;'>✗ Table '$table' does NOT exist</p>";
    }
}

// Show users table structure
echo "<h3>Users Table Structure:</h3>";
$columns = $conn->query("SHOW COLUMNS FROM users");
if($columns){
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    while($col = $columns->fetch_assoc()){
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

$conn->close();
?>
