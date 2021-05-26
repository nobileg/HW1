<?php
    include 'check_session.php';
    if (checkSession()) {
        header('Location: home.php');
        exit;
    }

    if (!empty($_POST["username"]) and !empty($_POST["password"]) )
    {
        $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $query = "SELECT ID, Username, Password FROM Dipendenti WHERE Username = '$username'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (password_verify($password, $row['Password'])) {
                $_SESSION["username"] = $row['Username'];
                $_SESSION["user_id"] = $row['ID'];
                header("Location: home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
            else $error = "Password invalida.";
        }
        else $error = "Username invalido.";
    }
    else if (empty($_POST["username"]) xor empty($_POST["password"])) {
        $error = "Inserisci username e password.";
    }

?>

<html>
    <head>
        <title>Casa Farmaceutica &bullet; Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='style/login.css'>

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Exo:wght@300&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    </head>
    <body>
    <header>
            <div id="header_container">
                <img id="header_logo" src="img/logo.png">
                <h1>Casa Farmaceutica</h1>
                <h2>Pannello Aziendale</h2>
            </div>
        </header>

        <div id="content">
            <section>
                <div id="section_header">
                    <h1>Login</h1>
                </div>
                <div class="spacer"></div>
                <div class="section_content">
                    <?php
                        if (isset($error)) {
                            echo "<div class='error'>$error</div>";
                        }
                    ?>
                    <form name='login' method='post'>
                        <input type='text' name='username' placeholder="Username">
                        <input type='password' name='password' placeholder="Password">
                        <input type='submit' value="Login">
                    </form>
                    <div id="form_footer">Non hai le credenziali? <a href="signup.php">Registrati</a>
                </div>
            </section>
        </div>

        <footer>
            <div id="footer_container">
                <div class="footer_content f_left">
                    <img id="footer_logo" src="img/logo.png">
                </div>

                <div class="footer_content f_right">
                    <div id="credits">
                        Università degli Studi di Catania - Piazza Università, 2 - 95131 Catania<br>
                        Dipartimento di Ingegneria Elettrica Elettronica e Informatica<br>
                        Corso di Database and Web Programming<br>
                        Giambattista Nobile, O46002144
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>