<?php

session_start();

require "database.php";

// Initializes the login attempt session is not already set
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the email and password from the POST request from the database
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute the SQL statement to fetch the user from the database
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);

    // Bind the parameter to the prepared statement
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Get the result of the query and fetch the account data
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();


    // If and else statement to check if the account exists and if the password is correct
    if ($account && password_verify($password, $account["password"])) {

        $_SESSION["login_attempts"] = 0;

        // Redirect to the main page
        header("Location: main.html");
        exit();

    } else {

        // Counts the number of failed login attempts 
        $_SESSION["login_attempts"]++;

        if ($_SESSION["login_attempts"] >= 5) {
           

            // Reset the login attempts after reaching the limit
            $_SESSION["login_attempts"] = 0; 

            //redirect to error page with alert message after 5 failed login attempts
            echo "<script>
                    alert('Too many failed login attempts. Please try again later.');
                    window.location.href = 'error.html';
            </script>";

            exit();

        } else {

            //sends an alert message to the user with the number of failed login attempts and redirects back to the login page
            echo "<script>
                alert('Invalid email or password. Login attempts: " . $_SESSION["login_attempts"] . " of 5');
                window.location.href = 'login.html';   
            </script>";
        
            exit();
        }
    }
}

?>