<?php

// Read the JSON file
$jsonData = file_get_contents('config.json');

// Decode the JSON data into a PHP array
$data = json_decode($jsonData, true);

if ($data === null) {
    // JSON decoding failed, handle the error
    die("Error decoding JSON data.");
}
?>