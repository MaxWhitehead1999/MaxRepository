
<?php

session_start();
require "database.php";

header("Content-Type: application/json");

function sendResponse($status, $success, $message) {
    http_response_code($status);
    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(405, false, "Invalid request method.");
}

if (!isset($_SESSION["user_id"])) {
    sendResponse(401, false, "Please log in first.");
}

// Verifies that the request is coming from the same origin point as the server to prevent CSRF attacks
$origin = $_SERVER["HTTP_ORIGIN"] ?? "";
if ($origin !== "") {
    $originHost = parse_url($origin, PHP_URL_HOST);
    $requestHost = explode(":", $_SERVER["HTTP_HOST"] ?? "")[0];

    if ($originHost !== $requestHost) {
        sendResponse(403, false, "Request not allowed.");
    }
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    sendResponse(400, false, "Invalid request data.");
}

$firstName = trim($data["first_name"] ?? "");
$lastName = trim($data["last_name"] ?? "");
$address = trim($data["address"] ?? "");


if ($firstName === "" || $lastName === "" ||
    $address === "") {
    sendResponse(400, false, "All fields are required.");
}


$userID = (int) $_SESSION["user_id"];

$stmt = $conn->prepare(
    "UPDATE accounts
     SET first_name = ?,
         last_name = ?,
         address = ?,
     WHERE id = ?"
);

$stmt->bind_param(
    "ssssi",
    $firstName,
    $lastName,
    $address,
    $userID
);

try {
    $stmt->execute();
    sendResponse(200, true, "Profile updated successfully!");
} catch (mysqli_sql_exception $error) {
    if ($error->getCode() == 1062) {
        sendResponse(409, false, "Email address is already in use.");
    }

    error_log($error->getMessage());
    sendResponse(500, false, "Unable to update profile.");
}

?>
