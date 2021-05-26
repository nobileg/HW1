<?php
    include 'check_session.php';
    if (checkSession() or empty($_GET["q"])) {
        header('Location: home.php');
        exit;
    }

    header('Content-Type: application/json');
    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    $username = mysqli_real_escape_string($conn, $_GET["q"]);
    $query = "SELECT Username FROM Dipendenti WHERE Username = '$username'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if(mysqli_num_rows($res) > 0) {
        echo json_encode(array('user_exists' => true));
    }
    else {
        echo json_encode(array('user_exists' => false));
    }

    mysqli_free_result($res);
    mysqli_close($conn);
?>