<?php

require("./common/databaseconnection.php");

// Get user_id from request
$user_id = $_REQUEST["user_id"];

// Check if user exists
$check_query = "SELECT user_id FROM users WHERE user_id = '$user_id'";
$check_result = mysqli_query($connection, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Delete user from database
    $delete_query = "DELETE FROM users WHERE user_id = '$user_id'";
    if (mysqli_query($connection, $delete_query)) {
        echo json_encode(["status" => "success", "message" => "User removed successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to remove user"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User does not exist"]);
}

?>
