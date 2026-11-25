<?php

session_start();
require "database.php";
require_once "updateLateFees.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$userID = $_SESSION["user_id"];

// Update overdue fees
updateLateFees($conn, $userID);

// Retrieve overdue books
$sql = "SELECT books.id, books.title,
               checkouts.due_date,
               checkouts.late_fee,
               TIMESTAMPDIFF(DAY, checkouts.due_date, NOW()) AS days_overdue
        FROM checkouts
        JOIN books ON checkouts.book_id = books.id
        WHERE checkouts.user_id = ?
        AND checkouts.status = 'checked_out'
        AND checkouts.return_date IS NULL

        AND checkouts.due_date < NOW()";

        $daysOverdue = filter_input(
            INPUT_GET,
            "days_overdue",
            FILTER_VALIDATE_INT
        );

        if ($daysOverdue !== null && $daysOverdue !== false) {
            $sql .= " AND TIMESTAMPDIFF(DAY, checkouts.due_date, NOW()) = ?";
        }

        $sql .= " ORDER BY checkouts.due_date ASC";

$stmt = $conn->prepare($sql);

if ($daysOverdue !== null && $daysOverdue !== false) {
    $stmt->bind_param("ii", $userID, $daysOverdue);
} else {
    $stmt->bind_param("i", $userID);
}

$stmt->execute();

$result = $stmt->get_result();

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);

