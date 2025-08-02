<?php

require("./common/databaseconnection.php");

// Fetch reels data
$query = "SELECT 
            
            `reel_url`, 
            `likes`, 
            `comments`, 
            `saved_status`, 
            `caption`, 
            `shares`, 
            `views`, 
            `user_profile_url`, 
            `user_full_name` 
          FROM `reels`";

$result = mysqli_query($connection, $query);

if ($result) {
    $reels = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $reels[] = $row;
    }

    if (!empty($reels)) {
        echo json_encode([
            "status" => "success",
            "message" => "Reels data retrieved successfully",
            "data" => $reels
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No reels found"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database query failed"
    ]);
}

?>
