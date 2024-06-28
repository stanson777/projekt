
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="navigator.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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

        .container{
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
        }
        .doctor-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
        }
        .filterer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin: 10px;
        }

        .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 200px;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
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
                <li class="hideOnMobile"><a href="#contact">Kontakt</a></li>
                <li class="hideOnMobile"><a href="adminPanel.php">Dla lekarza</a></li>
                <li onclick=showSidebar() class="menuBtn"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
            </ul>
        </nav>
        
        <div class="container">
        <h1>Znajdz doktorów</h1>
        <form action="" method="post">

            <div class='filterer'>
                <div class="form-group">
                    <input type="text" name="doctor" placeholder="Szukaj lekarzy" class="form-control">
                </div>
                <div class="form-group">
                    <select name="specjalizacja" class="form-control" id='select1'>
                        <option value="">Wybierz specjalizację</option>
                        <option value="wszystkie">Wszystkie</option>
                        <option value="chirurg">Chirurg</option>
                        <option value="kardiolog">Kardiolog</option>
                        <option value="neurolog">Neurolog</option>
                        <option value="pediatra">Pediatra</option>
                        <option value="dermatolog">Dermatolog</option>
                    </select>
                </div>

                <div class='form-group'>
                    <select name='usluga' class='form-control' id='select2' disabled>
                        <option value=''>Wybierz usługę</option>
                        <option value='zwolenie'></option>
                    </select>
                </div>

                <div class="form-btn">
                    <button type="submit" class="btn btn-primary">Szukaj</button>
                </div>

                <div class='form-group'>
                    <select name='sortowanie' class='form-control'>
                        <option value=''>Dostępności</option>
                        <option value='oceny'>Oceny</option>
                        <option value='Ilość opini'>Ilość opinii</option>
                    </select>
                </div>
        </form>
        <?php
            require_once 'klasy.php';
           
            require_once 'database.php';
            

            function srednia_ocena($numerLegitki){

                global $conn;
                $sql = "
                    SELECT d.doctors_id, AVG(r.rating) AS average_rating
                    FROM doctors d
                    JOIN reviews r ON d.doctors_id = r.visit_id
                    WHERE d.doctors_id=$numerLegitki
                    GROUP BY d.doctors_id;
                    ";
                    $result=mysqli_query($conn,$sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        
                        return $row['average_rating'];
                    } else {
                        return null; 
                    }
            }

            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                    if (isset($_POST['specjalizacja'])) {
                        $specjalizacja = $_POST['specjalizacja'];
                        if($specjalizacja !== ''){
                            if($specjalizacja==='wszystkie'){
                                if(isset($_POST['usluga'])&& $_POST['usluga']!==''){
                                    $uslugaName=$_POST['usluga'];
                                    foreach($array as $doctor){
                                        if (preg_match("/\b$uslugaName\b/i", $doctor->informacjeOUslugach())) {
                                            echo "<div class='doktor container'>
                                                    <img src='lekarz-poz.jpg' alt='lekarz'>
                                                    <h3 style='color: blue'>Lekarz " . $doctor->informacjeOSpecjalizacji() . "</h3>
                                                    <h3>" . "<a href='lekarz.php?id=".$doctor->numerLegitymacji()."'>".$doctor->pelneNazwisko()."</a>" . "</h3>
                                                    <h5>Średnia ocena: ".srednia_ocena($doctor->numerLegitymacji()). "</h5>
                                                </div>";
                                                $doctor->setOcena(srednia_ocena($doctor->numerLegitymacji()));
                                        }
                                    }
                                }else{
                                    foreach($array as $doctor){
                                        echo "<div class='doktor container'>
                                                    <img src='lekarz-poz.jpg' alt='lekarz'>
                                                    <h3 style='color: blue'>Lekarz " . $doctor->informacjeOSpecjalizacji() . "</h3>
                                                    <h3>" . "<a href='lekarz.php?id=".$doctor->numerLegitymacji()."'>".$doctor->pelneNazwisko()."</a>" . "</h3>
                                                    <h5>Średnia ocena: ".srednia_ocena($doctor->numerLegitymacji()). "</h5>
                                                </div>";
                                                $doctor->setOcena(srednia_ocena($doctor->numerLegitymacji()));
                                    }
                                }
                            }else{
                                foreach ($array as $doctor) {
                                    if ($doctor->informacjeOSpecjalizacji() == $specjalizacja) {
                                        echo "<div class='doktor container'>
                                                    <img src='lekarz-poz.jpg' alt='lekarz'>
                                                    <h3 style='color: blue'>Lekarz " . $doctor->informacjeOSpecjalizacji() . "</h3>
                                                    <h3>" . "<a href='lekarz.php?id=".$doctor->numerLegitymacji()."'>".$doctor->pelneNazwisko()."</a>" . "</h3>
                                                    <h5>Średnia ocena: ".srednia_ocena($doctor->numerLegitymacji()). "</h5>
                                                </div>";

                                                $doctor->setOcena(srednia_ocena($doctor->numerLegitymacji()));
                                    }       
                                }
                            }
                        }
                    }    
            }

            if ($_SERVER["REQUEST_METHOD"] === 'POST') {
                if (isset($_POST['doctor'])) {
                    $doctorName = $_POST['doctor'];
                    foreach ($array as $doctor) {
                        if ($doctor->pelneNazwisko() == $doctorName) {
                            echo "<div class='doktor container'>
                                                    <img src='lekarz-poz.jpg' alt='lekarz'>
                                                    <h3 style='color: blue'>Lekarz " . $doctor->informacjeOSpecjalizacji() . "</h3>
                                                    <h3>" . "<a href='lekarz.php?id=".$doctor->numerLegitymacji()."'>".$doctor->pelneNazwisko()."</a>" . "</h3>
                                                    <h5>Średnia ocena: ".srednia_ocena($doctor->numerLegitymacji()). "</h5>
                                                </div>";
                                    $doctor->setOcena(srednia_ocena($doctor->numerLegitymacji()));;
                            
                        }
                    }
                } else {
                    echo "Proszę wybrać specjalizację.";
                }
            }

            function sortowanieOceny($lekarze) {
                usort($lekarze, function($a, $b) {
                    $sredniaOcenaA = srednia_ocena($a->numerLegitymacji());
                    $sredniaOcenaB = srednia_ocena($b->numerLegitymacji());
                    return $sredniaOcenaB <=> $sredniaOcenaA; 
                });
                return $lekarze;
            }
            if($_SERVER["REQUEST_METHOD"]==="POST"){
                if(isset($_POST['sortowanie'])){
                    $metoda=$_POST['sortowanie'];
                    if($metoda === 'oceny'){
                        $posortowane=sortowanieOceny($array);
                        foreach ($posortowane as $doctor) {
                            echo "<div class='doktor container'>
                            <img src='lekarz-poz.jpg' alt='lekarz'>
                            <h3 style='color: blue'>Lekarz " . $doctor->informacjeOSpecjalizacji() . "</h3>
                            <h3>" . "<a href='lekarz.php?id=".$doctor->numerLegitymacji()."'>".$doctor->pelneNazwisko()."</a>" . "</h3>
                            <h5>Średnia ocena: ".srednia_ocena($doctor->numerLegitymacji()). "</h5>
                        </div>";

                        $doctor->setOcena(srednia_ocena($doctor->numerLegitymacji()));
                        }
                    }
                }
            }
