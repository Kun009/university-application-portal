<?php
// Database connection
include("db_connect.php"); // Update with your database connection file

// Fetch the search term
$query = isset($_GET['query']) ? $_GET['query'] : '';

$suggestions = [];
if ($query) {
    $universities = []; // Fetch all university names from the database
    $stmt = $pdo->query("SELECT universityName FROM universities");
    while ($row = $stmt->fetch()) {
        $universities[] = $row['universityName'];
    }

    // Find similar university names using Levenshtein distance
    foreach ($universities as $university) {
        if (levenshtein(strtolower($query), strtolower($university)) <= 2) { // Allows 2 character difference
            $suggestions[] = $university;
        }
    }
}

// Return suggestions as JSON
header('Content-Type: application/json');
echo json_encode($suggestions);
?>
