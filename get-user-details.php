<?php

require("./common/databaseconnection.php");

$user_id = $_REQUEST["user_id"];

// Fetch user details
$query = "SELECT u.first_name, u.last_name, u.user_id, u.points,u.profile_url,u.profile_background_url, ut.helper, ut.provider 
          FROM users u 
          JOIN user_type ut ON u.user_id = ut.user_id 
          WHERE u.user_id = '$user_id'";

$result = mysqli_query($connection, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $full_name = $row['first_name'] . ' ' . $row['last_name'];
    $position = "";
    
    if ($row['helper'] == 1) {
        $position = "Helper";
    } elseif ($row['provider'] == 1) {
        $position = "Provider";
    } else {
        $position = "User"; // Default role if neither helper nor provider
    }
    
    $user_points = $row['points'];

    // Calculate rank based on points
    $rank_query = "SELECT COUNT(*) + 1 AS rank FROM users WHERE points > '$user_points'";
    $rank_result = mysqli_query($connection, $rank_query);
    $rank_row = mysqli_fetch_assoc($rank_result);
    $rank = $rank_row['rank'];
    
    echo json_encode([
        "status" => "success",
        "background_url" => $row['profile_background_url'],
        "user_id" => $row['user_id'],
        "name" => $full_name,
        "points" => $row['points'],
        "profile_url" => $row['profile_url'],
        "background_url" => $row['profile_background_url'],
        "position" => $position,
        "rank" => $rank
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

?>
