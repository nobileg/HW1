<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    header('Content-Type: application/json');
    if (empty($_GET["q"])) {
        echo json_encode(array());
        exit;
    }
    

    $client_id = "Bh65ZmEZzqUK6G6u2HU-6tmLXrj1X1Xuhxtp0yOqNbQ";
    $query = urlencode($_GET["q"]);

    $url = "https://api.unsplash.com/search/photos?query=" . $query . "&orientation=landscape&client_id=" . $client_id;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($result, true);
    $newJson = array();
    for ($i = 0; $i < count($json['results']); $i++) {
        $newJson[] = $json['results'][$i]['urls']['small'];
    }
    echo json_encode($newJson);
?>