
<?php
session_start();
require "database.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

$userID = (int) $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT first_name, last_name, address, email
     FROM accounts
     WHERE id = ?"
);

$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(["error" => "Account not found"]);
    exit();
}

echo json_encode($user);
