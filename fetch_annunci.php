<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: application/json');
    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    mysqli_query ($conn, "SET NAMES 'utf8'");
    $query = "SELECT A.Data, D.Nome, D.Cognome, A.Messaggio, A.Immagine FROM Annunci A JOIN Dipendenti D ON D.ID = A.Autore";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resArray = array();

    while ($row = mysqli_fetch_assoc($res)) {
        $resArray[] = $row;
    }
    echo json_encode($resArray, JSON_UNESCAPED_UNICODE);

    mysqli_free_result($res);
    mysqli_close($conn);
?>