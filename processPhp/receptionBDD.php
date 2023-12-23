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
    $attribution = $mySqlConnection->prepare('SELECT `userName` FROM `attributions` WHERE sessionKey = (:sessionKey) ORDER BY `userName`');
    $attribution->execute([
        'sessionKey' => $_SESSION['sessionActive']
    ]);
    $listSpec = $attribution->fetchAll(PDO::FETCH_ASSOC);
}

if(isset($_SESSION['sessionActive'])){
    $task = $mySqlConnection->prepare('SELECT `taskName` as nameTask, `dateFrom`, `dateTo`, `personnel` as `selectPersonnel` FROM `agenda` WHERE sessionKey = :sessionKey');
    $task-> execute([
        'sessionKey' => $_SESSION['sessionActive']
    ]);
    $taskLs = $task->fetchAll(PDO::FETCH_ASSOC);
    if($_SESSION['nameModerator'] == $_SESSION['userActive']){
        $taskList = json_encode($taskLs);
    }else{
        $arrayTask = [];
        foreach($taskLs as $oneTask){
            $str = $oneTask['selectPersonnel'];
            if(preg_match('/'.$_SESSION['userActive'].'/', $str)){
                array_push($arrayTask, $oneTask);
            }
        }
        $taskList = json_encode($arrayTask);
    }
    
}

?>