<?php
// LIVE Database configuration
$host = "sql100.infinityfree.com"; // Tasweer se liya gaya host
$dbname = "if0_41714280_db_accessform"; // Tasweer se liya gaya database name
$username = "if0_41714280"; // Aapka account username
$password = "Arqam726"; // Pihcle step wala password (wt8...) yahan likhein!

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