?>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded",function(){
            const select1=document.getElementById("select1");
            const select2=document.getElementById("select2");


            const options={
                "wszystkie":[
                    {value:"EKG",text:"badanie EKG"},
                    {value:"konsultacja kardiologiczna",text:"konsultacja kardiologiczna"},
                    {value:"operacja wyrostka robaczkowego",text:"operacja wyrostka robaczkowego"},
                    {value:"wycięcie guza",text:"wycięcie guza"},
                    {value:"EEG",text:"badanie EEG"},
                    {value:"leczenie migreny",text:"leczenie migreny"},
                    {value:"badanie noworodka",text:"badanie noworodka"},
                    {value:"szczepienia",text:"szczepienia"},
                    {value:"leczenie trądziku",text:"leczenie trądziku"},
                    {value:"usuwanie zmian skórnych",text:"usuwanie zmian skórnych"}
                ],
                "kardiolog":[
                    {value:"EKG",text:"badanie EKG"},
                    {value:"konsultacja kardiologiczna",text:"konsultacja kardiologiczna"}
                ],
                "chirurg":[
                    {value:"operacja wyrostka robaczkowego",text:"operacja wyrostka robaczkowego"},
                    {value:"wycięcie guza",text:"wycięcie guza"}
                ],
                "neurolog":[
                    {value:"EEG",text:"badanie EEG"},
                    {value:"leczenie migreny",text:"leczenie migreny"}
                ],
                "pediatra":[
                    {value:"badanie noworodka",text:"badanie noworodka"},
                    {value:"szczepienia",text:"szczepienia"}
                ],
                "dermatolog":[
                    {value:"leczenie trądziku",text:"leczenie trądziku"},
                    {value:"usuwanie zmian skórnych",text:"usuwanie zmian skórnych"}
                ]
            };

            select1.addEventListener('change',function(){
                const selectedCategory=select1.value;

                while(select2.options.length>1){
                    select2.remove(1);
                }

                if(options[selectedCategory]){
                    options[selectedCategory].forEach(option =>{
                        const newOption=new Option(option.text,option.value);
                        select2.add(newOption);
                    });
                }

                select2.disabled=options[selectedCategory] ? false:true;
            });
        });
    </script>
</body>
</html>


