<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "database.php";

//echo "NEW IMPORTER IS RUNNING<br>";

// new books imported from open library includes genres as well as the book the Hunger games
$searches = [
    ["subject" => "fiction", "limit" => 10],
    ["subject" => "horror", "limit" => 10],
    ["subject" => "fantasy", "limit" => 10],
    ["subject" => "mystery", "limit" => 10],
    ["subject" => "science_fiction", "limit" => 10],
    ["title" => "The Hunger Games", "limit" => 1]
];

// Example of a search query for "The Hunger Games" by Suzanne Collins
$url = "https://openlibrary.org/search.json?title="
    . urlencode("The Hunger Games")
    . "&author=" . urlencode("Suzanne Collins")
    . "&limit=5";


// Example of a search query for "The Hunger Games" by Suzanne Collins
foreach ($searches as $search){
    $limit = $search["limit"];

    // Check if the search is by title or subject and construct the appropriate URL
    if(isset($search["title"])) {

        $url = "https://openlibrary.org/search.json?title="
            . urlencode($search["title"])
            . "&limit=" . $limit;

        $genre = "Dystopian Fiction";

    } else {
        $subject = $search["subject"];

        $url = "https://openlibrary.org/search.json?subject="
            . urlencode($subject)
            . "&limit=" . $limit;

        $genre = ucwords(str_replace("_", " ", $subject));
    }

    // Fetches the response from the Open Library API
    $responce = file_get_contents($url);

    if($responce === false) {
        continue;
    }

    // Decodes the JSON response into an associative array
    $data = json_decode($responce, true);

    // Checks if the "docs" key exists in the response data and contains an array of books
    echo "Books returned from API: " . count($data["docs"] ?? []) . "<br>";
   
    // Inserts multiple books into the database using a prepared statement to prevent SQL injection
    foreach ($data["docs"] ?? [] as $book) {

        $isbn = $book["isbn"][0] ?? null;
        $title = $book["title"] ?? null;
        $subtitle = $book["subtitle"] ?? null;
        $author = $book["author_name"][0] ?? null;
        $edition = null;
        $publisher = $book["publisher"][0] ?? null;
        $publicationDate = $book["first_publish_year"] ?? null;
        $format = null;
        $deweyDecimal = $book["ddc"][0] ?? null;
        $audience = null;
        $pageNumber = $book["number_of_pages_median"] ?? null;

        // adding in empty($isbn) was causing the problem before and coming back with an error because not every ISBN is displayed
        if (empty($title)){
            continue;
        }

        //IGNORE allows MySQL to skip inserting a book if it already exists in the database, preventing duplicate entries.
        $sql = "INSERT IGNORE INTO books
        (isbn, title, subtitle, author, edition, publisher,
        publication_date, format, dewey_decimal_number,
        genre, audience, page_number)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssssssssi",
            $isbn, $title, $subtitle, $author, $edition,
            $publisher, $publicationDate, 
            $format, $dewey, $genre, $audience, $pageNumber
        );

        echo "Attempting to import: " . htmlspecialchars($title) . "<br>";

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                echo "Imported: " . htmlspecialchars($title) . "<br>";
            } else {
                echo "Skipped: " . htmlspecialchars($title) . "<br>";
            }

        } else {
            echo "Error: " . htmlspecialchars($stmt->error) . "<br>";
        }

    $stmt->close();

    }
}

$conn->close();
echo "Books imported successfully!<br>";

?>