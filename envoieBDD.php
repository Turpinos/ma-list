<?php
session_start();
require_once('processPhp/initBDD.php');
require_once('processPhp/validEnvoieBDD.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/liste.png">
    <title>Ma liste</title>
</head>
<body>
    <main>
        <?php if($connected): ?>
            <?php if($validInput): ?>
                <script>
                    localStorage.removeItem(localStorage.getItem('infoConnection'));
                    localStorage.removeItem(`${localStorage.getItem('infoConnection')}_part`);
                </script>
                <div id="validInput">
                    <h1>C'est tout bon!</h1>
                    <p>La liste a bien été sauvegardée en ligne !</p>
                    <p>Vous allez être redirigé(e) d'ici quelques secondes.</p>
                </div>
            <?php else: ?>
                <div id="noValidInput">
                    <h1>Il manque des informations</h1>
                    <p>Vous tentez d'accéder à cette page sans avoir sauvegardé ou avec une erreur dans votre liste.</p>
                    <p>Vous allez être redirigé(e) d'ici quelques secondes.</p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div id="noConnection">
                <h1>Pas de connexion détectée!</h1>
                <p>Vous n'êtes pas connecté ou votre session a expiré.</p>
            </div>
        <?php endif; ?>
        <div>

        </div>
    </main>
</body>
</html>
