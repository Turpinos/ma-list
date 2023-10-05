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
                <form class="formLogin" method="post" action="<?php if(isset($listSession)){ echo "index.php"; }else{ echo "login.php"; } ?>">
                    <h3>Connexion</h3>
                    <div>
                        <label for="inputUser">Pseudo:</label>
                        <input type="text" name="inputUser" <?php if(isset($listSession)){ echo 'value="'. $_POST['inputUser'].'"';}; ?> <?php if(isset($_SESSION['placeholderPseudo'])){ echo 'value="'. $_SESSION['placeholderPseudo'] .'"';} ?>>
                    </div>
                    <div>
                        <label for="inputPassword">Mot de passe:</label>
                        <div>
                        <input class="switchPassword" type="password" name="inputPassword" <?php if(isset($listSession)){ echo 'value="'. $_POST['inputPassword'].'"';}; ?> <?php if(isset($_SESSION['placeHolderpass'])){ echo 'value="'. $_SESSION['placeHolderpass'] .'"';} ?>><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                        </div>
                    </div>
                    <div><p>Se connecter à une session:</p></div>
                    <div>
                        <p class="label">Session:</p>
                        <div class="radio">
                            <?php if(isset($listSession)): ?>
                                <?php if(count($listSession) != 0): ?>
                                    <?php foreach($listSession as $oneSession): ?>
                                        <p><input type="radio" name="AccountActiveForSessionActive" value="<?php echo $oneSession['sessionKey'] ?>">
                                        <label for="AccountActiveForSessionActive"><?php echo $oneSession['sessionKey'] ?>
                                    <?php
                                    foreach($listModo as $modo){
                                        if($modo['sessionKey'] == $oneSession['sessionKey']){
                                            echo " (hôte)" ;
                                        };
                                    };
                                    ?>
                                    </label></p>
                                    <?php endforeach;?>
                                
                                <?php else: ?>
                                    <p class="msg">Aucune session trouvée.</p>
                                    <p class="msg">Rechargez si vous venez d'être ajouté(e).</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="msg">Connectez-vous pour afficher.</p>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                    <div><p>Ou créer une session:</p></div>
                    <div>
                        <label for="AccountActiveForNewSession">Session:</label>
                        <input type="text" maxlength="25" name="AccountActiveForNewSession">
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
                            <input type="text" maxlength="35" name="inputCrtUser">
                        </div>
                        <div>
                            <label for="inputCrtPassword">Mot de passe:</label>
                            <input class="switchPassword" minlength="10" maxlength="25" type="password" name="inputCrtPassword"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                        </div>
                        <button type="submit">Créer un compte</button>
                    </form>
                </div>
            </main>
            <?php else: ?>
                <?php header("Refresh:0; url=index.php"); ?>
            <?php endif; ?>
    <?php else: ?>
    <main class="mainLogin">
        <form class="formLogin" method="post" action="<?php if(isset($listSession)){ echo "index.php"; }else{ echo "login.php"; } ?>">
            <h3>Connexion</h3>
            <div>
                <label for="inputUser">Pseudo:</label>
                <input type="text" name="inputUser" <?php if(isset($listSession)){ echo 'value="'. $_POST['inputUser'].'"';}; ?> <?php if(isset($_SESSION['placeholderPseudo'])){ echo 'value="'. $_SESSION['placeholderPseudo'] .'"';} ?>>
            </div>
            <div>
                <label for="inputPassword">Mot de passe:</label>
                <div>
                <input class="switchPassword" type="password" name="inputPassword" <?php if(isset($listSession)){ echo 'value="'. $_POST['inputPassword'].'"';}; ?> <?php if(isset($_SESSION['placeHolderpass'])){ echo 'value="'. $_SESSION['placeHolderpass'] .'"';} ?>><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
                </div>
            </div>
            <div><p>Se connecter à une session:</p></div>
            <div>
                <p class="label">Session:</p>
                <div class="radio">
                    <?php if(isset($listSession)): ?>
                        <?php if(count($listSession) != 0): ?>
                            <?php foreach($listSession as $oneSession): ?>
                                <p><input type="radio" name="AccountActiveForSessionActive" value="<?php echo $oneSession['sessionKey'] ?>">
                                <label for="AccountActiveForSessionActive"><?php echo $oneSession['sessionKey'] ?>
                            <?php
                            foreach($listModo as $modo){
                                if($modo['sessionKey'] == $oneSession['sessionKey']){
                                    echo " (hôte)" ;
                                };
                            };
                            ?>
                            </label></p>
                            <?php endforeach;?>

                        <?php else: ?>
                            <p class="msg">Aucune session trouvée.</p>
                            <p class="msg">Rechargez si vous venez d'être ajouté(e).</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="msg">Connectez-vous pour afficher.</p>

                    <?php endif; ?>
                </div>
            </div>
            <div><p>Ou créer une session:</p></div>
            <div>
                <label for="AccountActiveForNewSession">Session:</label>
                <input type="text" maxlength="25" name="AccountActiveForNewSession">
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

                if(isset($_SESSION['errorLengthSession'])){
                    echo '<p>'. $_SESSION['errorLengthSession'] .'</p>';
                    unset($_SESSION['errorLengthSession']);
                }

                if(isset($_SESSION['errorSessionName'])){
                    echo '<p>'. $_SESSION['errorSessionName'] .'</p>';
                    unset($_SESSION['errorSessionName']);
                }
                
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
                    unset($_SESSION['errorAccess']);
                };

                ?>
            </div>
        </form>
        <div class="formOption">
            <form class="formLogin" method="post" action="login.php">
                <h3>Inscription</h3>
                <div>
                    <label for="inputCrtUser">Pseudo:</label>
                    <input type="text" maxlength="35" name="inputCrtUser">
                </div>
                <div>
                    <label for="inputCrtPassword">Mot de passe:</label>
                    <div>
                    <input class="switchPassword" minlength="10" maxlength="25" type="password" name="inputCrtPassword"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p>
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

                    if(isset($_SESSION['errorLengthPass'])){
                        echo '<p>'. $_SESSION['errorLengthPass'] .'</p>';
                        unset($_SESSION['errorLengthPass']);
                    }

                    if(isset($_SESSION['errorLengthName'])){
                        echo '<p>'. $_SESSION['errorLengthName'] .'</p>';
                        unset($_SESSION['errorLengthName']);
                    }

                    ?>
                </div>
            </form>
        </div>
    </main>
    <?php endif; ?>
    <script type="module" src="login.js"></script>
</body>
</html>