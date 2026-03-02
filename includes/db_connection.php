<?php
// Database configuration
$host = "localhost";
$dbname = "access_form";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception so we can catch errors easily
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Uncomment the line below to test the connection initially, then comment it out again
    // echo "Database Connected Successfully!";
    
} catch (PDOException $e) {
    // If there is an error, display it and stop the script
    die("Database Connection Failed: " . $e->getMessage());
}
?>