<?php
session_start();
require_once 'config.php';


$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $conn->prepare("SELECT * FROM services");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);


function getUniqueCategories($categories) {
    $uniqueCategories = [];
    foreach ($categories as $category) {
        if (!in_array($category['name'], array_column($uniqueCategories, 'name'))) {
            $uniqueCategories[] = $category;
        }
    }
    return $uniqueCategories;
}

$uniqueCategories = getUniqueCategories($categories);


function wyswietlAnkiete() {
  echo '<h2>Ankieta satysfakcji pacjenta</h2>';
  echo '<form method="post" action="">';
  
  echo '<p>Jak oceniasz ogólną jakość opieki w naszej poradni?</p>';
  echo '<select name="ocena_opieki">
          <option value="5">Bardzo dobra</option>
          <option value="4">Dobra</option>
          <option value="3">Przeciętna</option>
          <option value="2">Słaba</option>
          <option value="1">Bardzo słaba</option>
        </select>';

  echo '<p>Czy łatwo było umówić wizytę?</p>';
  echo '<input type="radio" name="latwosc_wizyty" value="tak"> Tak
        <input type="radio" name="latwosc_wizyty" value="nie"> Nie';

  echo '<p>Jakie zmiany zaproponowałbyś/abyś w funkcjonowaniu naszej poradni?</p>';
  echo '<textarea name="sugestie_zmian" rows="4" cols="50"></textarea>';

  echo '<p><input type="submit" value="Wyślij ankietę" name="ankieta"></p>';
  echo '</form>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ankieta'])) {
   
    $ocena_opieki = $_POST['ocena_opieki'];
    $latwosc_wizyty = $_POST['latwosc_wizyty'];
    $sugestie_zmian = $_POST['sugestie_zmian'];
    
    
    $dane_ankiety = "Data: " . date("Y-m-d") . "\n";
    $dane_ankiety .= "Ocena opieki: $ocena_opieki\n";
    $dane_ankiety .= "Łatwość umówienia wizyty: $latwosc_wizyty\n";
    $dane_ankiety .= "Sugestie zmian: $sugestie_zmian\n";
    $dane_ankiety .= "------------------------------\n";
    
    
    $plik = fopen("ankiety.txt", "a");
    fwrite($plik, $dane_ankiety);
    fclose($plik);
    
    
   
    
}

