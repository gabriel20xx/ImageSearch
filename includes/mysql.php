<?php

// Access the values from the decoded data
$host = '192.168.2.60';
$username = 'gabriel';
$password = 'KingPong31:)';
$database = 'HordeImages';
$table = 'Metadata';

// Attempt to create a database connection
$conn = @mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$conn) {
    // Handle the connection error gracefully, e.g., by logging the error.
    // You can also provide a user-friendly message.
    error_log("Database connection error: " . mysqli_connect_error());
    // You can also set a flag or message to indicate the database connection issue.
    $dbConnectionError = true;
}

// The rest of your database-related code can go here.
