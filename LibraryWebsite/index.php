<!-- Redirect to login page if not logged in when library website is accessed-->
<!-- this is done for security purposes to prevent unauthorized access to the library website -->
<?php
    header("Location: login.html");
    exit;
?>