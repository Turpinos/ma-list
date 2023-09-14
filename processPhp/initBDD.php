<?php
//Connexion BDD...

    try{
        $mySqlConnection = new PDO(
            'mysql:host=localhost;dbname=todolist;charset=utf8',
            'root',
            'root'
        );
    }catch (Exception $e){
        die('Erreur : '.$e->getMessage());
    }

//Info users
$usersStatement = $mySqlConnection->prepare('SELECT `userName`, `userKey` FROM users');
$usersStatement->execute();
$users = $usersStatement->fetchAll();


//Info sessions
$sessionStatement = $mySqlConnection->prepare('SELECT `sessionKey`, `UserSession`  FROM sessionsusers');
$sessionStatement->execute();
$sessionsUsers = $sessionStatement->fetchAll();
$sessionsUsers



?>