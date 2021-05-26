<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: application/json');
    if (empty($_GET["q"])) {
        exit;
    }
    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    $prodotto = $_GET["q"];
    $query = "CALL addInventory('$prodotto')";
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    mysqli_close($conn);
?>