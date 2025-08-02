<?php

require("./common/databaseconnection.php");

$user_id = $_REQUEST["user_id"];

// Check if user exists in user_type table
$check_query = "SELECT * FROM `user_type` WHERE `user_id` = '$user_id'";
$check_result = mysqli_query($connection, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo json_encode(["message" => "User type not found", "status" => "error"]);
    exit();
}

$user_data = mysqli_fetch_assoc($check_result);

if ($user_data["provider"] == 1) {
    $usertype = "provider";
} elseif ($user_data["helper"] == 1) {
    $usertype = "helper";
} else {
    $usertype = "unknown";
}

echo json_encode(["message" => "User type retrieved successfully", "status" => "success", "usertype" => $usertype]);

?>