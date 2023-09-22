<?php session_start() ?>
<?php require_once('processPhp/function.php'); ?>
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
    <header>
        <div>
            <h1>Ma liste</h1>
        </div>
    </header>
    <?php if(isset($_SESSION['userActive']) && isset($_SESSION['sessionActive'])): ?>
        <?php if(isset($_POST['deco'])): ?>
            <main class="mainLogin">
                <form class="formLogin" method="post" action="index.php">
                    <h3>Connexion</h3>
                <div>
                    <label for="inputUser">Pseudo:</label>
                    <input type="text" name="inputUser">
                </div>
                <div>
                    <label for="inputPassword">Mot de passe:</label>
                    <input type="password" name="inputPassword">
                </div>
                <div><p>Se connecter à une session:</p></div>
                <div>
                    <label for="AccountActiveForSessionActive">Session:</label>
                    <input type="text" name="AccountActiveForSessionActive">
                </div>
                <div><p>Ou créer une session:</p></div>
                <div>
                    <label for="AccountActiveForNewSession">Session:</label>
                    <input type="text" name="AccountActiveForNewSession">
                </div>
                <button type="submit">Connexion</button>
                <div class='alert'>

                    <?php
                        if(isset($_SESSION['userActive'])){
                            unset($_SESSION['userActive']);
                        }

                        if(isset($_SESSION['sessionActive'])){
                            unset($_SESSION['sessionActive']);
                        }

                        if(isset($_SESSION['nameModerator'])){
                            unset($_SESSION['nameModerator']);
                        };
                    ?>

                
                </div>
                </form>
                <form class="formLogin" method="post" action="index.php">
                    <h3>Inscription</h3>
                    <div>
                        <label for="inputCrtUser">Pseudo:</label>
                        <input type="text" name="inputCrtUser">
                    </div>
                    <div>
                        <label for="inputCrtPassword">Mot de passe:</label>
                        <input type="password" name="inputCrtPassword">
                    </div>
                    <div><p>Se connecter à une session:</p></div>
                    <div>
                        <label for="newAccountForSessionActive">Session:</label>
                        <input type="text" name="newAccountForSessionActive">
                    </div>
                    <div><p>Ou créer une session:</p></div>
                    <div>
                        <label for="newAccountForNewSession">Session:</label>
                        <input type="text" name="newAccountForNewSession">
                    </div>
                    <button type="submit">Créer un compte</button>
                </form>
            </main>
            <?php else: ?>
                <?php header("Refresh:0; url=index.php"); ?>
            <?php endif; ?>
    <?php else: ?>
    <main class="mainLogin">
        <form class="formLogin" method="post" action="index.php">
            <h3>Connexion</h3>
            <div>
                <label for="inputUser">Pseudo:</label>
                <input type="text" name="inputUser">
            </div>
            <div>
                <label for="inputPassword">Mot de passe:</label>
                <input type="password" name="inputPassword">
            </div>
            <div><p>Se connecter à une session:</p></div>
            <div>
                <label for="AccountActiveForSessionActive">Session:</label>
                <input type="text" name="AccountActiveForSessionActive">
            </div>
            <div><p>Ou créer une session:</p></div>
            <div>
                <label for="AccountActiveForNewSession">Session:</label>
                <input type="text" name="AccountActiveForNewSession">
            </div>
            <button type="submit">Connexion</button>
            <div class='alert'>
                <?php
                if(isset($_SESSION['userActive'])){
                    unset($_SESSION['userActive']);
                };
                
                if(isset($_SESSION['sessionActive'])){
                    unset($_SESSION['sessionActive']);
                };

                if(isset($_SESSION['nameModerator'])){
                    unset($_SESSION['nameModerator']);
                };
                
                if(isset($_SESSION['errorUser'])){
                    echo '<p>'. $_SESSION['errorUser'] .'</p>';
                    unset($_SESSION['errorUser']);
                };

                if(isset($_SESSION['errorSession'])){
                    echo '<p>'. $_SESSION['errorSession'] .'</p>';
                    unset($_SESSION['errorSession']);
                };

                if(isset($_SESSION['errorDoubleInput'])){
                    echo '<p>'. $_SESSION['errorDoubleInput'] .'</p>';
                    unset($_SESSION['errorDoubleInput']);
                };

                if(isset($_SESSION['errorSessionIsActive'])){
                    echo '<p>'. $_SESSION['errorSessionIsActive'] .'</p>';
                    unset($_SESSION['errorSessionIsActive']);
                };
                
                if(isset($_SESSION['errorAccess'])){
                    echo '<p>' . $_SESSION['errorAccess'] . '</p>';
                };

                ?>
            </div>
        </form>
        <form class="formLogin" method="post" action="index.php">
            <h3>Inscription</h3>
            <div>
                <label for="inputCrtUser">Pseudo:</label>
                <input type="text" name="inputCrtUser">
            </div>
            <div>
                <label for="inputCrtPassword">Mot de passe:</label>
                <input type="password" name="inputCrtPassword">
            </div>
            <div><p>Se connecter à une session:</p></div>
            <div>
                <label for="newAccountForSessionActive">Session:</label>
                <input type="text" name="newAccountForSessionActive">
            </div>
            <div><p>Ou créer une session:</p></div>
            <div>
                <label for="newAccountForNewSession">Session:</label>
                <input type="text" name="newAccountForNewSession">
            </div>
            <button type="submit">Créer un compte</button>
            <div class="alert">
                <?php
                if(isset($_SESSION['errorDoubleInputCrt'])){
                    echo '<p>'. $_SESSION['errorDoubleInputCrt'] .'</p>';
                    unset($_SESSION['errorDoubleInputCrt']);
                }

                if(isset($_SESSION['errorSessionIsActiveCrt'])){
                    echo '<p>'. $_SESSION['errorSessionIsActiveCrt'] .'</p>';
                    unset($_SESSION['errorSessionIsActiveCrt']);
                }

                if(isset($_SESSION['errorUserAlreadyExists'])){
                    echo '<p>'. $_SESSION['errorUserAlreadyExists'] .'</p>';
                    unset($_SESSION['errorUserAlreadyExists']);
                }
                ?>
            </div>
        </form>
    </main>
    <?php endif; ?>
    <footer>
        
    </footer>
</body>
</html>