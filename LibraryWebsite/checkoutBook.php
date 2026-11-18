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

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(405, false, "Invalid request method.");
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    sendResponse(401, false, "Please log in first.");
}

// Check request origin
$origin = $_SERVER["HTTP_ORIGIN"] ?? "";

if ($origin !== "") {
    $requestOrigin = parse_url($origin);
    $host = $_SERVER["HTTP_HOST"] ?? "";

    if (($requestOrigin["host"] ?? "") !== explode(":", $host)[0]) {
        sendResponse(403, false, "Request not allowed.");
    }
}

// Get the selected book ID
$data = json_decode(file_get_contents("php://input"), true);

$bookID = filter_var(
    $data["book_id"] ?? null,
    FILTER_VALIDATE_INT
);

if (!$bookID || $bookID < 1) {
    sendResponse(400, false, "Invalid book ID.");
}

$userID = (int) $_SESSION["user_id"];

// Start a database transaction
$conn->begin_transaction();

try {

    // Lock the book row while checking availability
    $stmt = $conn->prepare(
        "SELECT id FROM books WHERE id = ? FOR UPDATE"
    );

    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        $conn->rollback();
        sendResponse(404, false, "This book does not exist.");
    }

    // Check if user has overdue books
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM checkouts
         WHERE user_id = ?
         AND status = 'checked_out'
         AND return_date IS NULL
         AND due_date < NOW()"
    );

    $stmt->bind_param("i", $userID);
    $stmt->execute();

    $overdue = $stmt->get_result()->fetch_assoc();

    if ($overdue["total"] > 0) {
        $conn->rollback();

        sendResponse(
            403,
            false,
            "You cannot check out a book because you have overdue books."
        );
    }

    // Check unpaid late fees
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM checkouts
         WHERE user_id = ?
         AND late_fee > late_fee_paid"
    );

    $stmt->bind_param("i", $userID);
    $stmt->execute();

    $fees = $stmt->get_result()->fetch_assoc();

    if ($fees["total"] > 0) {
        $conn->rollback();

        sendResponse(
            403,
            false,
            "You have unpaid late fees. Please pay them before checking out another book."
        );
    }

    // Check if the book is already reserved or checked out
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM checkouts
         WHERE book_id = ?
         AND status IN ('reserved', 'checked_out')"
    );

    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    $availability = $stmt->get_result()->fetch_assoc();

    if ($availability["total"] > 0) {
        $conn->rollback();

        sendResponse(
            409,
            false,
            "This book is currently unavailable for checkout."
        );
    }

    // Reserve the book
    $stmt = $conn->prepare(
        "INSERT INTO checkouts
         (user_id, book_id, status)
         VALUES (?, ?, 'reserved')"
    );

    $stmt->bind_param("ii", $userID, $bookID);
    $stmt->execute();

    $conn->commit();

    sendResponse(
        200,
        true,
        "Book successfully reserved for pickup!"
    );

} catch (Throwable $error) {

    $conn->rollback();
    error_log($error->getMessage());

    sendResponse(
        500,
        false,
        "Checkout failed. Please try again."
    );
}

?>