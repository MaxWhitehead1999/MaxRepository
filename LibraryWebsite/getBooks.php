<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "database.php";

$id = $_GET["id"] ?? "";

if ($id !== "") {
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);
}


if(!$result) {
    die("Query failed: " . $conn->error);
}

$books = [];
while($row = $result->fetch_assoc()) {
    $books[] = $row;
}

header('Content-Type: application/json');
echo json_encode($books);

$conn->close();

?>