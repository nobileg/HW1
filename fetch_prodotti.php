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
    $query = "SELECT P.Codice, P.Nome, P.Immagine, P. Descrizione, Count(*) AS Lotti FROM Prodotti P JOIN Lotti L ON P.Codice = L.Prodotto GROUP BY P.Nome";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $resArray = array();

    while ($row = mysqli_fetch_assoc($res)) {
        $query = "SELECT T.Nome FROM Teams T JOIN Teams_Produzione TP ON T.ID = TP.ID WHERE TP.Prodotto = " . $row['Codice'];
        $res2 = mysqli_query($conn, $query) or die(mysqli_error($conn));
        $resArray2 = array();

        while ($row2 = mysqli_fetch_assoc($res2)) {
            $resArray2[] = $row2['Nome'];
        }

        $row['Teams'] = $resArray2;
        $resArray[] = $row;
    }
    echo json_encode($resArray, JSON_UNESCAPED_UNICODE);

    mysqli_free_result($res);
    mysqli_close($conn);
?>