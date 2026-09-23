<?php

require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $address = $_POST["address"];
    $account_type = $_POST["account_type"];
    $phone = $_POST["phone"];

    // Hash the password before storing it in the database
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to insert the new user into the database
    $sql = "INSERT INTO accounts (firstname, lastname, email, password, address, account_type, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);

    // Bind the parameters to the prepared statement
    $stmt->bind_param("sssssss", $firstname, $lastname, $email, $password, $address, $account_type, $phone);

    if ($stmt->execute()) {
        echo "Signup successful! You can now log in.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    // Close the database connection
    $conn->close();

}
?>