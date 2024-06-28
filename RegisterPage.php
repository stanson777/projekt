<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="styleshee" href="RejestrStyle.css">
    <title>Document</title>
    <style>
        body{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            margin:0;
        }
        .container{
            max-width:600px;
            box-shadow:rgba(100,100,111,0.2) 0px 7px 29px 0px;
            border-radius:5px;
            border-style:none;
            display:flex;
            margin:0 auto;
            flex-direction:column;
            align-items:center;
        }
        .container form{
            display:flex;
            flex-direction:column;
            align-items:center;
        }

        form input[type="text"],input[type='password'],input[type='email']{
            width:100%;
            height:50px;
            margin:10px;
            border-style:none;

            background-color:rgba(221, 218, 218, 0.1);
        }
        #btn{

            
            border-radius:15px;
            padding:10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            if(isset($_POST["submit"])){
                $imie=$_POST['name'];
                $nazwisko=$_POST['lastname'];
                $email=$_POST['email'];
                $password=$_POST['password'];
                $confirmpassword=$_POST['confirmpassword'];
                $errors=array();


                $passwordHash=password_hash($password,PASSWORD_DEFAULT);

                if(empty($imie) OR empty($email) OR empty($password) OR empty($confirmpassword) OR empty($nazwisko)){
                    array_push($errors, "Wszystkie pola sa wymagene");
                }
                if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    array_push($errors,"Email nie jest poprawny");

                }
                if(strlen($password)<8 ){
                    array_push($errors,"Haslo musi sie skladac przynajmniej z 8 cyfer");
                }

                if($password!==$confirmpassword){
                    array_push($errors,"Hasła nie są takie same");

                }
                require_once "database.php";
                $sql="SELECT * FROM users WHERE email='$email'";
                $result=mysqli_query($conn,$sql);
                $rowCount=mysqli_num_rows($result);
                if($rowCount>0){
                    array_push($errors,"Email already exists");
                }
                if(count($errors)>0){
                    foreach($errors as $error){
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }else{
                    
                    $sql="INSERT INTO users (name, last_name, email, password) VALUES (?,?,?,?)";
                    $stmt=mysqli_stmt_init($conn);
                    $prepareStmt=mysqli_stmt_prepare($stmt,$sql);
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt,"ssss",$imie,$nazwisko,$email,$passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>Rejestracja przebiegla pomyslnie</div>";
                        header("Location: loginPage.php");
                        exit();
                    }else{
                        die("Stało sie cos nieoczekiwanego");
                    }
                }
            }

        ?>
        <h1>Rejestracja</h1>
        <form action="RegisterPage.php" method='post'>
            <div class='form-group'>
                <input type='text' class='form-control' name='name' placeholder="Imie: ">
            </div>

            <div class='form-group'>
                <input type='text' class='form-control' name='lastname' placeholder="Nazwisko: ">
            </div>

            <div class='form-group'>
                <input type='email' class='form-control' name='email' placeholder="Email: ">
            </div>

            <div class='form-group'>
                <input type='password' class='form-control' name='password' placeholder="Haslo: ">
            </div>

            <div class='form-group'>
                <input type='password' class='form-control' name='confirmpassword' placeholder="Powtórz haslo: ">
            </div>

            <div class='form-group'>
                <input type='submit' class="btn btn-primary" value="Register" name='submit'>
            </div>
        </form>
    </div>
</body>
</html>