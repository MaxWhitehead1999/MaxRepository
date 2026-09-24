<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];

    // Hash the password before storing it in the database
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to insert the new user into the database
    $sql = "INSERT INTO accounts (first_name, last_name, email, password, address,  phone) VALUES (?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameters to the prepared statement
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $password, $address, $phone);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    // Close the database connection
    $conn->close();

}
?>