?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="navigator.css">
    <link rel="stylesheet" type="text/css" href="mainer.css">
    <title>Przychodnia</title>
    <style>
        .wizyta-container {
            text-align: center;
            padding: 2rem;
            background-color: #fff;
            margin: 2rem;
            border-radius: 1rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .wizyta-container:hover {
            transform: translateY(-0.5rem);
        }
        .wizyta-container h1 {
            margin: 0.5rem 0;
            color: #007bff;
        }

        .wizyta-container p {
            color: #666;
        }
        .wizyta-container a:hover {
            color: #0056b3;
        }
        .mapa{
            justify-content:center;
            align-items:center;
        }
        .services, .categories, .doctors, .medicines, .prescriptions, .ask-doctor, .contact, .doctor-panel, .mapa  {
            padding: 2rem;
            background-color: #fff;
            margin: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .services h2, .categories h2, .doctors h2, .medicines h2, .prescriptions h2, .ask-doctor h2, .contact h2, .doctor-panel h2 {
            text-align: center;
            margin-bottom: 2rem;
        }

        .services {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .service {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            flex: 1 1 calc(33.333% - 2rem);
            margin: 1rem;
            box-sizing: border-box;
        }

        .service:hover {
            transform: translateY(-0.5rem);
        }

        .service h3 {
            margin-top: 0;
            font-size: 1.5rem;
        }

        .service p {
            color: #666;
            line-height: 1.5;
        }

        .categories ul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .categories li {
            margin: 0.5rem;
        }

        .categories a {
            display: block;
            padding: 0.5rem 1rem;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: background 0.3s ease;
        }

        .categories a:hover {
            background: #0056b3;
        }

        .doctor-card {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .doctor-card:hover {
            transform: translateY(-0.5rem);
        }

        .doctor-card img {
            max-width: 100%;
            border-radius: 50%;
            margin-bottom: 1rem;
        }

        .doctor-card h3 {
            margin-top: 0;
            font-size: 1.5rem;
        }

        .doctor-card p {
            color: #666;
            line-height: 1.5;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .menuBtn {
            display: none;
        }

        @media (max-width: 768px) {
            .hideOnMobile {
                display: none;
            }

            .menuBtn {
                display: block;
            }
        }

        

            .aktualnosci {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f5f5f5;
                border: 1px solid #ddd;
                border-radius: 5px;
                }

                .aktualnosci h2 {
                color: #333;
                font-size: 24px;
                margin-bottom: 20px;
                }

                .aktualnosci ul {
                list-style-type: none;
                padding: 0;
                }

                .aktualnosci li {
                margin-bottom: 20px;
                }

                .aktualnosci h3 {
                color: #555;
                font-size: 18px;
                margin-bottom: 10px;
                }

                .aktualnosci p {
                color: #777;
                line-height: 1.5;
                }

                .contact form {
                    background-color: #fff;
                    padding: 2rem;
                    border-radius: 1rem;
                    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                    margin: 0 auto;
                }
                .contact form h2 {
                    color: #007bff;
                    text-align: center;
                    margin-bottom: 1rem;
                }
                .contact form p {
                color: #333;
            }

            .contact form select, 
            .contact form input[type="radio"], 
            .contact form textarea {
                display: block;
                width: 100%;
                padding: 0.5rem;
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
            }

            .contact form input[type="submit"] {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .contact form input[type="submit"]:hover {
                background-color: #0056b3;
            }

                .mapa {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    margin: 20px 0;
                }

                .mapa iframe {
                    max-width: 100%;
                    border: none;
                }
                .doctor-list {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 1rem;
                }

                .doctor-container {
                    background-color: #fff;
                    padding: 2rem;
                    border-radius: 1rem;
                    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
                    max-width: 300px;
                    transition: transform 0.3s ease;
                }

                .doctor-container:hover {
                    transform: translateY(-0.5rem);
                }

                .doctor-container h3 {
                    color: #007bff;
                    margin-top: 0;
                }

                .doctor-container p {
                    color: #333;
                }

                .doctor-container a {
                    color: #007bff;
                    text-decoration: none;
                    transition: color 0.3s ease;
                }

                .doctor-container a:hover {
                    color: #0056b3;
                }
                
                .contact-info {
                    background-color: #fff;
                    padding: 2rem;
                    border-radius: 1rem;
                    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
                    max-width: 600px;
                    margin: 0 auto;
                    margin-top: 2rem;
                }

                .contact-info h2 {
                    color: #007bff;
                    text-align: center;
                    margin-bottom: 1rem;
                }

                .contact-info p {
                    color: #333;
                }
    </style>
        
</head>
<body>
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
            <li class="hideOnMobile"><a href="#contact-info">Kontakt</a></li>
            <li class="hideOnMobile"><a href="adminPanel.php">Dla lekarza</a></li>
            <li onclick=showSidebar() class="menuBtn"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>
    <main>
        <div class='wizyta-container'>
            <h1 class='first'>Szukasz lekarza online?</h1>
            <h1 class='second'>Umów się na wizytę</h1>
            <h3>Szybko, bezpiecznie, z gwarancją pracy wykonanej przez ekspertów.</h3>
            <p>Wypełnij <a href="#">formularz medyczny</a> lub <a href="#">umów teleporadę</a> wybierając dostępnego <a href="lekarze.php">lekarza</a> i otrzymaj <a href="#receipt">receptę</a> i <a href="#">zwolnienie</a> bez wychodzenia z domu!</p>
        </div>
    </main>
    <section class="services">
        
        <br>
        <?php
        $displayed_services = [];
        foreach ($services as $service):
            if (in_array($service['name'], $displayed_services)) {
                continue;

            }
            $displayed_services[] = $service['name'];
            ?>
            <div class="service">
                <h3><?= $service['name'] ?></h3>
                <p>Cena: <?= $service['price'] ?> zł</p>
                <p>Kategoria: <?= $categories[array_search($service['category_id'], array_column($categories, 'id'))]['name'] ?></p>
                <p><?= $service['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </section>
    <section class="categories">
        <h2>Kategorie usług</h2>
        <ul>
            <?php foreach ($uniqueCategories as $category): ?>
                <li><a href="services.php?category=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="doctors">
        <h2>Nasi Lekarze</h2>
        <div class="doctor-list">
            <?php
            require_once 'klasy.php';

            $count = 0;

            foreach ($array as $doktor) {
                if ($count >= 5) {
                    break;
                }
                $licenseNumber = $doktor->numerLegitymacji();
                ?>
                <div class="doctor-container">
                    <h3>
                        <a href="lekarz.php?id=<?php echo $licenseNumber; ?>">
                            <?php echo $doktor->pelneNazwisko(); ?>
                        </a>
                    </h3>
                    <p><strong>Specjalizacja:</strong> <?php echo $doktor->informacjeOSpecjalizacji(); ?></p>
                    <p><strong>Numer Legitymacji:</strong> <?php echo $licenseNumber; ?></p>
                    <p><strong>Usługi:</strong> <?php echo $doktor->informacjeOUslugach(); ?></p>
                    <?php if (isset($doktor->email)) { ?>
                        <p><strong>Email:</strong> <?php echo $doktor->email; ?></p>
                    <?php } ?>
                </div>
                <?php
                $count++;
            }
            ?>
        </div>
    </section>
    <section class='aktualnosci'>
        <h2>Aktualności</h2>
            <ul>
                <li>
                <h3>Zmiana godzin otwarcia w dniu 2 maja</h3>
                <p>W czwartek, 2 maja, poradnia będzie czynna w godzinach 8:00 - 14:00.</p>
                </li>
                <li>
                <h3>Dni wolne od pracy w maju</h3>
                <p>Informujemy, że w maju poradnia będzie nieczynna w następujących dniach:</p>
                <ul>
                    <li>1 maja (Święto Pracy)</li>
                    <li>3 maja (Święto Konstytucji 3 Maja)</li>
                </ul>
                </li>
                <li>
                <h3>Warsztaty dla rodziców</h3>
                <p>W dniu 15 maja odbędą się warsztaty dla rodziców na temat "Jak radzić sobie ze stresem u dzieci". Zapisy przyjmujemy do 10 maja.</p>
                </li>
            </ul>
    </section>

    
    <section class="contact">
        
        
    
        <div>
            <?php
                wyswietlAnkiete();
            ?>
        </div>
        
    </section>
    <section class='mapa'>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13145.05608015921!2d18.62055640749657!3d54.3792507746668!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46fd749446d19051%3A0x6e496e368e3c272a!2sManhattan%20Gda%C5%84skie%20Centrum%20Handlowe!5e0!3m2!1spl!2spl!4v1719566162533!5m2!1spl!2spl" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    </section>
    
    <div class="contact-info">
        <h2>Dane kontaktowe</h2>
        <p><strong>Numer telefonu:</strong> +48 123 456 789</p>
        <p><strong>Email:</strong> kontakt@przychodnia.pl</p>
    </div>
    
    <script>
        function showSidebar(){
            const sidebar=document.querySelector('.sidebar')
            sidebar.style.display='flex'
        }
        function closeSidebar(){
            const sidebar=document.querySelector('.sidebar')
            sidebar.style.display='none'
        }
    </script>
</body>
</html>
