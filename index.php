<?php session_start();
require_once('processPhp/function.php');
require_once('processPhp/receptionBDD.php');

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
    <header>
        <div>
            <h1>Ma liste</h1>
        </div>
    </header>
    <main id="mainIndex">
        <?php if(isset($_SESSION['userActive']) && isset($_SESSION['sessionActive'])): ?>
            <section id="list">
                <h2>Ma liste</h2>
                <?php if($_SESSION['nameModerator'] == $_SESSION['userActive']): ?>
                <form method="POST" enctype="text/plain">
                    <div>
                        <div>
                            <label id="labelName" for="name">L'élément: </label>
                            <input maxlength="16" id="name" name="name" type="text" placeholder="Nom">
                        </div>
                        <div>
                            <label id="labelValue" for="value">Prix: </label>
                            <input id="value" name="value" type="number" placeholder="€">
                        </div>
                        <div>
                            <label id="labelQte" for="qte">Quantité: </label>
                            <input id="qte" name="qte" type="number" value="1" placeholder="Qte">
                        </div>
                    </div>
                    <button type="button" id="buttonAdd">Ajouter</button>
                </form>
                <?php endif; ?>
                <div id="items">
                </div>
            </section>
            <section id="participation">
                <h2>Participation</h2>
                <?php if($_SESSION['nameModerator'] == $_SESSION['userActive']): ?>
                <form>
                    <div>
                        <label id="labelParticipant" for="participant">Participant:</label>
                        <input id="inputParticipant" maxlength="10" name="participant" placeholder="Participant">
                    </div>
                    <button type="button" id="buttonParticipant">Ajouter</button>
                </form>
                <?php endif; ?>
                <div id="participants"></div>
            </section>
            <section id="sectionSpec">
                <h2>Spectateurs</h2>
                <div id="spec">
                    <?php if($_SESSION['userActive'] == $_SESSION['nameModerator']): ?>
                        <!-- Affichage spec pour modérateur-->
                        <?php foreach($listSpec as $spec): ?>
                            <?php if($spec['userName'] != $_SESSION['nameModerator']): ?>
                                <form action="index.php" method="post">
                                    <input type="hidden" name="delFormSpec" value="<?php echo $spec['userName'] ?>">
                                    <button class="spec" type="submit"><?php echo $spec['userName'] ?><img src="img/fermer.png" alt="supprimer"></button>
                                </form>
                            <?php else: ?>
                                <p class="host"><?php echo $spec['userName'] ?>(hôte)</p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Affichage spec pour spec-->
                        <?php foreach($listSpec as $spec): ?>
                            <?php if($spec['userName'] != $_SESSION['nameModerator']): ?>
                                <p class="spec"><?php echo $spec['userName'] ?></p>
                            <?php else: ?>
                                <p class="host"><?php echo $spec['userName'] ?>(hôte)</p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php if($_SESSION['nameModerator'] == $_SESSION['userActive']): ?>
                    <form id="addFormSpec" action="index.php" method="post">
                        <input type="text" name="addFromSpec" required>
                        <button type="submit">Ajouter</button>
                    </form>
                    <div class='alert'>
                        <?php if(isset($_SESSION['errorDoublon'])){ echo '<p>'. $_SESSION['errorDoublon'] .'</p>';
                        unset($_SESSION['errorDoublon']);} ?>
                        <?php if(isset($_SESSION['uncaughtUser'])){ echo '<p>'. $_SESSION['uncaughtUser'] .'</p>';
                        unset($_SESSION['uncaughtUser']);} ?>
                    </div>
                    <div id='setting'>
                        <form class="formSession" action="index.php" method="post">
                            <input type="hidden" name="deleteSession" value="delete">
                            <div><input class="switchPassword" type="password" name="ConfDeleteSession" placeholder="Mot de passe"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p></div>
                            <button id="boutonSession" type="submit">Supprimer la session</button>
                        </form>
                        <form class="formAccount" action="index.php" method="post">
                            <input type="hidden" name="deleteAccount" value="delete">
                            <div><input class="switchPassword" type="password" name="ConfDeleteAccount" placeholder="Mot de passe"><p class="indicatorSwitch"><img src="img/cadenas-verrouille.png"></p></div>
                            <button id="boutonCompte" type="submit">Supprimer le compte</button>
                        </form>
                        <div class='alert'>
                            <?php if(isset($_POST['errorMdp'])){ echo '<p>'. $_POST['errorMdp'] .'</p>';} ?>
                            <?php if(isset($_POST['errorDelSession'])){ echo '<p>'. $_POST['errorDelSession'] .'</p>';} ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php else:
            header("Refresh:0; url=login.php"); ?>
        <?php endif; ?>
    </main>
    <?php if(isset($_SESSION['userActive']) && isset($_SESSION['sessionActive'])): ?>
        <footer>
            <p id="info"><b id="nameUserActive"><?php echo $_SESSION['userActive'] ?></b> est connecté sur la session <b id="nameSessionActive"><?php echo $_SESSION['sessionActive'] ?></b></p>

            <?php if($_SESSION['nameModerator'] == $_SESSION['userActive']): ?>
                <form id="formToSave" method="post" action="envoieBDD.php">
                    <button id="save" type="submit"><img id="saveImg" src="img/sauvegarder.png" alt="sauvegarde"><p>Enregistrer</p></button>
                </form>

                <button type="button" id="download"><img id="downloadImg" src="img/telecharger.png" alt="importer"><p>Importer</p></button>
            <?php endif; ?>

            <form method="post" action="login.php">
                <button id='disconnect' type="submit" name="deco" value="reset">Déconnecter</button>
            </form>    
        </footer>
    <?php endif; ?>
    <?php if(isset($_SESSION['userActive']) && isset($_SESSION['sessionActive'])): ?>

        <?php echo "<script> let elements = '".$jsArray."'; </script>"; ?>
        <?php echo "<script> let peoples = '".$jsPeoples."'; </script>"; ?>
        <?php if($_SESSION['userActive'] == $_SESSION['nameModerator']){ echo "<script> let moderator = 'true'; </script>";}; ?>
        
        <script type="text/javascript" src="main.js"></script>
    <?php endif; ?>
</body>
</html>