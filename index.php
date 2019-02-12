<?php
    session_start();

    if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true))
    {
        header('Location: main_site.php');
        exit();
    }
?>


<!DOCTYPE HTML>
<html lang = "pl">
<head>
    <meta charset = "utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Smakowicie</title>
    <link rel = "stylesheet" type = "text/css" href = "style.css">
</head>

<body>

    <div class="login-box">


    Witaj na stronie<br /><br />


    <form action = "Zaloguj.php" method = "post">

        Login: <br /> <input type = "text" name = "login"/> <br/>
        Haslo: <br /> <input type = "password" name = "haslo"/> <br /><br />
        <input type = "submit" value = "Zaloguj się" />

    </form>

    <?php
    if(isset($_SESSION['blad']))
    {
        echo $_SESSION['blad'];
    }

    ?>

    <br/><br/>
    <a href ="rejestracja.php">Nie masz jeszcze konta? Zarejestruj się za darmo!</a>

    </div>
</body>
</html>




















