<?php
$hostName = 'localhost';
$dbUser = 'root';
$dbPassword = "";
$dbName = 'lekarze';

try {
    $conn = new PDO("mysql:host=$hostName;dbname=$dbName", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
