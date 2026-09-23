<?php  

$host = "localhost";
$username = "root";
$password = "";
$database = "mylibrary";

// Create a new database connection
$conn = new mysqli($host, $username, $password, $database);

if($conn->connect_error){
    die("Database connection failed: " . $conn->connect_error);
}

// Used to check if the connection is successful
echo "Database connected sucessfully";


?>