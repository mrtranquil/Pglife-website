<?php
//add user 

require("./common/databaseconnection.php");

$first_name = $_REQUEST["first_name"];
$last_name = $_REQUEST["last_name"];
$email = $_REQUEST["email"];
$phone = $_REQUEST["phone"];
$password = $_REQUEST["password"];
$gender = $_REQUEST["gender"];
$date_of_birth = $_REQUEST["date_of_birth"];

$check_query = "SELECT * FROM `users` WHERE `email` = '$email' ";
$check_result = mysqli_query($connection, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode(["message" => "User exists", "status" => "error"]);
} else {
    $query = "INSERT INTO `users`(`first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `password`, `gender`) 
              VALUES ('$first_name','$last_name','$email','$phone','$date_of_birth','$password','$gender')";
    
    $result = mysqli_query($connection, $query);
    $useridquery = "SELECT `user_id` FROM `users` WHERE `email`='$email'";
    $userresult = mysqli_query($connection, $useridquery);
    $userid = mysqli_fetch_array($userresult);
    
    if ($result) {
        echo json_encode(["message" => "User added", "status" => "success","user_id"=>"$userid[0]"]);
    } else {
        echo json_encode(["message" => "no data from api", "status" => "error"]);
    }
}

?>