<?php
session_start();
if(isset($_SESSION["user"])){
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'test.php';
    if(isset($redirect) && $redirect != 'registerPage.php'){
        header("Location: " . $redirect);
    } else {
        header("Location: accountPage.php");
    }
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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

        input{
            margin:10px;
        }

        #btn{

            width:50%;
            border-radius:15px;
            padding:10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <?php
            if(isset($_POST['login'])){
                $email = $_POST['email'];
                $password = $_POST['password'];
                $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'test.php'; 
            
                require_once "database.php";
            
                $sql = "SELECT * FROM users WHERE email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            
                if($user){
                    if(password_verify($password, $user['password'])){
                        session_start();
                        $_SESSION["user"] = $user['id'];
                        header("Location: " . $redirect);
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Hasło nie pasuje</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email nie pasuje</div>";
                }
            }


        ?>
        <h1>Login</h1>
        <form action='loginPage.php' method='post'>
            <input type="hidden" name="redirect" value="<?php echo isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : ''; ?>">
            <div class="form-group">
                <input type='email' placeholder="Wpisz email:" name='email' class='form-control'>
            </div>
            <div class="form-group">
                <input type='password' placeholder="Wpisz haslo:" name='password' class='form-control'>
            </div>
            
            <div class="form-btn">
                <input type='submit' value='Login' name='login' class='form-control'>
            </div>
            <p href='Nie masz konta '><a href='RegisterPage.php'>Załóż konto</a></p>
            
        </form>
    </div>
    
</body>
</html>