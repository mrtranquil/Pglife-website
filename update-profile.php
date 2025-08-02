<?php
require("./common/databaseconnection.php");

if (isset($_REQUEST["user_id"]) && isset($_FILES["profile_photo"])) {
    $user_id = mysqli_real_escape_string($connection, $_REQUEST["user_id"]);

    // Fetch user data
    $query = "SELECT * FROM `users` WHERE `user_id`='$user_id'";
    $result = mysqli_query($connection, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // Delete old profile picture if exists
        if (!empty($row["profile_url"])) {
            $file_path = __DIR__ . "/" . $row["profile_url"];
            if (file_exists($file_path)) {
                unlink($file_path); // Delete old file
            }
        }

        // Upload new profile picture
        $directory = "uploads/images/profile/";
        $filename = basename($_FILES["profile_photo"]["name"]);
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
        $target_file = $directory . $new_filename;

        $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            echo json_encode(["status" => "error", "message" => "Invalid file type. Allowed: JPG, PNG, GIF"]);
            exit;
        }

        // Ensure directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // Move uploaded file and update database
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $profile_url = $target_file;
            $update_query = "UPDATE `users` SET `profile_url` = '$profile_url' WHERE `user_id` = '$user_id'";
            
            if (mysqli_query($connection, $update_query)) {
                echo json_encode(["status" => "success", "message" => "Profile updated successfully", "profile_url" => $profile_url]);
            } else {
                echo json_encode(["status" => "error", "message" => "Database update failed"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to upload new profile picture"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
