<?php
require_once "database2.php";


function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getAllDoctors($conn) {
    $sql = "SELECT * FROM doctors";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function getUpcomingVisits($conn) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE status = 'upcoming'");
    $stmt->execute();
    $result = $stmt->get_result();
    $visits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $visits;
}


function getUserCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}


function getTodayVisitsCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE DATE(date) = CURDATE()";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}


$users = getAllUsers($conn);
$doctors = getAllDoctors($conn);
$upcomingVisits = getUpcomingVisits($conn);
$userCount = getUserCount($conn);
$todayVisitsCount = getTodayVisitsCount($conn);


header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=report.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Report Title', 'Data']);
fputcsv($output, ['User Count', $userCount]);
fputcsv($output, ['Today Visits Count', $todayVisitsCount]);
fputcsv($output, []); 
fputcsv($output, ['Upcoming Visits']);
fputcsv($output, ['Doctor Name', 'Date', 'Time']);
foreach ($upcomingVisits as $visit) {
    fputcsv($output, [$visit['doctor_name'], $visit['date'], $visit['time']]);
}
fputcsv($output, []); 
fputcsv($output, ['Users']);
fputcsv($output, ['ID', 'Name', 'Email']);
foreach ($users as $user) {
    fputcsv($output, [$user['id'], $user['name'], $user['email']]);
}

fclose($output);
?>