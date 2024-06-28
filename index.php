<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekcja 1</title>
</head>
<body>
    <div class='container'>
        <form action='' method='post'>
            <label for='text1'>Wpisz tekst: </label>
            <input type='text' name='text1'>
            <input type='submit' value='Odwróć'>

        </form>
        <?php
            session_start();
            function odwroc_tekst($tekst){
                $reversed="";
                for($i=strlen($tekst)-1;$i>=0;$i--){
                    $reversed.=$tekst[$i];
                }

                return $reversed;
            }
            if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST["text1"])){
                $tekst=$_POST['text1'];
                $_SESSION['reversed_word']=odwroc_tekst($tekst);

                header("Location: ".$_SERVER["REQUEST_URI"]);
                exit;
                
            }


            if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['delete_var'])){
                unset($_SESSION['reversed_word']);
            }


            if(isset($_SESSION['reversed_word'])){
                echo "<h1>".$_SESSION['reversed_word']."</h1>";

                echo "<form action='' method='post'><input type='hidden' name='delete_var'><input type='submit' value='Usun wartosc'></form>" ;
            }
        ?>
    </div>
</body>
</html>