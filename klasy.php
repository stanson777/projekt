<?php
    abstract class Doktor {
        protected $imie;
        protected $nazwisko;
        protected $numer_legitymacji;
        protected $uslugi;
        protected $ocena;
    
        public function __construct($imie, $nazwisko, $numer_legitymacji, $uslugi) {
            $this->imie = $imie;
            $this->nazwisko = $nazwisko;
            $this->numer_legitymacji = $numer_legitymacji;
            $this->uslugi = $uslugi;
            
        }
    
        abstract public function informacjeOSpecjalizacji();
    
        
    
        public function setOcena() {
            
        }
        public function iloscOpinii(){
            return count($this->opinie);
        }
        public function pelneNazwisko() {
            return $this->imie . ' ' . $this->nazwisko;
        }
    
        public function numerLegitymacji() {
            return $this->numer_legitymacji;
        }
    
        public function informacjeOUslugach() {
            return 'Wykonuje usługi: ' . implode(", ", $this->uslugi);
        }
    }
    
    class Kardiolog extends Doktor {
        public $specjalizacja = "kardiolog";
        public function informacjeOSpecjalizacji() {
            return $this->specjalizacja;
        }
    }
    
    class Chirurg extends Doktor {
        public $specjalizacja = "chirurg";
        public function informacjeOSpecjalizacji() {
            return $this->specjalizacja;
        }
    }
    
    class Neurolog extends Doktor {
        public $specjalizacja = "neurolog";
        public function informacjeOSpecjalizacji() {
            return $this->specjalizacja;
        }
    }
    
    class Pediatra extends Doktor {
        public $specjalizacja = "pediatra";
        public function informacjeOSpecjalizacji() {
            return $this->specjalizacja;
        }
    }
    
    class Dermatolog extends Doktor {
        public $specjalizacja = "dermatolog";
        public function informacjeOSpecjalizacji() {
            return $this->specjalizacja;
        }
    }
    
    global $array;
    $array=[
        $doktor1 = new Kardiolog("Jan", "Kowalski", "123456", ["badanie EKG", "konsultacja kardiologiczna"], "jan.kowalski@klinika.pl"),
        $doktor2 = new Chirurg("Anna", "Nowak", "234567", ["operacja wyrostka robaczkowego", "wycięcie guza"], "anna.nowak@klinika.pl"),
        $doktor3 = new Neurolog("Piotr", "Wiśniewski", "345678", ["badanie EEG", "leczenie migreny"], "piotr.wisniewski@klinika.pl"),
        $doktor4 = new Pediatra("Katarzyna", "Kamińska", "456789", ["badanie noworodka", "szczepienia"], "katarzyna.kaminska@klinika.pl"),
        $doktor5 = new Dermatolog("Marek", "Lewandowski", "567890", ["leczenie trądziku", "usuwanie zmian skórnych"], "marek.lewandowski@klinika.pl"),
        $doktor6 = new Kardiolog("Zofia", "Dąbrowska", "678901", ["badanie EKG", "konsultacja kardiologiczna"], "zofia.dabrowska@klinika.pl"),
        $doktor7 = new Chirurg("Adam", "Zieliński", "789012", ["operacja przepukliny", "usunięcie polipa"], "adam.zielinski@klinika.pl"),
        $doktor8 = new Neurolog("Barbara", "Szymańska", "890123", ["badanie neurologiczne", "leczenie padaczki"], "barbara.szymanska@klinika.pl"),
        $doktor9 = new Pediatra("Tomasz", "Wójcik", "901234", ["badanie rozwoju dziecka", "szczepienia"], "tomasz.wojcik@klinika.pl"),
        $doktor10 = new Dermatolog("Magdalena", "Kowalczyk", "012345", ["badanie znamion", "leczenie łuszczycy"], "magdalena.kowalczyk@klinika.pl"),
        $doktor11 = new Kardiolog("Marcin", "Woźniak", "123450", ["badanie serca", "leczenie chorób układu krążenia"], "marcin.wozniak@klinika.pl"),
        $doktor12 = new Chirurg("Ewa", "Lis", "234561", ["operacja złamania", "rekonstrukcja ścięgna"], "ewa.lis@klinika.pl"),
        $doktor13 = new Neurolog("Paweł", "Krawczyk", "345672", ["leczenie rwy kulszowej", "badanie EMG"], "pawel.krawczyk@klinika.pl"),
        $doktor14 = new Pediatra("Karolina", "Zając", "456783", ["badanie noworodka", "konsultacja pediatryczna"], "karolina.zajac@klinika.pl"),
        $doktor15 = new Dermatolog("Robert", "Piotrowski", "567894", ["usuwanie zmian skórnych", "badanie histopatologiczne"], "robert.piotrowski@klinika.pl"),
        $doktor16 = new Kardiolog("Alicja", "Witkowska", "678905", ["badanie echokardiograficzne", "leczenie nadciśnienia"], "alicja.witkowska@klinika.pl"),
        $doktor17 = new Chirurg("Wojciech", "Kaczmarek", "789016", ["operacja przepukliny", "leczenie żylaków"], "wojciech.kaczmarek@klinika.pl"),
        $doktor18 = new Neurolog("Izabela", "Kozłowska", "890127", ["leczenie migreny", "badanie potencjałów wywołanych"], "izabela.kozlowska@klinika.pl"),
        $doktor19 = new Pediatra("Krzysztof", "Adamczyk", "901238", ["szczepienia", "badanie neurologiczne"], "krzysztof.adamczyk@klinika.pl"),
        $doktor20 = new Dermatolog("Agnieszka", "Pawlak", "012349", ["leczenie trądziku", "usuwanie blizn"], "agnieszka.pawlak@klinika.pl"),
        $doktor21 = new Kardiolog("Łukasz", "Jaworski", "123450", ["badanie wysiłkowe", "leczenie arytmii"], "lukasz.jaworski@klinika.pl"),
        $doktor22 = new Chirurg("Monika", "Michalak", "234561", ["operacja przewodu pokarmowego", "usunięcie torbieli"], "monika.michalak@klinika.pl"),
        $doktor23 = new Neurolog("Dariusz", "Nowicki", "345672", ["leczenie padaczki", "badanie neurologiczne"], "dariusz.nowicki@klinika.pl"),
        $doktor24 = new Pediatra("Jolanta", "Kaczorowska", "456783", ["badanie noworodka", "szczepienia"], "jolanta.kaczorowska@klinika.pl"),
        $doktor25 = new Dermatolog("Mariusz", "Grabowski", "567894", ["badanie skóry", "leczenie łojototokowego zapalenia skóry"], "mariusz.grabowski@klinika.pl"),
        $doktor26 = new Kardiolog("Justyna", "Pietrzak", "678905", ["badanie Holtera", "leczenie nadciśnienia"], "justyna.pietrzak@klinika.pl"),
        $doktor27 = new Chirurg("Sebastian", "Zawadzki", "789016", ["operacja żylaków", "leczenie przepukliny"], "sebastian.zawadzki@klinika.pl"),
        $doktor28 = new Neurolog("Patrycja", "Kowal", "890127", ["badanie EEG", "leczenie stwardnienia rozsianego"], "patrycja.kowal@klinika.pl"),
        $doktor29 = new Pediatra("Rafał", "Wrona", "901238", ["badanie noworodka", "szczepienia"], "rafal.wrona@klinika.pl"),
        $doktor30 = new Dermatolog("Anita", "Walczak", "012349", ["usuwanie znamion", "badanie histopatologiczne"], "anita.walczak@klinika.pl"),
        $doktor31 = new Kardiolog("Dominik", "Sikora", "123450", ["badanie EKG", "leczenie migotania przedsionków"], "dominik.sikora@klinika.pl"),
        $doktor32 = new Chirurg("Anna", "Lipińska", "234561", ["operacja przepukliny", "leczenie polipów"], "anna.lipinska@klinika.pl"),
        $doktor33 = new Neurolog("Piotr", "Duda", "345672", ["leczenie bólu głowy", "badanie EMG"], "piotr.duda@klinika.pl"),
        $doktor34 = new Pediatra("Natalia", "Kurek", "456783", ["szczepienia", "badanie noworodka"], "natalia.kurek@klinika.pl")
    
    ];
    

    
    require_once "database.php";

    $json_data = file_get_contents('passwords.json');
    $passwords = json_decode($json_data, true);

    if (count($array) === count($passwords)) {
        $sql = "INSERT INTO doctors (doctors_id, password) VALUES (?, ?) ON DUPLICATE KEY UPDATE password = VALUES(password)";
        $stmt = mysqli_stmt_init($conn);

        foreach ($array as $index => $doktor) {
            $doctors_id = $doktor->numerLegitymacji();
            $password = $passwords[$index];
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt, "ss", $doctors_id, $passwordHash);
                mysqli_stmt_execute($stmt);
            } else {
                die("Stało się coś nieoczekiwanego");
            }
        }
    } else {
        echo "Błąd: liczba lekarzy nie odpowiada liczbie haseł.";
    }
?>