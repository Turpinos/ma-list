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
                    <input class="switchPassword" type="password" name="inputPassword"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                </div>
                <div><p>Se connecter à une session:</p></div>
                <div>
                    <label for="AccountActiveForSessionActive">Session:</label>
                    <!--<input type="text" name="AccountActiveForSessionActive">-->
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
                <div class="formOption">
                    <form class="formLogin" method="post" action="login.php">
                        <h3>Inscription</h3>
                        <div>
                            <label for="inputCrtUser">Pseudo:</label>
                            <input type="text" name="inputCrtUser">
                        </div>
                        <div>
                            <label for="inputCrtPassword">Mot de passe:</label>
                            <input class="switchPassword" type="password" name="inputCrtPassword"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                        </div>
                        <button type="submit">Créer un compte</button>
                    </form>
                    <form class="formLogin" action="login.php" method="post">
                        <h3>Message au support</h3>
                        <div>
                            <label for="emailForSupport">Votre Email:</label>
                            <input type="text" name="emailForSupport" required>
                        </div>
                        <div>
                            <label for="messageForSupport">Votre message:</label>
                            <textarea name="messageForSupport" maxlength="250" cols="20" required></textarea>
                        </div>
                        <button type="submit">Envoyer</button>
                        <div class="alert">
                            <?php 

                            if(isset($_SESSION['errorMessageSupport'])){
                                echo '<p>'. $_SESSION['errorMessageSupport'] .'</p>';
                                unset($_SESSION['errorMessageSupport']);
                            };

                            ?>
                        </div>
                    </form>
                </div>
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
                <input type="text" name="inputUser" <?php if(isset($_SESSION['placeholderPseudo'])){ echo 'value="'. $_SESSION['placeholderPseudo'] .'"';} ?>>
            </div>
            <div>
                <label for="inputPassword">Mot de passe:</label>
                <div>
                <input class="switchPassword" type="password" name="inputPassword" <?php if(isset($_SESSION['placeHolderpass'])){ echo 'value="'. $_SESSION['placeHolderpass'] .'"';} ?>><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                </div>
            </div>
            <div><p>Se connecter à une session:</p></div>
            <div>
                <p for="AccountActiveForSessionActive"><b>Session:</b></p>
                <div class="radio">
                    <?php if(isset($listSession)): ?>
                        <?php if(count($listSession) != 0): ?>

                        <?php foreach($listSession as $oneSession): ?>
                            <input type="radio" name="AccountActiveForSessionActive">
                        <?php endforeach;?>

                        <?php else: ?>
                            <p>Aucune session trouvée</p>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
                <!--<input type="text" name="AccountActiveForSessionActive">-->
            </div>
            <div><p>Ou créer une session:</p></div>
            <div>
                <label for="AccountActiveForNewSession">Session:</label>
                <input type="text" name="AccountActiveForNewSession">
            </div>
            <button type="submit">Connexion</button>
            <div class='alert'>
                <?php

                if(isset($_SESSION['placeholderPseudo'])){
                    unset($_SESSION['placeholderPseudo']);
                };

                if(isset($_SESSION['placeHolderpass'])){
                    unset($_SESSION['placeHolderpass']);
                };

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
        <div class="formOption">
            <form class="formLogin" method="post" action="login.php">
                <h3>Inscription</h3>
                <div>
                    <label for="inputCrtUser">Pseudo:</label>
                    <input type="text" name="inputCrtUser">
                </div>
                <div>
                    <label for="inputCrtPassword">Mot de passe:</label>
                    <div>
                    <input class="switchPassword" type="password" name="inputCrtPassword"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                    </div>
                </div>
                <button type="submit">Créer un compte</button>
                <div class="alert">
                    <?php

                    if(isset($_SESSION['errorCrtUser'])){
                        echo '<p>'. $_SESSION['errorCrtUser'] .'</p>';
                        unset($_SESSION['errorCrtUser']);
                    }

                    if(isset($_SESSION['errorCrtPassword'])){
                        echo '<p>'. $_SESSION['errorCrtPassword'] .'</p>';
                        unset($_SESSION['errorCrtPassword']);
                    }

                    if(isset($_SESSION['errorUserAlreadyExists'])){
                        echo '<p>'. $_SESSION['errorUserAlreadyExists'] .'</p>';
                        unset($_SESSION['errorUserAlreadyExists']);
                    }

                    ?>
                </div>
            </form>
            <form class="formLogin" action="login.php" method="post">
                <h3>Message au support</h3>
                <div>
                    <label for="emailForSupport">Votre Email:</label>
                    <input type="text" name="emailForSupport" required>
                </div>
                <div>
                    <label for="messageForSupport">Votre message:</label>
                    <textarea name="messageForSupport" maxlength="250" cols="20" required></textarea>
                </div>
                <button type="submit">Envoyer</button>
                <div class="alert">
                    <?php 

                    if(isset($_SESSION['errorMessageSupport'])){
                        echo '<p>'. $_SESSION['errorMessageSupport'] .'</p>';
                        unset($_SESSION['errorMessageSupport']);
                    };

                    ?>
                </div>
            </form>
        </div>
    </main>
    <?php endif; ?>
    <script type="module" src="login.js"></script>
</body>
</html>