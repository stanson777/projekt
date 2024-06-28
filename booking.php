<?php
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_set_cookie_params(60 * 60 * 24 * 30);
session_start();

require_once 'klasy.php';
require_once 'database2.php';

if (!isset($_SESSION['user'])) {
    header("Location: loginPage.php");
    exit();
}

$userId = $_SESSION['user']; 
$lekarzId = $_GET['id'];

function znajdzLekarzaPoId($id) {
    global $array;
    foreach ($array as $lekarz) {
        if ($lekarz->numerLegitymacji() == $id) {
            return $lekarz;
        }
    }
    return null;
}

$lekarz = znajdzLekarzaPoId($lekarzId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['appointment-date'];
    $time = $_POST['appointment-time'];
    $description = isset($_POST['appointment-description']) ? $_POST['appointment-description'] : '';
    $recommendations = isset($_POST['appointment-recommendations']) ? $_POST['appointment-recommendations'] : '';
    $today = date('Y-m-d');
    $dateTime = $date . ' ' . $time;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE dateTime = ?");
    $stmt->bind_param("s", $dateTime);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        $status = ($date < $today) ? 'history' : 'upcoming';
        $stmt = $conn->prepare("INSERT INTO appointments (doctor_id, doctor_name, user_id, date, time, dateTime, description, recommendations, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isissssss", $lekarzId, $lekarz->pelneNazwisko(), $userId, $date, $time, $dateTime, $description, $recommendations, $status);
        $stmt->execute();
        $stmt->close();

        if ($status == 'history') {
            echo "<script>alert('Wizyta zarezerwowana na $date o godzinie $time i dodana do historii wizyt');</script>";
        } else {
            echo "<script>alert('Wizyta zarezerwowana na $date o godzinie $time');</script>";
        }
    } else {
        echo "<script>alert('Ten slot jest już zajęty. Wybierz inną datę lub godzinę.');</script>";
    }
}

function getUpcomingVisits($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE status = 'upcoming' AND user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $visits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $visits;
}

$upcomingVisits = getUpcomingVisits($conn, $userId);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezerwacja Wizyty</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" type="text/css" href="navigator.css">
    <style>
        .booking-container {
            text-align: center;
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .calendar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .timeslots {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .timeslot {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .timeslot.selected {
            background-color: #007bff;
            color: #fff;
        }
        
        .timeslot.disabled {
            background-color: #ddd;
            color: #888;
            cursor: not-allowed;
        }

        .appointment-details {
            margin-top: 20px;
        }

        .appointment-details textarea {
            width: 100%;
            margin-bottom: 10px;
        }

        .visit-history {
            margin-top: 30px;
            text-align: left;
        }

        .visit {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=closeSidebar()><a href="test.php"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="accountPage.php">Konto</a></li>
            <li><a href="lekarze.php">Lekarze</a></li>
            <li><a href="#receipt">E-recepta</a></li>
            <li><a href="adminPanel.php">Dla lekarza</a></li>
            <li><a href="services.php">Usługi</a></li>
            <li><a href="#contact">Kontakt</a></li>
        </ul>
        <ul>
            <li><a href="test.php">Home</a></li>
            <li class="hideOnMobile"><a href="accountPage.php">Konto</a></li>
            <li class="hideOnMobile"><a href="lekarze.php">Lekarze</a></li>
            <li class="hideOnMobile"><a href="e-recepty.php">E-recepta</a></li>
            <li class="hideOnMobile"><a href="services.php">Usługi</a></li>
            <li class="hideOnMobile"><a href="#contact">Kontakt</a></li>
            <li class="hideOnMobile"><a href="adminPanel.php">Dla lekarza</a></li>
            <li onclick=showSidebar() class="menuBtn"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>
    <div class="booking-container">
        <h1>Rezerwuj e-Wizytę</h1>
        <h2>Wybierz z kalendarza dogodną dla Ciebie datę i godzinę e-wizyty</h2>
        <form method="post" action="">
            <div class="calendar">
                <input type="date" id="appointment-date" name="appointment-date">
            </div>
            <div class="timeslots">
                <?php
                if (isset($_POST['appointment-date'])) {
                    $date = $_POST['appointment-date'];
                } else {
                    $date = '';
                }
                $times = ["15:30", "15:45", "16:00", "16:15", "16:30", "16:45", "17:00", "17:15", "17:30", "17:45", "18:00", "18:15", "18:30", "18:45", "19:00", "19:15", "19:30", "19:45", "20:00", "20:15", "20:30", "20:45"];
                foreach ($times as $time) {
                    $dateTime = $date . ' ' . $time;
                    $disabled = !empty($date) && in_array($dateTime, array_column($upcomingVisits, 'dateTime'));
                    echo "<label class='timeslot" . ($disabled ? ' disabled' : '') . "'><input type='radio' name='appointment-time' value='$time'" . ($disabled ? ' disabled' : '') . "> $time</label>";
                }
                ?>
            </div>
            <button type="submit">Potwierdź Wizytę</button>
        </form>
    </div>

</body>
</html>
