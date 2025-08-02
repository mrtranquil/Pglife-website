<?php

require("./common/databaseconnection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_REQUEST["user_id"])) {
    $user_id = $_REQUEST["user_id"];

    // Fetch the current profile URL from the database
    $query = "SELECT profile_url FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($connection, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $currentProfile = $row["profile_url"];

        if (!empty($currentProfile)) {
            $filePath = __DIR__ . "/" . $currentProfile; // Construct absolute path

            // Check if the file exists and delete it
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    // File deleted successfully, now update the database
                    $updateQuery = "UPDATE users SET profile_url = NULL WHERE user_id = '$user_id'";
                    if (mysqli_query($connection, $updateQuery)) {
                        echo json_encode(["status" => "success", "message" => "Profile picture removed"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Failed to update database"]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to delete profile picture"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Profile picture not found"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No profile picture set"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

?>
