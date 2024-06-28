<?php
require_once "database2.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patientId = $_POST['patient'];
    $documentType = $_POST['document_type'];
    $content = $_POST['content'];

    
    $stmt = $conn->prepare("SELECT name, last_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();

    
    $fileName = $documentType . '_' . $patient['name'] . '_' . $patient['last_name'] . '.txt';

    
    $documentContent = "Dokument: " . ($documentType == 'prescription' ? 'Recepta' : 'Zaświadczenie lekarskie') . "\n";
    $documentContent .= "Pacjent: " . $patient['name'] . ' ' . $patient['last_name'] . "\n\n";
    $documentContent .= $content;

    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . strlen($documentContent));

    
    echo $documentContent;
    exit;
} else {
    echo "Nieprawidłowe żądanie.";
}
?>