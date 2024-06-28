
<?php
require_once "database2.php";

function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$users = getAllUsers($conn); 

function getAllDoctors($conn) {
    $sql = "SELECT * FROM doctors";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$doctors = getAllDoctors($conn);

function getUpcomingVisits($conn) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE status = 'upcoming'");
   
    $stmt->execute();
    $result = $stmt->get_result();
    $visits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $visits;
}

$upcomingVisits = getUpcomingVisits($conn);

function getUserCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM users";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

$userCount = getUserCount($conn);

function getTodayVisitsCount($conn) {
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE DATE(date) = CURDATE()";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function getSurveyData($filePath) {
    $surveyData = [];
    if (file_exists($filePath)) {
        $file = fopen($filePath, "r");
        while (($line = fgets($file)) !== false) {
            $data = explode(":", trim($line));
            if (count($data) == 2) {
                $surveyData[trim($data[0])] = trim($data[1]);
            }
        }
        fclose($file);
    }
    return $surveyData;
}

$surveyData = getSurveyData("ankiety.txt");

$todayVisitsCount = getTodayVisitsCount($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny Lekarza</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9f9f9;
            color: #333;
            margin: 0;
        }
        #wpadminbar {
            background: #007bff;
            color: #fff;
            height: 32px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 99999;
        }
        #adminmenuwrap {
            float: left;
            width: 200px;
            background: #007bff;
            height: 100vh;
            position: fixed;
            top: 32px;
            padding-top: 10px;
        }
        #adminmenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #adminmenu li {
            margin-bottom: 5px;
        }
        #adminmenu a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            transition: background 0.3s, color 0.3s;
        }
        #adminmenu a:hover {
            background: #0056b3;
            color: #fff;
        }
        #wpcontent {
            margin-left: 200px;
            padding: 50px 20px;
        }
        h1 {
            color: #007bff;
            font-size: 28px;
            margin: 0 0 20px 0;
        }
        .wrap {
            margin: 10px 20px 0 0;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .card h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .card ul {
            list-style: none;
            padding: 0;
        }
        .card ul li {
            padding: 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f7f7f7;
            font-weight: 600;
        }
        select, textarea, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="wpadminbar"></div>
    <div id="adminmenuwrap">
        <ul id="adminmenu">
            <li><a href="test.php">Home</a></li>
            <li><a href="#" onclick="generateReport(); return false;">Generuj raport</a></li>
            <li><a href="manage_doctors.php">Zarzadzaj lekarzami</a></li>
            <li><a href="manage_services.php">Zarzadzaj serwisami</a></li>
            
        </ul>
    </div>
    <div id="wpcontent">
        <div class="wrap">
            <h1>Panel zarzadzania</h1>
            <div class="card">
                <h2>Witaj w panelu administracyjnym</h2>
                <p>Oto podsumowanie Twojej aktywności:</p>
                <ul>
                    <li>Liczba pacjentów: <?php echo $userCount; ?></li>
                    <li>Dzisiejsze wizyty: <?php echo $todayVisitsCount; ?></li>
                    <li>Oczekujące recepty: 3</li>
                </ul>
            </div>
            <div class="card">
                <h2>Nadchodzące wizyty</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Pacjent</th>
                            <th>Data</th>
                            <th>Godzina</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($upcomingVisits)) {
                            foreach ($upcomingVisits as $visit) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($visit['doctor_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($visit['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($visit['time']) . "</td>";
                                
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Brak nadchodzących wizyt.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div id="users" class="card">
                    <h2>Lista Użytkowników</h2>
                    <table>
                        <tr>
                            <th>ID Użytkownika</th>
                            <th>Nazwa Użytkownika</th>
                            <th>Email</th>
                            
                        </tr>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <div class="card">
                <h2>Statystyki</h2>
                <table>
                    <tr>
                        <th>Metryka</th>
                        <th>Wartość</th>
                    </tr>
                    <tr>
                        <td>Liczba zarejestrowanych pacjentów</td>
                        <td><?php echo $userCount; ?></td>
                    </tr>
                    <tr>
                        <td>Dzisiejsze wizyty</td>
                        <td><?php echo $todayVisitsCount; ?></td>
                    </tr>
                    <tr>
                        <td>Nadchodzące wizyty</td>
                        <td><?php echo count($upcomingVisits); ?></td>
                    </tr>
                    <tr>
                        <td>Liczba lekarzy</td>
                        <td><?php echo count($doctors); ?></td>
                    </tr>
                    <tr>
                        <td>Liczba dostępnych usług</td>
                        <td><?php 
                            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM services");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $serviceCount = $result->fetch_assoc()['count'];
                            echo $serviceCount;
                        ?></td>
                    </tr>
                    <tr>
                        <td>Średnia ocena lekarzy</td>
                        <td><?php 
                            $stmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $avgRating = $result->fetch_assoc()['avg_rating'];
                            echo number_format($avgRating, 2);
                        ?></td>
                    </tr>
                </table>
                <div class="card">
                    <h2>Ankiety</h2>
                    <table>
                        <tr>
                            <th>Data</th>
                            <td><?php echo htmlspecialchars($surveyData['Data']); ?></td>
                        </tr>
                        <tr>
                            <th>Ocena opieki</th>
                            <td><?php echo htmlspecialchars($surveyData['Ocena opieki']); ?></td>
                        </tr>
                        <tr>
                            <th>Łatwość umówienia wizyty</th>
                            <td><?php echo htmlspecialchars($surveyData['Łatwość umówienia wizyty']); ?></td>
                        </tr>
                        <tr>
                            <th>Sugestie zmian</th>
                            <td><?php echo htmlspecialchars($surveyData['Sugestie zmian']); ?></td>
                        </tr>
                    </table>
                </div>            
                <div class="card">
                    <h2>Wystawianie dokumentów medycznych</h2>
                    <form action="generate_document.php" method="post">
                        <label for="patient">Wybierz pacjenta:</label>
                        <select name="patient" id="patient">
                            <?php
                            $patients = getAllUsers($conn);
                            foreach ($patients as $patient) {
                                echo "<option value='" . $patient['id'] . "'>" . htmlspecialchars($patient['name'] . ' ' . $patient['last_name']) . "</option>";
                            }
                            ?>
                        </select>
                        <br><br>
                        <label for="document_type">Typ dokumentu:</label>
                        <select name="document_type" id="document_type">
                            <option value="prescription">Recepta</option>
                            <option value="certificate">Zaświadczenie lekarskie</option>
                        </select>
                        <br><br>
                        <label for="content">Treść dokumentu:</label>
                        <textarea name="content" id="content" rows="4" cols="50"></textarea>
                        <br><br>
                        <input type="submit" value="Generuj dokument">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function generateReport() {
        window.location.href = 'generate_report.php';
    }
    </script>
</body>

            
</html>