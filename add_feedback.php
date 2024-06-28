<?php
session_start();
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = "Musisz być zalogowany, aby dodać opinię.";
        header("Location: login.php");
        exit();
    }

    
    $visit_id = $_POST['visit_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    
    if (empty($visit_id) || empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        $_SESSION['error_message'] = "Nieprawidłowe dane. Spróbuj ponownie.";
        header("Location: accountPage.php");
        exit();
    }

    
    $query = "INSERT INTO feedback (visit_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiis", $visit_id, $user_id, $rating, $comment);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Dziękujemy za Twoją opinię!";
    } else {
        $_SESSION['error_message'] = "Wystąpił błąd podczas dodawania opinii. Spróbuj ponownie.";
    }
    
    $stmt->close();
    $conn->close();

    
    header("Location: accountPage.php");
    exit();
} else {
    
    header("Location: index.php");
    exit();
}
?>