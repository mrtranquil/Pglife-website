<?php
require("./common/databaseconnection.php");

// SQL query to fetch user details with rank and full name
$query = "
    SELECT 
        user_id,
        CONCAT(first_name, ' ', last_name) AS name,
        profile_url,
        points,
        RANK() OVER (ORDER BY points DESC) AS rank
    FROM users
";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
    exit;
}

// Fetch results
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Return JSON response
echo json_encode([
    "status" => "success",
    "users" => $users
]);
?>