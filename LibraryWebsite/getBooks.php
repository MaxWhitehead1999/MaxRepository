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
    if (isset($_GET["id"])) {

    $bookID = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($bookID === false || $bookID < 1) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid book ID"]);
        exit();
    }

    $stmt = $conn->prepare(
        "SELECT * FROM books WHERE id = ?"
    );

    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    $result = $stmt->get_result();

    } else {

        $sql = "SELECT * FROM books";
        $result = $conn->query($sql);

    }
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