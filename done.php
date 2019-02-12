<?php
session_start();

    if(!isset($_SESSION['udanarejestracja']))
        {
            header('Location: index.php');
            exit();
        }

    else
        {
            unset($_SESSION['udanarejestracja']);
        }
    ?>


<!DOCTYPE HTML>
<html lang = "pl">
<head>
    <meta charset = "utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Smakowicie</title>
</head>

<body>

Zaloguj się na swoje konto<br /><br />

<a href="index.php">Zaloguj się na swoje konto</a>
<br/><br/>

</body>
</html>




















