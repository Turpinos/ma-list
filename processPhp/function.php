<?php
require_once('initBDD.php');

$search = false;
//Validation de connection pour utilisateur deja existant..
if(isset($_POST['inputUser']) && isset($_POST['inputPassword'])){
   if(!isset($_SESSION['userActive'])){
       foreach($users as $user){
           if($user['userName'] == $_POST['inputUser'] && password_verify($_POST['inputPassword'], $user['userKey'])){
              if(isset($_POST['AccountActiveForSessionActive']) || !empty($_POST['AccountActiveForNewSession'])){
                     $_SESSION['userActive'] = $user['userName'];
              }else{
                     $search = true;
              }
           }
       }   
   }

   //Recherche après vérification si le compte possède des attributions..
   if($search){
       $session = $mySqlConnection->prepare('SELECT `userName`, `sessionKey` FROM `attributions` WHERE userName = :userName');
       $session->execute([
              'userName' => $_POST['inputUser']
       ]);
       $listSession = $session->fetchAll(PDO::FETCH_ASSOC);

       $modo = $mySqlConnection->prepare('SELECT `sessionKey`, `UserSession` FROM `sessionsusers` WHERE UserSession = :UserSession');
       $modo-> execute([
              'UserSession' => $_POST['inputUser']
       ]);
       $listModo = $modo->fetchAll(PDO::FETCH_ASSOC);
   }
    
   //Validation du choix ou creation de session..
   if(!isset($_SESSION['sessionActive']) && (isset($_POST['AccountActiveForSessionActive']) || !empty($_POST['AccountActiveForNewSession']))){
       if(!empty($_POST['AccountActiveForSessionActive']) && !empty($_POST['AccountActiveForNewSession'])){
              $_SESSION['errorDoubleInput'] = 'Vous ne pouvez pas vous connectez et créer une session en même temps.';
       }else if(!empty($_POST['AccountActiveForSessionActive'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['AccountActiveForSessionActive']){
                            $_SESSION['sessionActive'] = $validSession['sessionKey'];
                            $_SESSION['nameModerator'] = $validSession['UserSession'];
                     }
              }
       }else if(!empty($_POST['AccountActiveForNewSession'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['AccountActiveForNewSession']){
                            $_SESSION['errorSessionIsActive'] = "La session existe déjà.";
                     }
              }

              $accountActiveForNewSession = validInput($_POST['AccountActiveForNewSession']);

              if(!isset($_SESSION['errorSessionIsActive']) && !preg_match('/["\'(|;,><{=})(.]/', $_POST['AccountActiveForNewSession']) && !empty($accountActiveForNewSession) && isset($_SESSION['userActive']) && strlen($_POST['AccountActiveForNewSession']) <= 25){
                     $_SESSION['sessionActive'] = $accountActiveForNewSession;
                     $_SESSION['nameModerator'] = $_SESSION['userActive'];

                     $sessionAttri = $mySqlConnection->prepare('INSERT INTO `attributions` (`userName`, `sessionKey`) VALUES (:userName , :sessionKey)');
                     $sessionAttri->execute([
                            'userName' => $_SESSION['userActive'],
                            'sessionKey' => $accountActiveForNewSession
                     ]);

                     $addNewSession = $mySqlConnection -> prepare('INSERT INTO `sessionsusers`(`sessionKey`, `UserSession`) VALUES (:sessionKey, :UserSession)');
                     $addNewSession -> execute([
                            'sessionKey' => $accountActiveForNewSession,
                            'UserSession' => $_SESSION['userActive']
                     ]);
              }else{
                     if(preg_match('/["\'(|;,><{=})(.]/', $_POST['AccountActiveForNewSession'])){
                            $_SESSION['errorSessionName'] = "Le nom de session est invalide";
                     }

                     if(strlen($_POST['AccountActiveForNewSession']) > 25){
                            $_SESSION['errorLengthSession'] = 'Le nom de session est trop long';
                     }
              }
       }
       
   }
}

//Validation de connexion pour une creation de compte..
if(isset($_POST['inputCrtUser'])){
       $inputCrtUser = trim(validInput($_POST['inputCrtUser']));
       if(empty($inputCrtUser) || preg_match('/["\'(|;,><{=})(.]/', $_POST['inputCrtUser'])){
              $_SESSION['errorCrtUser'] = 'Votre pseudo est invalide';
       }

       if(strlen($_POST['inputCrtUser']) > 35){
              $_SESSION['errorLengthName'] = 'Le pseudo est trop long';
       }
}

