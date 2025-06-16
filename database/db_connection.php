<?php
    $servername = "asdasdasd";
    $username = "asdasda";  
    $password = "asdasd";    
    $dbname = "asdasdasdasd";
    
    // Actually create the connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        // Don't expose detailed error information to users
        echo "<div class='alert alert-danger'>Database connection error. Please contact the administrator.</div>";
    }
    
    // Set UTF-8 character set for database connection
    $conn->set_charset("utf8mb4");
?>