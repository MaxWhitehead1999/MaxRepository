<?php

session_start();

header("Content-Type: application/json");

if(!isset($_SESSION["user_id"])) {
    http_response_code(401); 
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

echo json_encode([
    "user_id" => $_SESSION["user_id"],
    "first_name" => $_SESSION["first_name"],
    "last_name" => $_SESSION["last_name"],
    "email" => $_SESSION["email"],
    "address" => $_SESSION["address"],
    "phone" => $_SESSION["phone"]
]);


?>