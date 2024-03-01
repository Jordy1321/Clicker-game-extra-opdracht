<?php
ini_set('display_errors', 0);
include 'db_connection.php';
$conn = OpenCon();
// Get the username and userId from the POST data
$postData = json_decode(file_get_contents('php://input'), true);
$username = $postData['username'];
$userId = $postData['userId'];

if (is_array($username)) {
    error_log('username is an array: ' . print_r($username, true));
}
if (is_array($userId)) {
    error_log('userId is an array: ' . print_r($userId, true));
}

// Generate a new user ID, if userId is not set
if (!$userId) {
    $userId = bin2hex(random_bytes(8)); // generates a random 16 character string
}

// Prepare a SQL query to create a new user

$sql = "INSERT INTO users (userId, points, username) VALUES (?, 0, ?)";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
    // Bind the userId and username to the statement
    $stmt->bind_param("ss", $userId, $username);

    // Execute the statement
    $stmt->execute();

    if ($stmt->error) {
        // Output the error message as JSON
        echo json_encode(array('error' => $stmt->error));
    } else {
        // Store the user id in a cookie
        setcookie('userId', $userId, time() + (86400 * 30), "/"); // 86400 = 1 day

        // Add an inventory item for the user
        $itemName = 'tosti';
        $itemCount = 1;
        $inventorySql = "INSERT INTO inventory (userId, itemName, itemCount) VALUES (?, ?, ?)";
        if ($inventoryStmt = $conn->prepare($inventorySql)) {
            // Bind the userId, itemName, and itemCount to the statement
            $inventoryStmt->bind_param("ssi", $userId, $itemName, $itemCount);
            // Execute the statement
            $inventoryStmt->execute();
            // Close the statement
            $inventoryStmt->close();
        }

        // Output a success message as JSON
        echo json_encode(array('success' => 'User registered successfully'));
    }

    // Close the statement
    $stmt->close();
} else {
    // Output the error message as JSON
    echo json_encode(array('error' => $conn->error));
}

// Close the database connection
$conn->close();
?>