<?php
session_start();
include 'db_connection.php';
$conn = OpenCon();

// Check if the userId cookie is set
if (isset($_COOKIE['userId'])) {
    $userId = $_COOKIE['userId'];

    // Prepare a SQL query to get the user's stats
    $sql = "SELECT * FROM users WHERE userId = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the userId to the statement
        $stmt->bind_param("s", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the data
        $userData = $result->fetch_assoc();

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the statement could not be prepared
        echo "Error: " . $conn->error;
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user data is not null
    if ($userData !== null) {
        // Increase the user's points by 1
        $newPoints = $userData['points'] + 1;

        // Prepare a SQL query to update the user's points
        $updateSql = "UPDATE users SET points = ? WHERE userId = ?";

        // Prepare the statement
        if ($updateStmt = $conn->prepare($updateSql)) {
            // Bind the new points and userId to the statement
            $updateStmt->bind_param("is", $newPoints, $userId);

            // Execute the statement
            $updateStmt->execute();

            // Close the statement
            $updateStmt->close();
        } else {
            // Handle the case where the statement could not be prepared
            echo "Error: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
