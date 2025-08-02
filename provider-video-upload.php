<?php

require("./common/databaseconnection.php");

if (isset($_REQUEST["user_id"])) {
    $user_id = mysqli_real_escape_string($connection, $_REQUEST["user_id"]);

    // Check if user exists
    $check_query = "SELECT * FROM `users` WHERE `user_id` = '$user_id'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // Define upload directory
    $directory = "uploads/videos/providervideos/" . $user_id . "/";

    // Ensure user directory exists
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    // Get video details
    $filename = basename($_FILES["provider_video"]["name"]);
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $new_filename = "video_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $directory . $new_filename;

    // Allowed video file types
    $allowed_extensions = ["mp4", "avi", "mkv", "mov", "flv"];

    if (!in_array($file_extension, $allowed_extensions)) {
        echo json_encode(["status" => "error", "message" => "Invalid file type. Allowed: MP4, AVI, MKV, MOV, FLV"]);
        exit;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["provider_video"]["tmp_name"], $target_file)) {
        $video_url = $target_file;

        // Insert video details into database
        $query = "INSERT INTO `provider_videos` (`user_id`, `video_url`) VALUES ('$user_id', '$video_url')";
        $result = mysqli_query($connection, $query);

        if ($result) {
            echo json_encode(["status" => "success", "message" => "Video uploaded successfully", "video_url" => $video_url]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database insertion failed"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload video"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request, missing parameters"]);
}
?>
