<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: application/json');
    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    $query = "SELECT D.Nome, D.Cognome, D.Data_Assunzione, D.Anni_Servizio, T.Nome AS Team FROM Dipendenti D JOIN Teams T ON D.Team = T.ID ORDER BY D.Anni_Servizio DESC";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resArray = array();

    while ($row = mysqli_fetch_assoc($res)) {
        $resArray[] = $row;
    }
    echo json_encode($resArray);

    mysqli_free_result($res);
    mysqli_close($conn);
?>