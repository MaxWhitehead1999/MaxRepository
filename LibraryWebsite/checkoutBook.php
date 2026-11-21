
<?php

session_start();
require "database.php";

header("Content-Type: application/json");

// Get the logged-in user's ID
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Please log in first."
    ]);
    exit;
}

$userID = $_SESSION["user_id"];

// Get the selected book ID from JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$bookID = $data["book_id"] ?? null;

if (!filter_var($bookID, FILTER_VALIDATE_INT) || $bookID < 1) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Invalid book ID."
    ]);
    exit;
}

try {
    $conn->begin_transaction();

    // Make sure the book exists
    $stmt = $conn->prepare("SELECT id FROM books WHERE id = ?");
    $stmt->bind_param("i", $bookID);
    $stmt->execute();

    if ($stmt->get_result()->num_rows === 0) {
        throw new Exception("Book not found.");
    }
    $stmt->close();

    // Reserve the selected book for this user.
    // The database's unique_active_book constraint prevents
    // two users from reserving the same book simultaneously.
    $stmt = $conn->prepare(
        "INSERT INTO checkouts (user_id, book_id, status)
         VALUES (?, ?, 'reserved')"
    );

    $stmt->bind_param("ii", $userID, $bookID);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Book successfully reserved!"
    ]);

} catch (mysqli_sql_exception $error) {
    $conn->rollback();

    http_response_code(409);
    echo json_encode([
        "success" => false,
        "message" => $error->getCode() == 1062
            ? "This book is already reserved or checked out."
            : "Unable to reserve this book."
    ]);

} catch (Exception $error) {
    $conn->rollback();

    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => $error->getMessage()
    ]);
}

?>
