<?php

    session_start();

    if((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
    {
        header('Location:index.php');
        exit();
    }


    require_once "connect_database.php";

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    if($polaczenie->connect_errno!=0)
    {
        echo "Error: ".$polaczenie->connect_errno;
    }

    else
    {
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $login = htmlentities($haslo, ENT_QUOTES, "UTF-8");

        if($rezultat= @$polaczenie->query(sprintf("SELECT * FROM `dane` WHERE `USER`='%s' AND `PASSWORD`='%s'",
            mysqli_real_escape_string($polaczenie, $login),
            mysqli_real_escape_string($polaczenie, $haslo))))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            {

                    $_SESSION['zalogowany'] = true;
                    $wiersz = $rezultat->fetch_assoc();

                    $_SESSION['ID'] = $wiersz['ID'];
                    $_SESSION['USER'] = $wiersz['USER'];
                    $_SESSION['E-MAIL'] = $wiersz['E-MAIL'];

                    unset($_SESSION['blad']);



                    $rezultat->close(); //usuwanie rezultatów zapytania
                    header('Location: main_site.php');
            }

            else
            {
                $_SESSION['blad']='<span style = "color:orangered"><p>Nieprawidłowy login lub hasło</span>';
                header('Location: index.php');
            }
        }

        $polaczenie->close();
    }
?>









