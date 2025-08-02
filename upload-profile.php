<?php

require("./common/databaseconnection.php");

if (isset($_FILES["profile_photo"])) {
    $user_id = $_REQUEST["user_id"]; // Get user_id from the request

    // Define the upload directory
    $uploadDir = "uploads/images/profile/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
    }

    // Get file details
    $fileName = basename($_FILES["profile_photo"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = "profile_" . $user_id . "_" . time() . "." . $fileExt; // Unique file name
    $targetFile = $uploadDir . $newFileName;

    // Allowed file types
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type. Allowed: JPG, PNG, GIF"]);
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFile)) {
        // Update database with new profile URL
        $profileUrl = $targetFile;
        $query = "UPDATE users SET `profile_url` = '$profileUrl' WHERE `user_id` = '$user_id'";
        $result = mysqli_query($connection, $query);
if($result){
    echo json_encode(["status" => "success", "message" => "Profile updated", "profile_url" => $profileUrl]);

}
       
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload image"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

?>