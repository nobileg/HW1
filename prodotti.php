<?php
    include 'check_session.php';
    $userid = checkSession();
    if (!$userid) {
        header('Location: login.php');
        exit;
    }
?>

<html>
    <head>
        <title>Casa Farmaceutica &bullet; Prodotti</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' href='style/home.css'>
        <script src="script/prodotti.js" defer="true"></script>
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
                <a href="home.php" class="button">Home</a>
                <a href="dipendenti.php" class="button">Dipendenti</a>
                <a href="teams.php" class="button">Teams</a>
                <a class="button active">Prodotti</a>
                <a href="logout.php" class="button">Logout</a>
            </nav>

            <section>
                <div id="section_header">
                    <h1>Prodotti</h1>
                </div>
                <div class="spacer"></div>
                <div id="section_content">
                    <div id="products"></div>
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
