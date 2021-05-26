<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    if (!empty($_POST["message"])) {
        $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
        mysqli_query ($conn, "SET NAMES 'utf8'");
        $date = date("Y-m-d");
        $message = mysqli_real_escape_string($conn, $_POST["message"]);
        $image = mysqli_real_escape_string($conn, $_POST["image"]);
        $query = "INSERT INTO Annunci(Autore, Data, Messaggio, Immagine) VALUES ($userid, '$date', '$message', '$image')";
        mysqli_query($conn, $query) or die(mysqli_error($conn));
        mysqli_close($conn);
        header('Location: home.php');
        exit;
    }
?>