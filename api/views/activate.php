<?php
include 'db_connection.php';
$conn = OpenCon();
$response = array('success' => false, 'message' => '');
error_log('response: ' . print_r($response, true));


// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $postData = json_decode(file_get_contents('php://input'), true);
    $userId = $postData['userId'] ?? null;
    $itemName = $postData['itemName'] ?? null;

    if (!$userId || !$itemName) {
        $response['message'] = 'User ID or Item Name not provided';
        // Return a status code 400
        http_response_code(400);
        echo json_encode($response);
        exit;
    }

    $pointsSql = "SELECT points FROM users WHERE userId = ?";
    if ($pointsStmt = $conn->prepare($pointsSql)) {
        // Bind the userId to the statement
        $pointsStmt->bind_param("s", $userId);
        // Execute the statement
        $pointsStmt->execute();
        // Get the result
        $pointsResult = $pointsStmt->get_result();
        // Fetch the data
        $pointsData = $pointsResult->fetch_assoc();
        // Close the statement
        $pointsStmt->close();

        // Check if the user has enough points
        if ($pointsData['points'] < 100) {
            $response['body'] = 'Not enough points';
            echo json_encode($response);
            exit;
        }

        // Deduct points from the user's account
        $newPoints = $pointsData['points'] - 100;
        $deductSql = "UPDATE users SET points = ? WHERE userId = ?";
        if ($deductStmt = $conn->prepare($deductSql)) {
            // Bind the newPoints and userId to the statement
            $deductStmt->bind_param("is", $newPoints, $userId);
            // Execute the statement
            $deductStmt->execute();
            // Close the statement
            $deductStmt->close();
        } else {
            // Handle the case where the statement could not be prepared
            $response['message'] = "Error: " . $conn->error;
            echo json_encode($response);
            exit;
        }
    }

    // Prepare a SQL query to get the user's inventory item
    $sql = "SELECT itemCount FROM inventory WHERE userId = ? AND itemName = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind the userId and itemName to the statement
        $stmt->bind_param("ss", $userId, $itemName);
        // Execute the statement
        $stmt->execute();
        // Get the result
        $result = $stmt->get_result();
        // Fetch the data
        $itemData = $result->fetch_assoc();
        // Close the statement
        $stmt->close();

        // Check if the item exists in the user's inventory
        if ($itemData) {
            // The item exists, so increase the itemCount by 1
            $newItemCount = $itemData['itemCount'] + 1;
            // Prepare a SQL query to update the itemCount
            $updateSql = "UPDATE inventory SET itemCount = ? WHERE userId = ? AND itemName = ?";
            // Prepare the statement
            if ($updateStmt = $conn->prepare($updateSql)) {
                // Bind the newItemCount, userId, and itemName to the statement
                $updateStmt->bind_param("iss", $newItemCount, $userId, $itemName);
                // Execute the statement
                $updateStmt->execute();
                // Close the statement
                $updateStmt->close();
                // Set the success response
                $response['success'] = true;
                $response['message'] = 'Item purchased successfully';
                $response['body'] = ["newItemCount" => $newItemCount, "newPoints" => $newPoints];
            } else {
                // Handle the case where the statement could not be prepared
                $response['message'] = "Error: " . $conn->error;
            }
        } else {
            // add the item to the user's inventory
            $insertSql = "INSERT INTO inventory (userId, itemName, itemCount) VALUES (?, ?, 1)";
            if ($insertStmt = $conn->prepare($insertSql)) {
                // Bind the userId and itemName to the statement
                $insertStmt->bind_param("ss", $userId, $itemName);
                // Execute the statement
                $insertStmt->execute();
                // Close the statement
                $insertStmt->close();
                // Set the success response
                $response['success'] = true;
                $response['message'] = 'Item purchased successfully';
                $response['body'] = ["newItemCount" => $newItemCount, "newPoints" => $newPoints];
            } else {
                // Handle the case where the statement could not be prepared
                $response['message'] = "Error: " . $conn->error;
            }
            $response['success'] = true;
            $response['message'] = 'Item purchased successfully';
            $response['body'] = ["newItemCount" => $newItemCount, "newPoints" => $newPoints];
        }
    } else {
        // Handle the case where the statement could not be prepared
        $response['message'] = "Error: " . $conn->error;
    }
} else {
    $response['message'] = 'Invalid request method';
}
error_log('response: ' . print_r($response, true));
// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Don't forget to close the database connection
$conn->close();
?>