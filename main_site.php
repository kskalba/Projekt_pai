<?php

    session_start();

    if(!isset($_SESSION['zalogowany']))
    {
        header('Location:index.php');
        exit();
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

<?php

echo"<p>Witaj ".$_SESSION['USER'].' [<a href = "logout.php">Wyloguj </a>]</p>';
echo"<p>E-mail: ".$_SESSION['E-MAIL'];

?>

</body>
</html>
