<?php
// Include database connection
require_once '../includes/db_connect.php';

// Function to execute SQL file
function executeSQLFile($conn, $filename) {
    echo "Executing SQL file: $filename<br>";
    
    // Read the SQL file
    $sql = file_get_contents($filename);
    
    // Split SQL file into individual queries
    $queries = explode(';', $sql);
    
    // Execute each query
    $count = 0;
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query)) {
                $count++;
            } else {
                echo "Error executing query: " . $conn->error . "<br>";
                echo "Query: " . $query . "<br><br>";
            }
        }
    }
    
    echo "Successfully executed $count queries from $filename<br><br>";
}

// Execute SQL files
executeSQLFile($conn, 'add_more_plants.sql');
executeSQLFile($conn, 'add_more_livestock.sql');

echo "Database update completed!";

// Close connection
$conn->close();
?>
