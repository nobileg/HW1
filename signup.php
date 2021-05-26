<?php
    include 'check_session.php';
    if (checkSession()) {
        header('Location: home.php');
        exit;
    }

    $error = "";
    if (!empty($_POST["username"]) and !empty($_POST["email"]) and 
        !empty($_POST["password"]) and !empty($_POST["confirm_password"]) and
        !empty($_POST["firstname"]) and !empty($_POST["lastname"]) and
        !empty($_POST["date"]) and !empty($_POST["team"])) 
    {
        $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
        
        // Validazione username
        if (strlen($_POST["username"]) <= 4) {
            $error .= "<div>L'username deve contenere almeno 5 caratteri.</div>";
        }
        else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $query = "SELECT Username FROM Dipendenti WHERE Username = '$username'";
            $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
            if (mysqli_num_rows($res) > 0) {
                $error .= "<div>L'username è già in uso.</div>";
            }
        }

        // Validazione password
        if (!preg_match("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^", $_POST["password"])) {
            $error .= "<div>La password deve contenere almeno 8 caratteri, di cui almeno una lettera e un numero.</div>";
        }
        else if (strcmp($_POST["confirm_password"], $_POST["password"]) != 0) {
            $error .= "<div>Le due password non coincidono.</div>";
        }

        // Registrazione
        if (empty($error)) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT);
            $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $team = $_POST["team"];

            $query = "INSERT INTO Dipendenti(Username, Password, Email, Cognome, Nome, Data_Assunzione, Team) VALUES ('$username', '$password', '$email', '$lastname', '$firstname', '$date', $team)";
            if (mysqli_query($conn, $query)) {
                $_SESSION["username"] = $username;
                $_SESSION["user_id"] = mysqli_insert_id($conn);
                header("Location: home.php");
                mysqli_free_result($res);
                mysqli_close($conn);
                exit;
            }
            else {
                $error .= "<div>Errore di connessione al database. Riprova più tardi.</div>";
            }
        }
    }
    else if (!empty($_POST["username"])) {
        $error = "<div>Compila tutti i campi.</div>";
    }

?>

<html>
    <head>
        <title>Casa Farmaceutica &bullet; Registrazione</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='style/login.css'>
        <script src="script/signup.js" defer="true"></script>
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
                    <h1>Registrazione</h1>
                </div>
                <div class="spacer"></div>
                <div class="section_content">
                    <div id='client_errors' class='error hidden'></div>
                    <?php
                        if (!empty($error)) {
                            echo "<div class='error'>$error</div>";
                        }
                    ?>
                    <form name='signup' method='post'>
                        <div>
                            <input type='text' name='username' placeholder="Username" <?php if(isset($_POST["username"])){ echo "value=".$_POST["username"];} ?>>
                            <input type='email' name='email' placeholder="Email" <?php if(isset($_POST["email"])){ echo "value=".$_POST["email"];} ?>>
                        </div>
                        <div>
                            <input type='password' name='password' placeholder="Password">
                            <input type='password' name='confirm_password' placeholder="Conferma Password">
                        </div>
                        <div>
                            <input type='text' name='firstname' placeholder="Nome" <?php if(isset($_POST["firstname"])){ echo "value=".$_POST["firstname"];} ?>>
                            <input type='text' name='lastname' placeholder="Cognome" <?php if(isset($_POST["lastname"])){ echo "value=".$_POST["lastname"];} ?>>
                        </div>
                        <div>
                            <input type='text' name='date' placeholder="Data di assunzione" onfocus="(this.type='date')" onblur="(this.type='text')" <?php if(isset($_POST["date"])){ echo "value=".$_POST["date"];} ?>>
                            <input name='team' placeholder="Team" list="teams" <?php if(isset($_POST["team"])){ echo "value=".$_POST["team"];} ?>>
                            <datalist id="teams">
                                <?php
                                    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
                                    $query = "SELECT ID, Nome FROM Teams";
                                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='$row[ID]' label='$row[Nome]'>";
                                    }
                                    mysqli_free_result($res);
                                    mysqli_close($conn);
                                ?>
                            </datalist>
                        </div>
                            <input type='submit' value="Registrati">
                    </form>
                    <div id="form_footer">Hai già un account? <a href="login.php">Accedi</a>
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