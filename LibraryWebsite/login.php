<?php

session_start();

require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute the SQL statement to fetch the user from the database
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameter to the prepared statement
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $account = $result->fetch_assoc();

    if ($account && password_verify($password, $account["password"])) {

        //echo "CORRECT LOGIN";
        // Redirect to the main page
        header("Location: main.html");
        exit();

    } else {
       header("Location: error.html");
        //echo "Invalid email or password.";
       exit();
    }

}

?>