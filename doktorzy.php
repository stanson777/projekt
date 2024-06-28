<?php
    class Doktor{
        public $imie;
        public $nazwisko;

        public $specjalizacja;

        public $uslugi;




        public function __construct($imie,$nazwisko,$specjalizacja,$uslugi){
            $this->imie=$imie;
            $this->nazwisko=$nazwisko;
            $this->specjalizacja=$specjalizacja;
            $this->usługi=$uslugi;
        }

        public function pelneNazwisko() {
            return $this->imie . ' ' . $this->nazwisko;
        }
    
        
        public function informacjeOSpecjalizacji() {
            return 'Specjalizacja: ' . $this->specjalizacja;
        }
    
        
        public function numerLegitymacji() {
            return 'Numer legitymacji: ' . $this->numer_legitymacji;
        }
    
        
        public function informacjeOUslugach() {
            return 'Wykonuje usługi: ' . implode(", ", $this->uslugi);
        }
    }



?>