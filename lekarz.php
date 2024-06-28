<?php
session_start();
require_once 'klasy.php'; 
require_once 'database.php'; 

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


$query = "SELECT rating, comment, date FROM reviews WHERE visit_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $lekarzId);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}
$query = "SELECT AVG(rating) as average_rating, COUNT(*) as review_count FROM reviews WHERE visit_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $lekarzId);
$stmt->execute();
$result = $stmt->get_result();
$rating_data = $result->fetch_assoc();

$average_rating = round($rating_data['average_rating'], 1);
$review_count = $rating_data['review_count'];
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Lekarza</title>
    <link rel="stylesheet" type="text/css" href="navigator.css">
    <style>
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
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        

        header {
            text-align: center;
            padding: 20px 0;
            background-color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
            color: #333;
        }

        header h2 {
            margin: 10px 0;
            font-size: 1.5em;
            color: #777;
        }

        .rating {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rating .star {
            font-size: 1.5em;
            color: #ffd700;
            cursor: pointer;
        }

        .rating span {
            margin-left: 10px;
            font-size: 1em;
            color: #333;
        }

        .profile {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .profile img {
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile .details {
            flex: 1;
        }

        .profile .details p {
            margin: 5px 0;
        }

        .profile .details button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .rating .average-rating {
            font-size: 1.2em;
            font-weight: bold;
            color: #ffd700;
            margin: 0 10px;
        }
        .rating .star.active {
            color: #ffd700;
        }
        .services, .description, .reviews, .submit-review {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .services h3, .description h3, .reviews h3, .submit-review h3 {
            margin-top: 0;
        }

        .services ul {
            list-style: none;
            padding: 0;
        }

        .services ul li {
            margin: 10px 0;
        }

        .submit-review form {
            display: flex;
            flex-direction: column;
        }

        .submit-review form label {
            margin: 10px 0 5px;
        }

        .submit-review form select, .submit-review form textarea, .submit-review form input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-review form input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <ul class="sidebar">
                <li onclick=closeSidebar()><a href="#home"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
                <li><a href="accountPage.php">Konto</a></li>
                <li><a href="lekarze.php">Lekarze</a></li>
                <li><a href="#receipt">E-recepta</a></li>
                <li><a href="adminPanel.php">Dla lekarza</a></li>
                <li><a href="services.php">Usługi</a></li>
                <li><a href="#contact">Kontakt</a></li>
            </ul>
            <ul>
                <li><a href="#home">Home</a></li>
                <li class="hideOnMobile"><a href="accountPage.php">Konto</a></li>
                <li class="hideOnMobile"><a href="lekarze.php">Lekarze</a></li>
                <li class="hideOnMobile"><a href="e-recepty.php">E-recepta</a></li>
                <li class="hideOnMobile"><a href="services.php">Usługi</a></li>
                <li class="hideOnMobile"><a href="#contact">Kontakt</a></li>
                <li class="hideOnMobile"><a href="adminPanel.php">Dla lekarza</a></li>
                <li onclick=showSidebar() class="menuBtn"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
            </ul>
        </nav>
        <header>
            <h1><?php echo $lekarz->pelneNazwisko(); ?></h1>
            <h2>Lekarz ogólny/Internista</h2>
            <div class="rating">
                <span class="star" data-value="5">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="1">★</span>
                <span class="average-rating"><?php echo $average_rating; ?></span>
                <span>(<?php echo $review_count; ?> opinie)</span>
            </div>
        </header>
        <section class="profile">
            <img src="profile.jpg" alt="<?php echo $lekarz->pelneNazwisko(); ?>">
            <div class="details">
                <p>1760 poleceń lekarza</p>
                <p>Gabinet On-line</p>
                <p>Przyjmuje w: Śr, Czw, Pt, Sob, Nd, Wt, Pon</p>
                <p>Wystawiam <strong>recepty. Nie wystawiam zwolnień</strong></p>
                <button onclick="window.location.href='booking.php?id=<?php echo $lekarzId; ?>'">Umów e-Wizytę (Wybierz termin)</button>
            </div>
        </section>
        <section class="services">
            <h3>Lekarz oferuje usługi:</h3>
            <ul>
                <li>eRecepta - konsultacja lekarska - 89 zł</li>
                <li>Kontynuacja antykoncepcji - e-Recepta - 59 zł</li>
            </ul>
        </section>
        <section class="description">
            <h3>O "<?php echo $lekarz->pelneNazwisko(); ?>"</h3>
            <p>Posiadam duże doświadczenie w udzielaniu świadczeń medycznych realizowanych za pomocą środków porozumiewania się na odległość...</p>
        </section>
        <section class="reviews">
            <h3>Opinie</h3>
            <div id="reviews-container">
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review">
                            <p><strong><?php echo isset($review['name']) ? $review['name'] : 'Anonimowy'; ?></strong> (<?php echo $review['date']; ?>)</p>
                            <p><?php echo $review['comment']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Brak opinii.</p>
                <?php endif; ?>
            </div>
        </section>
        <section class="submit-review">
            <h3>Wystaw opinię</h3>
            <?php if (isset($_SESSION['feedback_message'])): ?>
                <p><?php echo $_SESSION['feedback_message']; unset($_SESSION['feedback_message']); ?></p>
            <?php endif; ?>
            <form action="submit_feedback.php" method="post">
                <input type="hidden" name="visit_id" value="<?php echo $lekarzId; ?>">
                
                <label for="rating">Ocena:</label>
                <select name="rating" id="rating" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br>
                <label for="comment">Komentarz:</label>
                <textarea name="comment" id="comment" required></textarea>
                <br>
                <input type="submit" value="Wyślij opinię">
            </form>
        </section>
    </div>

    <script>
        function showSidebar() {
            document.querySelector('.sidebar').style.display = 'flex';
        }

        function closeSidebar() {
            document.querySelector('.sidebar').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const averageRating = <?php echo $average_rating; ?>;

            stars.forEach(star => {
                if (star.dataset.value <= averageRating) {
                    star.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>