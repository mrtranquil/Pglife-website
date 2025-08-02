<?php
require("./common/databaseconnection.php");

$email = $_REQUEST["email"];
$password = $_REQUEST["password"];

// Check if user exists
$query = "SELECT user_id FROM users WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) { // Check if at least one row is returned
    $row = mysqli_fetch_assoc($result);
    echo json_encode([
        "status" => "success",
        "user_id" => $row['user_id']
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User does not exist"]);
}

?>

