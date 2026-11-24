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

//Updates Users checkouts to reflect any late fees that have accrued since the last time they checked out a book
updateLateFees($conn, $userID);

$sql = "SELECT books.id, books.title, checkouts.status
        FROM checkouts
        JOIN books ON checkouts.book_id = books.id
        WHERE checkouts.user_id = ?
        AND checkouts.status IN ('reserved', 'checked_out')
        AND checkouts.return_date IS NULL
        AND (checkouts.due_date IS NULL
             OR checkouts.due_date >= NOW())
        ORDER BY checkouts.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();

$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);

?>