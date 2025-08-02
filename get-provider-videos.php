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

    // Fetch provider videos for the given user
    $video_query = "SELECT `video_url` FROM `provider_videos` WHERE `user_id` = '$user_id'";
    $video_result = mysqli_query($connection, $video_query);

    $videos = [];

    while ($row = mysqli_fetch_assoc($video_result)) {
        $videos[] = $row['video_url'];
    }

    if (!empty($videos)) {
        echo json_encode([
            "status" => "success",
            "message" => "Videos retrieved successfully",
            "videos" => $videos
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "No videos found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request, missing parameters"]);
}
?>
