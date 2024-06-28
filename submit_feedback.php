<?php
session_start();
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visit_id = $_POST['visit_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    $query = "INSERT INTO reviews (visit_id, rating, comment, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $visit_id, $rating, $comment);

    if ($stmt->execute()) {
        $_SESSION['feedback_message'] = "Dziękuję za Twoją opinię!";
    } else {
        $_SESSION['feedback_message'] = "Wystąpił błąd podczas dodawania opinii. Spróbuj ponownie.";
    }

    header("Location: lekarz.php?id=" . $visit_id);
    exit();
}
?>