if(isset($_POST['inputCrtPassword'])){
       $inputCrtPassword = trim(validInput($_POST['inputCrtPassword']));
       if(empty($inputCrtPassword) || preg_match('/["\'(|><{=})(.]/', $_POST['inputCrtPassword'])){
              $_SESSION['errorCrtPassword'] = 'Votre mot de passe est invalide';
       }

       if(strlen($_POST['inputCrtPassword']) > 25){
              $_SESSION['errorLengthPass'] = 'Le MDP est trop long';
       }
}

if(isset($inputCrtUser) && isset($inputCrtPassword) && !empty($inputCrtUser) && !empty($inputCrtPassword) && !preg_match('/["\'(|><{=})(.]/', $_POST['inputCrtPassword']) && !preg_match('/["\'(|;,><{=})(.]/', $_POST['inputCrtUser']) && strlen($_POST['inputCrtPassword']) <= 25 && strlen($_POST['inputCrtUser']) <= 35){
       
       foreach($users as $user){
           if($user['userName'] == $_POST['inputCrtUser']){
                  $_SESSION['errorUserAlreadyExists'] = "Ce nom d'utilisateur est déjà existant.";
           }
       }  
              
       if(!empty($_SERVER['HTTP_CLIENT_IP'])){
              $ip = "prox-". $_SERVER['HTTP_CLIENT_IP'];
       }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
              $ip = "shared-". $_SERVER['HTTP_X_FORWARDED_FOR'];
       }else{
              $ip = "ip-". $_SERVER['REMOTE_ADDR'];
       }

       if(isset($_POST['rgpd'])){
              $rgpd = 1;
       }else{
              $rgpd = 0;
       };
       
       if(!isset($_SESSION['errorUserAlreadyExists'])){
              $_SESSION['placeholderPseudo'] = $inputCrtUser;
              $_SESSION['placeHolderpass'] = $inputCrtPassword;
              $addUser = $mySqlConnection -> prepare('INSERT INTO `users`(`userName`, `userKey`, `ip`, `rgpd`) VALUES (:userName, :userKey, :ip, :rgpd)');
              $addUser -> execute([
                     'userName' => $inputCrtUser,
                     'userKey' => password_hash($inputCrtPassword, PASSWORD_DEFAULT),
                     'ip' => $ip,
                     'rgpd' => $rgpd
              ]);
       }
}

    
if(isset($_POST['inputUser']) && !isset($_SESSION['userActive']) && !$search){
       $_SESSION['errorUser'] = 'Il y a une erreur avec le nom d\'utilisateur ou le MDP.';
       
}

if(isset($_POST['AccountActiveForSessionActive']) && !isset($_SESSION['userActive'])){
       $_SESSION['errorSession'] = 'Il y a une erreur avec la session';
}


//Info attributions

if(isset($_SESSION['userActive']) && isset($_SESSION['sessionActive'])){
       
       $sessionAttri = $mySqlConnection->prepare('SELECT `sessionKey` FROM attributions WHERE userName = (:userName)');
       $sessionAttri->execute([
              'userName' => $_SESSION['userActive']
       ]);
       $Attributions = $sessionAttri->fetchAll();

       $validAccess = false;
foreach($Attributions as $isValidAttribution){
       if($isValidAttribution['sessionKey'] == $_SESSION['sessionActive']){
              $validAccess = true;
       };
}

       if(!$validAccess){
              unset($_SESSION['userActive']);
              unset($_SESSION['sessionActive']);
              $_SESSION['errorAccess'] = 'Vous n\'avez pas les droits d\'accès à la session choisie';
       }

}

//Retirer une attribution..

if(isset($_POST['delFormSpec'])){
       $delSpec = $mySqlConnection->prepare('DELETE FROM `attributions` WHERE `userName` = :userName AND `sessionKey` = :sessionKey');
       $delSpec->execute([
              'userName' => validInput($_POST['delFormSpec']),
              'sessionKey' => $_SESSION['sessionActive']
       ]);
}

//Ajouter attribution..

