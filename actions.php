<?php

    require_once "database2.php";

    if ($conn->connect_error) {
        die("Błąd połączenia: " . $conn->connect_error);
    }

    function issuePrescription($doctor_id, $doctor_name, $patient_id, $medication, $expiry_date) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO prescriptions (doctor_id, doctor_name, patient_id, medication, date_issued, expiry_date) VALUES (?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("isiss", $doctor_id, $doctor_name, $patient_id, $medication, $expiry_date);
        return $stmt->execute();
    }

    function issueMedicalLeave($doctor_id, $doctor_name, $patient_id, $reason, $start_date, $end_date) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO medical_leave (doctor_id, doctor_name, patient_id, reason, start_date, end_date, date_issued) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isisss", $doctor_id, $doctor_name, $patient_id, $reason, $start_date, $end_date);
        return $stmt->execute();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        $doctor_id = intval($_POST['doctor_id']);
        $doctor_name = $_POST['doctor_name'];
        $patient_id = intval($_POST['patient_id']);
        if ($action === 'prescription') {
            $medication = $_POST['medication'];
            $expiry_date = $_POST['expiry_date'];
            if (issuePrescription($doctor_id, $doctor_name, $patient_id, $medication, $expiry_date)) {
                echo "E-recepta została wystawiona.";
            } else {
                echo "Błąd podczas wystawiania e-recepty.";
            }
        } elseif ($action === 'medical_leave') {
            $reason = $_POST['reason'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            if (issueMedicalLeave($doctor_id, $doctor_name, $patient_id, $reason, $start_date, $end_date)) {
                echo "Zwolnienie lekarskie zostało wystawione.";
            } else {
                echo "Błąd podczas wystawiania zwolnienia lekarskiego.";
            }
        }
    }
?>
