<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "database.php";

$url = "https://openlibrary.org/search.json?q=subject%3Afiction&limit=20";


$response = file_get_contents($url);

if ($response === false) {
    die("Error: Unable to retrieve books from Open Library.");
}

$data = json_decode($response, true);

if (!isset($data["docs"])) {
    die("Error: Invalid API response.");
}

//IGNORE allows MySQL to skip inserting a book if it already exists in the database, preventing duplicate entries.
$sql = "INSERT IGNORE INTO books
    (isbn, title, subtitle, author, edition, publisher,
    publication_date, format, dewey_decimal_number,
    genre, audience, page_number)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}


$importedBooks = 0;

// Inserts multiple books into the database using a prepared statement to prevent SQL injection
foreach ($data["docs"] as $book) {

    $isbn = $book["isbn"][0] ?? null;
    $title = $book["title"] ?? "Unknown";
    $subtitle = $book["subtitle"] ?? null;
    $author = $book["author_name"][0] ?? null;
    $edition = null;
    $publisher = $book["publisher"][0] ?? null;
    $publicationDate = isset($book["first_publish_year"])
        ? (string)$book["first_publish_year"]
        : null;
    $format = null;
    $dewey = $book["ddc"][0] ?? null;
    $genre = $book["subject"][0] ?? null;
    $audience = null;
    $pageNumber = null;

    $stmt->bind_param(
        "sssssssssssi",
        $isbn,
        $title,
        $subtitle,
        $author,
        $edition,
        $publisher,
        $publicationDate,
        $format,
        $dewey,
        $genre,
        $audience,
        $pageNumber
    );

   
    if ($stmt->execute()) {
        $importedBooks += $stmt->affected_rows;
    } else {
        echo "Error importing book: " . $stmt->error . "<br>";
    }
}


echo "Books imported successfully!<br>";
echo "Number of new books imported: " . $importedBooks;


$stmt->close();


$conn->close();

?>