<?php  

//$host = "localhost";
//$username = "root";
//$password = "";
//$database = "mylibrary";

//$host = getenv('DB_HOST');
//$username = getenv('DB_USER');
//$password = getenv('DB_PASSWORD');
//$database = getenv('DB_NAME');

if(getenv('DB_HOST')) {
    $host = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');
    $database = getenv('DB_NAME');
} else {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "mylibrary";

    }


// Create a new database connection
try {
    $conn = new mysqli($host, $username, $password, $database);
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    die("Database connection failed.");
    
}

// Checks the connection, leaves a Database Connection Failed message if the connection fails and displays the error message
//if($conn->connect_error){
    //die("Database connection failed: " . $conn->connect_error);
//    die("Database connection failed.");
//}

?>