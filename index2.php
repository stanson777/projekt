<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action='' method='post'>
        <label for='plik'>Wpisz jak chcesz nazwac plik txt :</label>
        <input type='text' name='plik'>

        <button type='submit'>Utwórz plik</button>
    </form>
    <?php
        session_start();

        if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['plik'])){
            $nameOfFile=$_POST['plik'];
            $fileName=$nameOfFile.".txt";

            if(!is_file($fileName)){
                fopen($fileName,"w");
                $_SESSION['fileName']=$fileName;
                header("Location: message.php?message=Plik $fileName został utworzony");
                exit();
            }else{
                $_SESSION['fileName']=$fileName;
                header("Location: message.php?message=Plik $fileName już istnieje");
                exit();
            }

        }


    ?>
</body>
</html>