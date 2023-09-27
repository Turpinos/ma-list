<?php

//Reception des donnees de la BDD...
if(isset($_SESSION['sessionActive'])){
    $sessionItems = $mySqlConnection->prepare('SELECT `date`, `title`, `price`, `qte` FROM items WHERE sessionKey = (:sessionKey)');
    $sessionItems -> execute([
        'sessionKey' => $_SESSION['sessionActive']
    ]);
    $itemsArray = $sessionItems->fetchAll();
    $jsArray = json_encode($itemsArray);
}

if(isset($_SESSION['sessionActive'])){
    $sessionPeoples = $mySqlConnection->prepare('SELECT `nameParticipant`, `contribution` FROM participants WHERE sessionKey = (:sessionKey)');
    $sessionPeoples -> execute([
        'sessionKey' => $_SESSION['sessionActive']
    ]);
    $peoplesArray = $sessionPeoples -> fetchAll();
    $jsPeoples = json_encode($peoplesArray);
}

if(isset($_SESSION['sessionActive'])){
    $attribution = $mySqlConnection->prepare('SELECT `userName` FROM `attributions` WHERE sessionKey = (:sessionKey)');
    $attribution->execute([
        'sessionKey' => $_SESSION['sessionActive']
    ]);
    $listSpec = $attribution->fetchAll(PDO::FETCH_ASSOC);
}

?>