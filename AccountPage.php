<?php
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
session_set_cookie_params(60 * 60 * 24 * 30);
session_start();


$isLoggedIn = isset($_SESSION['user']);


require_once 'klasy.php';
require_once 'database2.php';

if (!isset($_SESSION['user'])) {
    header("Location: loginPage.php");
    exit();
}

$userId = $_SESSION['user'];

function getRandomDescription() {
    $descriptions = [
        "Rutynowa kontrola ciśnienia krwi i poziomu cholesterolu. Wyniki w normie.",
        "Konsultacja w sprawie bólu pleców. Zalecono ćwiczenia wzmacniające mięśnie grzbietu.",
        "Badanie kontrolne po przebytej infekcji górnych dróg oddechowych. Stan pacjenta uległ poprawie.",
        "Omówienie wyników badań krwi. Stwierdzono lekką anemię.",
        "Konsultacja dermatologiczna w sprawie wysypki na skórze. Przepisano maść łagodzącą.",
        "Wizyta kontrolna po zabiegu usunięcia znamienia. Gojenie przebiega prawidłowo."
    ];
    return $descriptions[array_rand($descriptions)];
}

function getRandomRecommendation() {
    $recommendations = [
        "Zwiększyć spożycie warzyw i owoców. Kontynuować regularne ćwiczenia fizyczne.",
        "Przyjmować przepisane leki zgodnie z zaleceniami. Kontrola za 3 miesiące.",
        "Wykonywać ćwiczenia oddechowe 2 razy dziennie. Unikać narażenia na dym papierosowy.",
        "Suplementacja żelaza przez 2 miesiące. Kontrolne badanie krwi za 8 tygodni.",
        "Stosować przepisaną maść 2 razy dziennie. Unikać gorących kąpieli i silnych detergentów.",
        "Delikatnie masować bliznę maścią witaminową. Kontrola za 4 tygodnie."
    ];
    return $recommendations[array_rand($recommendations)];
}


function getVisitHistory($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE user_id = ? AND status = 'history' ORDER BY dateTime DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $visits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach ($visits as &$visit) {
        if (empty($visit['description'])) {
            $visit['description'] = getRandomDescription();
        }
        if (empty($visit['recommendations'])) {
            $visit['recommendations'] = getRandomRecommendation();
        }
    }
    return $visits;
}


function getUpcomingVisits($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE user_id = ? AND status = 'upcoming' ORDER BY dateTime ASC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $visits = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $visits;
}

$visitHistory = getVisitHistory($conn, $userId);
$upcomingVisits = getUpcomingVisits($conn, $userId);





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="navigator.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .account-section {
            margin-top: 20px;
        }

        .account-section h3 {
            margin-top: 10px;
        }

        form {
            margin-top: 20px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .visit-history, .upcoming-visits {
            margin-top: 20px;
        }

        .visit-history h3, .upcoming-visits h3 {
            margin-top: 10px;
        }

        .visit-history ul, .upcoming-visits ul {
            list-style-type: none;
            padding: 0;
        }

        .visit-history li, .upcoming-visits li {
            background: #f4f4f4;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
        }
        nav {
            background-color: #007bff;
            color: #fff;
            padding: 1rem;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul.sidebar {
            display: none;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #007bff;
            padding-top: 2rem;
        }

        nav ul.sidebar li {
            margin: 1rem 0;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: block;
        }

        nav ul li a:hover {
            background-color: #0056b3;
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
    <h1>Panel konta</h1>

    <div class="container">
        <?php if ($isLoggedIn): ?>
            
            

        <div class="account-section">
        
        <div class="visit-history">
            <h3>Historia wizyt</h3>
            <?php if (!empty($visitHistory)): ?>
                <ul>
                    <?php foreach ($visitHistory as $visit): ?>
                        <li>
                            <strong>Lekarz:</strong> <?php echo htmlspecialchars($visit['doctor_name']); ?><br>
                            <strong>Data:</strong> <?php echo htmlspecialchars($visit['date']); ?><br>
                            <strong>Godzina:</strong> <?php echo htmlspecialchars($visit['time']); ?><br>
                            <strong>Opis spotkania:</strong> <?php echo htmlspecialchars($visit['description'] ?: getRandomDescription()); ?><br>
                            <strong>Zalecenia:</strong> <?php echo htmlspecialchars($visit['recommendations'] ?: getRandomRecommendation()); ?><br>
                            
                            <?php if (empty($visit['rating'])): ?>
                                <form action="submit_feedback2.php" method="post">
                                    <input type="hidden" name="visit_id" value="<?php echo $visit['doctor_id']; ?>">
                                    <label for="rating">Ocena:</label>
                                    <select name="rating" id="rating">
                                        <option value="1">1 - Bardzo źle</option>
                                        <option value="2">2 - Źle</option>
                                        <option value="3">3 - Przeciętnie</option>
                                        <option value="4">4 - Dobrze</option>
                                        <option value="5">5 - Bardzo dobrze</option>
                                    </select>
                                    <label for="comment">Komentarz:</label>
                                    <textarea name="comment" id="comment" placeholder="Komentarz"></textarea>
                                    <input type="submit" value="Dodaj opinię">
                                </form>
                            <?php else: ?>
                                <strong>Ocena:</strong> <?php echo htmlspecialchars($visit['rating']); ?><br>
                                <strong>Komentarz:</strong> <?php echo htmlspecialchars($visit['comment']); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Brak wizyt w historii.</p>
            <?php endif; ?>
        </div>

        <h3>Nadchodzące wizyty</h3>
        <div class="upcoming-visits">
            <ul>
                <?php if (!empty($upcomingVisits)): ?>
                    <?php foreach ($upcomingVisits as $visit): ?>
                        <li>
                            <strong>Lekarz:</strong> <?php echo htmlspecialchars($visit['doctor_name']); ?><br>
                            <strong>Data:</strong> <?php echo htmlspecialchars($visit['date']); ?><br>
                            <strong>Godzina:</strong> <?php echo htmlspecialchars($visit['time']); ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak nadchodzących wizyt.</p>
                <?php endif; ?>
            </ul>
        </div>
    </div>

                <h3>Zarządzanie danymi</h3>
                <p>Tu możesz zarządzać swoimi danymi osobowymi.</p>
                <form action='editData.php' method='post'>
                    <input type='submit' value='Edytuj konto'>
                </form>
                <form action='logout.php' method='post'>
                    <input type='submit' value='Wyloguj się'>
                </form>
            </div>
        <?php else: ?>
            <h2>Nie jesteś zalogowany</h2>
            <form action='loginPage.php' method='post'>
                <input type='submit' value='Zaloguj się'>
            </form>
            <form action='registerPage.php' method='post'>
                <input type='submit' value='Zarejestruj się'>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>