if(isset($_POST['addFromSpec'])){

      $newSpec = validInput($_POST['addFromSpec']);

      $reelUser = false;
       foreach($users as $user){

              if($user['userName'] == $newSpec){

                     $reelUser = true;

                     $attrib = $mySqlConnection->prepare('SELECT `userName` FROM `attributions` WHERE sessionKey = (:sessionKey) ORDER BY `userName`');
                     $attrib->execute([
                         'sessionKey' => $_SESSION['sessionActive']
                     ]);
                     $Specs = $attrib->fetchAll(PDO::FETCH_ASSOC);

                     
                     $attributionAlreadyExist = false;
                     foreach($Specs as $spec){
                            if($spec['userName'] == $newSpec){
                                   $attributionAlreadyExist = true;
                            }
                     }
                     if(!$attributionAlreadyExist){
                            $newAttribution = $mySqlConnection->prepare('INSERT INTO `attributions` (`userName`, `sessionKey`) VALUES (:userName, :sessionKey)');
                            $newAttribution->execute([
                                   'userName' => $newSpec,
                                   'sessionKey' => $_SESSION['sessionActive']
                            ]);
                     }//Afficher maintenant les messages d'erreurs..
                     
                     if($attributionAlreadyExist){
                            $_SESSION['errorDoublon'] = 'Ce spectateur se trouve déjà dans la liste';
                     }
              }
       }

       if(!$reelUser){
              $_SESSION['uncaughtUser'] = 'Ce pseudo n\'existe pas';
       }
}

//Suppression de la session_______________________________________________________
if(isset($_POST['deleteSession']) && isset($_POST['ConfDeleteSession'])){

       $mdp = validInput($_POST['ConfDeleteSession']);

       $validDelSession = false;

       foreach($users as $user){

              if($user['userName'] == $_SESSION['userActive'] && password_verify($mdp, $user['userKey'])){
                     $validDelSession = true;
              }
       }

       if($validDelSession){

            $selectItems = $mySqlConnection->prepare('SELECT `title` FROM `items` WHERE sessionKey = :sessionKey');
            $selectItems->execute([
              'sessionKey' => $_SESSION['sessionActive']
            ]);
            $item = $selectItems->fetchAll();
            
            $selectParts = $mySqlConnection->prepare('SELECT `nameParticipant` FROM `participants` WHERE sessionKey = :sessionKey');
            $selectParts->execute([
              'sessionKey' => $_SESSION['sessionActive']
            ]);
            $part = $selectParts->fetchAll();

            $selectAttri = $mySqlConnection->prepare('SELECT `userName` FROM `attributions` WHERE sessionKey = :sessionKey');
            $selectAttri->execute([
              'sessionKey' => $_SESSION['sessionActive']
            ]);
            $attri = $selectAttri->fetchAll();

            $selectTask = $mySqlConnection->prepare('SELECT `taskName` FROM `agenda` WHERE sessionKey = :sessionKey');
            $selectTask->execute([
              'sessionKey' => $_SESSION['sessionActive']
            ]);
            $task = $selectTask->fetchAll();

            try {
              // Démarrez la transaction
              $mySqlConnection->beginTransaction();
      
              if(count($item) != 0){
                     $delItems = $mySqlConnection->prepare('DELETE FROM items WHERE sessionKey = :sessionKey');
                     $delItems->execute([
                            'sessionKey' => $_SESSION['sessionActive']
                     ]);
              }

              if(count($part) != 0){
                     $delPart = $mySqlConnection->prepare('DELETE FROM participants WHERE sessionKey = :sessionKey');
                     $delPart->execute([
                            'sessionKey' => $_SESSION['sessionActive']
                     ]);
              }

              if(count($attri) != 0){
                     $delAttri = $mySqlConnection->prepare('DELETE FROM attributions WHERE sessionKey = :sessionKey');
                     $delAttri->execute([
                            'sessionKey' => $_SESSION['sessionActive']
                     ]);
              }

              if(count($task) != 0){
                     $delTask = $mySqlConnection->prepare('DELETE FROM agenda WHERE sessionKey = :sessionKey');
                     $delTask->execute([
                            'sessionKey' => $_SESSION['sessionActive']
                     ]);
              }

              $delSession = $mySqlConnection->prepare('DELETE FROM sessionsusers WHERE sessionKey = :sessionKey');
              $delSession->execute([
                     'sessionKey' => $_SESSION['sessionActive']
              ]);
      
              // Si tout s'est bien passé, validez la transaction
              $mySqlConnection->commit();
      
              unset($_SESSION['sessionActive']);
              unset($_SESSION['userActive']);
              unset($_SESSION['nameModerator']);
              header('Refresh:0, url=login.php');

          } catch (PDOException $e) {
              // En cas d'erreur, annulez la transaction
              $mySqlConnection->rollBack();
      
              // Gérez l'erreur ou affichez un message d'erreur
              $_POST['errorDelSession'] =  "Erreur : La suppression a échoué";
          }
       }else{
              $_POST['errorMdp'] = 'Le mot de passe est erroné';
       }
}

