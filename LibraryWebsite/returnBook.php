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

// Temporary administrator protection
if (!isset($_SESSION["user_id"]) ||
    (int) $_SESSION["user_id"] !== 1) {

    sendResponse(403, false, "Access denied.");
}

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(405, false, "Invalid request method.");
}

// Only accept JSON requests
$contentType = explode(";", $_SERVER["CONTENT_TYPE"] ?? "")[0];

if (trim($contentType) !== "application/json") {
    sendResponse(415, false, "Invalid content type.");
}

// Retrieve the checkout record ID
$data = json_decode(file_get_contents("php://input"), true);

$checkoutID = filter_var(
    $data["checkout_id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$checkoutID || $checkoutID < 1) {
    sendResponse(400, false, "Invalid checkout ID.");
}

try {

    $stmt = $conn->prepare(
        "UPDATE checkouts
         SET late_fee = CASE
             WHEN status = 'checked_out'
             AND due_date < NOW()
             THEN LEAST(25, TIMESTAMPDIFF(DAY, due_date, NOW()))
             ELSE late_fee
         END,
         return_date = NOW(),
         status = 'returned'
         WHERE id = ?
         AND status IN ('reserved', 'checked_out')
         AND return_date IS NULL"
    );

    $stmt->bind_param("i", $checkoutID);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        sendResponse(404, false, "Active checkout record not found.");
    }

    sendResponse(200, true, "Book successfully removed.");

} catch (Throwable $error) {

    error_log($error->getMessage());

    sendResponse(500, false, "Unable to remove book.");
}
