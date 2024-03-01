<?php
error_reporting(1);
// Turn off error reporting
// connect your database
include 'db_connection.php';
$db = OpenCon();
$userId = $_COOKIE['userId'] ?? $_GET['userId'] ?? null ?? $_POST['userId'] ?? null;
if (!$userId) {
    $response['message'] = 'User ID not provided';
    echo json_encode($response);
    error_log('exiting');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // query the database for the user's data
    $query = "SELECT * FROM users WHERE userid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    // query the database for the user's inventory
    $sql = "SELECT itemName, itemCount FROM inventory WHERE userId = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $inventoryItems = $result->fetch_all(MYSQLI_ASSOC);

    $response = array('success' => false, 'message' => '');

    if ($userData) {
        $response['success'] = true;
        $response['data']['user'] = $userData;
    } else {
        $response['message'] = 'User not found, please register';
    }

    if ($inventoryItems) {
        $response['success'] = true;
        $response['data']['inventory'] = $inventoryItems;
    } else {
        $response['message'] = 'Inventory not found for the user';
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Turn off error reporting
    error_reporting(1);
    // connect your database
    $userId = $_COOKIE['userId'] ?? $_POST['userId'] ?? null;
    if (!$userId) {
        $response['message'] = 'User ID not provided';
        echo json_encode($response);
        exit;
    }
    // Get the new points from the POST request
    $postData = json_decode(file_get_contents('php://input'), true);
    $newPoints = $postData['newPoints'];
    if (!$newPoints) {
        $response['message'] = 'New points not provided';
        echo json_encode($response);
        error_log('newPoints: ' . $newPoints);
        exit;
    }

    // Update the user's points in the database
    $query = "UPDATE users SET points = ? WHERE userid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('is', $newPoints, $userId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Points updated successfully';
        error_log('Points updated successfully');
    } else {
        $response['message'] = 'Failed to update points';
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
$db->close();
?>