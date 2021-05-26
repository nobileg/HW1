<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }

    $conn = mysqli_connect($database['host'], $database['user'], $database['password'], $database['name']) or die(mysqli_error($conn));
    $query = "SELECT D.Username, D.Nome, D.Cognome, T.Nome AS Team FROM Dipendenti D JOIN Teams T ON T.ID = D.Team WHERE D.ID = $userid";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $user_info = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
?>

<html>
    <head>
        <title>Casa Farmaceutica &bullet; Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='style/home.css'>
        <script src="script/home.js" defer="true"></script>
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
                <div id="menu">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </header>

        <div id="content">
            <nav>
                <a class="button active">Home</a>
                <a href="dipendenti.php" class="button">Dipendenti</a>
                <a href="teams.php" class="button">Teams</a>
                <a href="prodotti.php" class="button">Prodotti</a>
                <a href="logout.php" class="button">Logout</a>
            </nav>

            <section>
                <div id="section_header">
                    <h1>Home</h1>
                </div>
                <div class="spacer"></div>
                <div id="section_content">
                    <div id="sidebar">
                        <h2>Bentornato,</h2><br>
                        <h2><?php echo $user_info['Nome'] . " " . $user_info['Cognome']?></h2>
                        <p><em>Team:</em> <?php echo $user_info['Team']?></p>
                    </div>
                    <div id="board">
                        <h1>Annunci</h1>
                        <dl></dl>
                        <div id="search_container" class="hidden">
                            <form name='search_image'>
                                <input name='query' type='text' placeholder='Cerca'>
                                <input type='submit' value='Cerca immagine'>
                            </form>
                            <div id="result_container"></div>
                        </div>
                        <form name='announcement' method='post' action='post_announcement.php'>
                            <textarea name='message' placeholder="Pubblica un annuncio..."></textarea>
                            <div>
                                <a id="a_search">Cerca un'immagine da aggiungere</a>
                            </div>
                            <input id='message_image' name='image' type='hidden'>
                            <input type='submit' value="Pubblica">
                        </form>
                    </div>
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
<?php mysqli_close($conn); ?>
