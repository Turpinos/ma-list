<?php
require_once('initBDD.php');

//Validation de connection pour utilisateur deja existant..
if(isset($_POST['inputUser']) && isset($_POST['inputPassword'])){
   if(!isset($_SESSION['userActive'])){
       foreach($users as $user){
           if($user['userName'] == $_POST['inputUser'] && $user['userKey'] == $_POST['inputPassword']){
                  $_SESSION['userActive'] = $user['userName'];
           }
       }   
   }
    
   //Validation du choix ou creation de session..
   if(!isset($_SESSION['sessionActive'])){
       if(!empty($_POST['AccountActiveForSessionActive']) && !empty($_POST['AccountActiveForNewSession'])){
              $_SESSION['errorDoubleInput'] = 'Vous ne pouvez pas vous connectez et créer une session en même temps.';
       }else if(!empty($_POST['AccountActiveForSessionActive'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['AccountActiveForSessionActive']){
                            $_SESSION['sessionActive'] = $validSession['sessionKey'];
                     }
              }
       }else if(!empty($_POST['AccountActiveForNewSession'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['AccountActiveForNewSession']){
                            $_SESSION['errorSessionIsActive'] = "La session existe déjà.";
                     }
              }
              $accountActiveForNewSession = validInput($_POST['AccountActiveForNewSession']);
              if(!isset($_SESSION['errorSessionIsActive']) && !empty($accountActiveForNewSession) && isset($_SESSION['userActive'])){
                     $_SESSION['sessionActive'] = $_POST['AccountActiveForNewSession'];
                     $addNewSession = $mySqlConnection -> prepare('INSERT INTO `sessionsusers`(`sessionKey`, `UserSession`) VALUES (:sessionKey, :UserSession)');
                     $addNewSession -> execute([
                            'sessionKey' => $_POST['AccountActiveForNewSession'],
                            'UserSession' => $_SESSION['userActive']
                     ]);
                     $addNewSession -> fetchAll();
              }
       }
       
   }
}

//Validation de connexion pour une creation de compte..
if(isset($_POST['inputCrtUser'])){
       $inputCrtUser = validInput($_POST['inputCrtUser']);
}

if(isset($_POST['inputCrtPassword'])){
       $inputCrtPassword = validInput($_POST['inputCrtPassword']);
}

if(isset($inputCrtUser) && isset($inputCrtPassword)){
       
       foreach($users as $user){
           if($user['userName'] == $_POST['inputCrtUser']){
                  $_SESSION['errorUserAlreadyExists'] = "Ce nom d'utilisateur est déjà existant.";
           }
       }   
       
       if(!isset($_SESSION['errorUserAlreadyExists'])){
              $_SESSION['userActive'] = $_POST['inputCrtUser'];
              $addUser = $mySqlConnection -> prepare('INSERT INTO `users`(`userName`, `userKey`) VALUES (:userName, :userKey)');
              $addUser -> execute([
                     'userName' => $_POST['inputCrtUser'],
                     'userKey' => $_POST['inputCrtPassword']
              ]);
              $addUser -> fetchAll();
       }

       //Validation du choix ou creation de session..
       if(!empty($_POST['newAccountForSessionActive']) && !empty($_POST['newAccountForNewSession'])){
              $_SESSION['errorDoubleInputCrt'] = 'Vous ne pouvez pas vous connectez et créer une session en même temps.';
       }else if(!empty($_POST['newAccountForSessionActive'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['newAccountForSessionActive']){
                            $_SESSION['sessionActive'] = $validSession['sessionKey'];
                     }
              }
       }else if(!empty($_POST['newAccountForNewSession'])){
              foreach($sessionsUsers as $validSession){
                     if($validSession['sessionKey'] == $_POST['newAccountForNewSession']){
                            $_SESSION['errorSessionIsActiveCrt'] = "La session existe déjà.";
                     }
              }
              $newAccountForNewSession = validInput($_POST['newAccountForNewSession']);
              if(!isset($_SESSION['errorSessionIsActiveCrt']) && !empty($newAccountForNewSession) && isset($_SESSION['userActive'])){
                     $_SESSION['sessionActive'] = $_POST['newAccountForNewSession'];
                     $addNewSession = $mySqlConnection -> prepare('INSERT INTO `sessionsusers`(`sessionKey`, `UserSession`) VALUES (:sessionKey, :UserSession)');
                     $addNewSession -> execute([
                            'sessionKey' => $_POST['newAccountForNewSession'],
                            'UserSession' => $_SESSION['userActive']
                     ]);
                     $addNewSession -> fetchAll();
              }
       }
}
    
if(isset($_POST['inputUser']) && !isset($_SESSION['userActive'])){
       if($_SERVER['PHP_SELF'] == '/ToDoList/index.php'){
              $_SESSION['errorUser'] = 'Il y a une erreur avec le nom d\'utilisateur ou le MDP.';
       }
       
}
if(isset($_POST['inputSession']) && !isset($_SESSION['sessionActive'])){
       if(!isset($_SESSION['errorDoubleInput'])){
              if($_SERVER['PHP_SELF'] == '/ToDoList/index.php'){
                     $_SESSION['errorSession'] = 'Il y a une erreur avec le nom de session.';
              }
       }
}



function validInput($param){
    $param = trim($param);
    $param = strip_tags($param);
    $param = htmlspecialchars($param);
    return $param;
}
?>