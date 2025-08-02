<?php

require("./common/databaseconnection.php");

if (isset($_REQUEST["user_id"]) && isset($_FILES["reelfile"]) && isset($_REQUEST["reelcaption"])) {
    $user_id = mysqli_real_escape_string($connection, $_REQUEST["user_id"]);
    $reel_caption = mysqli_real_escape_string($connection, $_REQUEST["reelcaption"]);
    $user_profile = mysqli_real_escape_string($connection, $_REQUEST["user_profile"]);
    $user_name = mysqli_real_escape_string($connection, $_REQUEST["user_name"]);

   

    // Define upload directory
    $directory = "uploads/reels/";

    // Ensure directory exists
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Get file details
    $filename = basename($_FILES["reelfile"]["name"]);
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $new_filename = "reel_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $directory . $new_filename;

    // Allowed video file types
    $allowed_extensions = ["mp4", "avi", "mkv", "mov", "flv"];

    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type. Allowed: MP4, AVI, MKV, MOV, FLV"]);
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["reelfile"]["tmp_name"], $target_file)) {
        $reel_url = $target_file;

        // Insert reel details into the database
        $query = "INSERT INTO `reels` (`user_id`, `reel_url`, `caption`,`user_full_name`,`user_profile_url`) 
                  VALUES ('$user_id', '$reel_url', '$reel_caption','$user_name','$user_profile')";

        if (mysqli_query($connection, $query)) {
            echo json_encode([
                "status" => "success",
                "message" => "Reel uploaded successfully",
                "reel_url" => $reel_url
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insertion failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload reel"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request, missing parameters"]);
}
?>
