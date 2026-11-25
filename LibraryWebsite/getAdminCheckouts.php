<?php

session_start();

// Restrict this endpoint to local testing for now
if (!isset($_SESSION["user_id"]) ||
    (int) $_SESSION["user_id"] !== 1) {

    http_response_code(403);
    echo json_encode(["error" => "Access denied."]);
    exit();
}

require "database.php";

header("Content-Type: application/json");

$sql = "SELECT
            checkouts.id AS checkout_id,
            accounts.id AS user_id,
            accounts.first_name,
            accounts.last_name,
            books.id AS book_id,
            books.title,
            CASE
                WHEN checkouts.status = 'checked_out'
                AND checkouts.due_date < NOW()
                THEN 'overdue'
                ELSE checkouts.status
            END AS status,
            CASE
                WHEN checkouts.status = 'checked_out'
                AND checkouts.due_date < NOW()
                THEN LEAST(25, TIMESTAMPDIFF(
                    DAY, checkouts.due_date, NOW()
                ))
                ELSE checkouts.late_fee
            END AS late_fee
        FROM checkouts
        JOIN accounts ON checkouts.user_id = accounts.id
        JOIN books ON checkouts.book_id = books.id
        WHERE checkouts.status IN ('reserved', 'checked_out')
        AND checkouts.return_date IS NULL
        ORDER BY accounts.id ASC, checkouts.id DESC";

$result = $conn->query($sql);

$checkouts = [];

while ($row = $result->fetch_assoc()) {
    $checkouts[] = $row;
}

echo json_encode($checkouts);
