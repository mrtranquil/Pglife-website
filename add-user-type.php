<?php

require("./common/databaseconnection.php");

$user_id = $_REQUEST["user_id"];
$user_type = $_REQUEST["user_type"];


if ($user_type == "helper") {
    $query = "INSERT INTO `user_type`(`user_id`, `helper`, `provider`) VALUES ('$user_id', 1, 0)";
} elseif ($user_type == "provider") {
    $query = "INSERT INTO `user_type`(`user_id`, `helper`, `provider`) VALUES ('$user_id', 0, 1)";
} 

$result = mysqli_query($connection, $query);

if ($result) {
    echo json_encode(["message" => "User type added successfully", "status" => "success"]);
} else {
    echo json_encode(["message" => "Failed to insert data", "status" => "error"]);
}

?>