//Suppression du compte_______________________________________________________________
if(isset($_POST['deleteAccount']) && isset($_POST['ConfDeleteAccount'])){

       $mdp = validInput($_POST['ConfDeleteAccount']);

       $validDelAccount = false;

       foreach($users as $user){
              if($user['userName'] == $_SESSION['userActive'] && password_verify($mdp, $user['userKey'])){
                     $validDelAccount = true;
              };
       };

       if($validDelAccount){

              $selectSession = $mySqlConnection->prepare('SELECT `sessionKey` FROM `sessionsusers` WHERE UserSession = :UserSession');
              $selectSession->execute([
                     'UserSession' => $_SESSION['userActive']
              ]);
              $session = $selectSession->fetchAll(PDO::FETCH_ASSOC);

              $i = 0;
              foreach($session as $own){

                     $selectItems = $mySqlConnection->prepare('SELECT `title` FROM `items` WHERE sessionKey = :sessionKey');
                     $selectItems->execute([
                       'sessionKey' => $own['sessionKey']
                     ]);
                     $item[$i] = $selectItems->fetchAll();

                     $selectParts = $mySqlConnection->prepare('SELECT `nameParticipant` FROM `participants` WHERE sessionKey = :sessionKey');
                     $selectParts->execute([
                       'sessionKey' => $own['sessionKey']
                     ]);
                     $part[$i] = $selectParts->fetchAll();

                     $selectTasks = $mySqlConnection->prepare('SELECT `taskName` FROM `agenda` WHERE sessionKey = :sessionKey');
                     $selectTasks->execute([
                            'sessionKey' => $own['sessionKey']
                     ]);
                     $tasks[$i] = $selectTasks->fetchAll();

                     $selectAttri = $mySqlConnection->prepare('SELECT `userName` FROM `attributions` WHERE sessionKey = :sessionKey');
                     $selectAttri->execute([
                            'sessionKey' => $own['sessionKey']
                     ]);
                     $attri[$i] = $selectAttri->fetchAll();

                     $i++;
              }

              try {
                     // Démarrez la transaction
                     $mySqlConnection->beginTransaction();
             
                     $u = 0;
                     foreach($session as $own){

                            if(count($item[$u]) != 0){
                                   $delItems = $mySqlConnection->prepare('DELETE FROM items WHERE sessionKey = :sessionKey');
                                   $delItems->execute([
                                          'sessionKey' => $own['sessionKey']
                                   ]);
                            }
              
                            if(count($part[$u]) != 0){
                                   $delPart = $mySqlConnection->prepare('DELETE FROM participants WHERE sessionKey = :sessionKey');
                                   $delPart->execute([
                                          'sessionKey' => $own['sessionKey']
                                   ]);
                            }

                            if(count($tasks[$u]) != 0){
                                   $delTasks = $mySqlConnection->prepare('DELETE FROM agenda WHERE sessionKey = :sessionKey');
                                   $delTasks->execute([
                                          'sessionKey' => $own['sessionKey']
                                   ]);
                            }

                            if(count($attri[$u]) != 0){
                                   $delSpecSession = $mySqlConnection->prepare('DELETE FROM attributions WHERE sessionKey = :sessionKey');
                                   $delSpecSession->execute([
                                          'sessionKey' => $own['sessionKey']
                                   ]);
                            }
                            
                            $u++;
                     }

                     $delSession = $mySqlConnection->prepare('DELETE FROM sessionsusers WHERE UserSession = :UserSession');
                     $delSession->execute([
                            'UserSession' => $_SESSION['userActive']
                     ]);

                     $delUser = $mySqlConnection->prepare('DELETE FROM users WHERE userName = :userName');
                     $delUser->execute([
                            'userName' => $_SESSION['userActive']
                     ]);
                     
             
                     // Si tout s'est bien passé, validez la transaction
                     $mySqlConnection->commit();
             
                     unset($_SESSION['sessionActive']);
                     unset($_SESSION['userActive']);
                     unset($_SESSION['nameModerator']);
                     header('Refresh:0, url=login.php');

              } catch (PDOException $e) {
                     // En cas d'erreur, annulez la transaction
                     $mySqlConnection->rollBack();
             
                     // Gérez l'erreur ou affichez un message d'erreur
                     $_POST['errorDelAccount'] = "Erreur : La suppression a échoué";
              }

       }else{
              $_POST['errorMdp'] = 'Le mot de passe est erroné';
       }
}

//____________________________________Envoi mail au support______________________________________//

function validInput($param){
    $param = strip_tags($param);
    $param = htmlspecialchars($param);
    return $param;
}
?>