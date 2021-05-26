<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: application/json');
    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    $query = 
        "SELECT 1 AS Tipo, T.Nome, D.Nome AS Leader_Nome, D.Cognome AS Leader_Cognome, S.Indirizzo AS Sede_Indirizzo, S.Citta AS Sede_Citta, R.Nome AS Assegnazione 
        FROM Teams T JOIN Teams_Ricerca TR ON T.ID = TR.ID JOIN Dipendenti D ON T.Leader = D.ID JOIN Sedi S ON T.Sede = S.ID JOIN Ricerche R ON TR.Ricerca = R.Codice
        UNION
        SELECT 2 AS Tipo, T.Nome, D.Nome AS Leader_Nome, D.Cognome AS Leader_Cognome, S.Indirizzo AS Sede_Indirizzo, S.Citta AS Sede_Citta, P.Nome AS Assegnazione
        FROM Teams T JOIN Teams_Produzione TP ON T.ID = TP.ID JOIN Dipendenti D ON T.Leader = D.ID JOIN Sedi S ON T.Sede = S.ID JOIN Prodotti P ON TP.Prodotto = P.Codice";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resArray = array();

    while ($row = mysqli_fetch_assoc($res)) {
        $resArray[] = $row;
    }
    echo json_encode($resArray);

    mysqli_free_result($res);
    mysqli_close($conn);
?>