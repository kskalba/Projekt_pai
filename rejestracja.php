<?php
    session_start();

    if(isset($_POST['nick']))
    {
        $correct = true;

        //sprawdzanie poprawności nicka
        $nick = $_POST['nick'];
        if((strlen($nick)<7) || (strlen($nick)>20))
        {
            $correct = false;
            $_SESSION['error_nick'] = "Nick powinien zawierać od 7 do 20 znaków";
        }

        if(ctype_alnum($nick) == false)
        {
            $correct = false;
            $_SESSION['error_nick'] = "Nick nie może zawierać polskich znaków i znaków specjalnych";
        }

        //sprawdzanie poprawności maila
        $email = $_POST['E-mail'];
        $safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if((filter_var($safe_email, FILTER_VALIDATE_EMAIL) == false) || ($safe_email != $email))
        {
            $correct = false;
            $_SESSION['error_email'] = "Podaj poprawny adres email";
        }

        //sprawdzanie poprawności hasła
        $haslo1 = $_POST['password1'];
        $haslo2 = $_POST['password2'];

        if ((strlen($haslo1)<8) || (strlen($haslo2)>20))
        {
            $correct = false;
            $_SESSION['error_haslo'] = "Hasło powinno zawierać od 8 do 20 znaków";
        }

        if($haslo1 != $haslo2)
        {
            $correct = false;
            $_SESSION['error_haslo'] = "Podane hasła nie są identyczne";
        }

        //akceptacja checkboxa "regulamin"
        if(isset($_POST['regulamin']))
        {
            $correct = false;
            $_SESSION['error_regulamin'] = "Niezaakceptowano regulaminu";
        }

        //Sprawdzenie recaptchy
        $secret = "6LedN5AUAAAAAGYI3yvJBXbZn3GtngWPKQYaRCIy";

        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST
            ['g-recaptcha-response']);

        $response = json_decode($check); //json obsługuje rezultaty recaptchy

        if($response->success == false)
        {
            $correct = false;
            $_SESSION['error_recaptcha'] = "Brak zatwierdzenia recaptcha";
        }

        //Błędzy w połączeniu z bazą
        require_once "connect_database.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            if($polaczenie->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno());
            }

            else
            {
                //taki sam email
                $rezultat = $polaczenie->query("SELECT `ID` FROM `dane` WHERE `E-MAIL` = '$email'");
                if(!$rezultat) throw new Exception($polaczenie->error);

                $maile = $rezultat->num_rows;

                if($maile>0)
                {
                    $correct = false;
                    $_SESSION['error_email'] = "Podana nazwa e-mail jest już zajęta i nie może zostać ponownie użyta";
                }


                //taki sam login
                $rezultat = $polaczenie->query("SELECT `ID` FROM `dane` WHERE `USER` = '$nick'");
                if(!$rezultat) throw new Exception($polaczenie->error);

                $loginy = $rezultat->num_rows;

                if($loginy>0)
                {
                    $correct = false;
                    $_SESSION['error_nick'] = "Podany nickname jest już zajęty i nie może zostać ponownie użyty";
                }

                if($correct == true)
                {
                    //dodanie użytkownika do bazy
                    if($polaczenie->query("INSERT INTO `dane` VALUES (NULL, '$nick', '$haslo1', '$email', 100, 100, 100, 14)"))
                    {
                        $_SESSION['udanarejestracja'] = true;
                        header('Location: done.php');
                    }
                    else
                    {
                        throw new Exception($polaczenie->error);
                    }
                }

                $polaczenie->close();
            }
        }
        catch(Exception $e)
        {
            echo '<span style = "color: orangered;">Błąd połączenia z serwerem<span/>';
        }
    }

?>

<!DOCTYPE HTML>
<html lang = "pl">
<head>
    <meta charset = "utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Smakowicie - rejestracja </title>
    <script src = 'https://www.google.com/recaptcha/api.js'></script>

    <style>
        .error
        {
            color:orangered;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>

    <link rel = "stylesheet" type = "text/css" href = "style.css">

</head>

<body>
    <div class="login-box">
    <form method = "post">

        Nickname : <input type  = "tekst" name = "nick" /><br/><br/>
        <?php
        if(isset($_SESSION['error_nick']))
        {
            echo '<div class = "error">'.$_SESSION['error_nick'].'</div>';
            unset($_SESSION['error_nick']);
        }
        ?>


        E-mail :  <input type  = "tekst" name = "E-mail" /><br/><br/>
        <?php
        if(isset($_SESSION['error_email']))
        {
            echo '<div class = "error">'.$_SESSION['error_email'].'</div>';
            unset($_SESSION['error_email']);
        }
        ?>


        Hasło :  <input type  = "password" name = "password1" /><br/><br/>
        <?php
        if(isset($_SESSION['error_haslo']))
        {
            echo '<div class = "error">'.$_SESSION['error_haslo'].'</div>';
            unset($_SESSION['error_haslo']);
        }
        ?>

        Powtórz hasło : <input type  = "password" name = "password2" /><br/><br/>

        <label>
            <input type = "checkbox" name = "Regulamin"/> Akceptuję regulamin
        </br>
        </label>

        <?php
        if(isset($_SESSION['error_regulamin']))
        {
            echo '<div class = "error">'.$_SESSION['error_regulamin'].'</div>';
            unset($_SESSION['error_regulamin']);
        }
        ?>

        <div class = "g-recaptcha" data-sitekey = "6LedN5AUAAAAAMtJiEkdTpHJ_WANomEovy0Hv1tm"></div>

        <?php
        if(isset($_SESSION['error_recaptcha']))
        {
            echo '<div class = "error">'.$_SESSION['error_recaptcha'].'</div>';
            unset($_SESSION['error_recaptcha']);
        }
        ?>

        </br>

        <input type = "submit" value = "Zarejestruj się"/>

    </form>
    </div>

</body>
</html>




















