<?php

//_____________________________________________________Envoie des nouvelles données d'items...
if(isset($_SESSION['sessionActive'])){
    if(isset($_POST['items']['date0']) && isset($_POST['items']['title0']) && isset($_POST['items']['price0']) && isset($_POST['items']['qte0']) || !isset($_POST['items']['date0']) && !isset($_POST['items']['title0']) && !isset($_POST['items']['price0']) && !isset($_POST['items']['qte0'])){
        $removeSessionItems = $mySqlConnection->prepare('DELETE FROM items WHERE sessionKey = (:sessionKey)');
        $removeSessionItems->execute([
            'sessionKey' => $_SESSION['sessionActive']
        ]);

        if(isset($_POST['items']['date0']) && isset($_POST['items']['title0']) && isset($_POST['items']['price0']) && isset($_POST['items']['qte0'])){

            $countItems = count($_POST['items'])/4;
        

            for($i = 0; $i < $countItems; $i++){
                $addSessionItems = $mySqlConnection->prepare('INSERT INTO items (`sessionKey`, `date`, `title`, `price`, `qte`) VALUES (:sessionKey, :sessiondate, :title, :price, :qte)');
                $addSessionItems->execute([
                'sessionKey' => htmlspecialchars(strip_tags($_SESSION['sessionActive'])),
                'sessiondate' => htmlspecialchars(strip_tags($_POST['items']['date'.$i])),
                'title' => htmlspecialchars(strip_tags($_POST['items']['title'.$i])),
                'price' => htmlspecialchars(strip_tags($_POST['items']['price'.$i])),
                'qte' => htmlspecialchars(strip_tags($_POST['items']['qte'.$i]))
                ]);
            }

        }

        $validInput = true;
        header("Refresh:3; url=index.php");

    }else{

        $validInput = false;
        header("Refresh:3; url=index.php");
    }

    $connected = true;
    header("Refresh:3; url=index.php");

//_____________________________________________________Envoie des nouvelles données de participants...
    if(isset($_POST['part']['name0']) && isset($_POST['part']['contribution0']) || !isset($_POST['part']['name0']) && !isset($_POST['part']['contribution0'])){
        $removeSessionPart = $mySqlConnection -> prepare('DELETE FROM participants WHERE sessionKey = (:sessionKey)');
        $removeSessionPart -> execute([
            'sessionKey' => $_SESSION['sessionActive']
        ]);
        
        if(isset($_POST['part']['name0']) && isset($_POST['part']['contribution0'])){
            $countPart = count($_POST['part'])/2;

            for($i = 0; $i < $countPart; $i++){
                $addSessionPart = $mySqlConnection -> prepare('INSERT INTO participants (`sessionKey`, `nameParticipant`, `contribution`) VALUES (:sessionKey, :nameParticipant, :contribution)');
                $addSessionPart ->execute([
                    'sessionKey' => htmlspecialchars(strip_tags($_SESSION['sessionActive'])),
                    'nameParticipant' => htmlspecialchars(strip_tags($_POST['part']['name'.$i])),
                    'contribution' => htmlspecialchars(strip_tags($_POST['part']['contribution'.$i]))
                ]);
                
            }

        }
    }

    if(isset($_POST['task']['nameTask0']) && isset($_POST['task']['dateFrom0']) && isset($_POST['task']['dateTo0']) && isset($_POST['task']['selectPersonnel0']) || !isset($_POST['task']['nameTask0']) && !isset($_POST['task']['dateFrom0']) && !isset($_POST['task']['dateTo0']) && !isset($_POST['task']['selectPersonnel0'])){
        $removeSessionTask = $mySqlConnection -> prepare('DELETE FROM agenda WHERE sessionKey = (:sessionKey)');
        $removeSessionTask-> execute([
            'sessionKey' => $_SESSION['sessionActive']
        ]);

        if(isset($_POST['task']['nameTask0']) && isset($_POST['task']['dateFrom0']) && isset($_POST['task']['dateTo0']) && isset($_POST['task']['selectPersonnel0'])){
            $countTask = count($_POST['task'])/4;


            for($i = 0; $i < $countTask; $i++){
                $addSessionTask = $mySqlConnection-> prepare('INSERT INTO agenda (`taskName`, `dateFrom`, `dateTo`, `personnel`, `sessionKey`) VALUES (:taskName, :dateFrom, :dateTo, :personnel, :sessionKey)');
                $addSessionTask->execute([
                    'taskName' => htmlspecialchars(strip_tags($_POST['task']['nameTask'.$i])),
                    'dateFrom' => htmlspecialchars(strip_tags($_POST['task']['dateFrom'.$i])),
                    'dateTo' => htmlspecialchars(strip_tags($_POST['task']['dateTo'.$i])),
                    'personnel' => htmlspecialchars(strip_tags($_POST['task']['selectPersonnel'.$i])),
                    'sessionKey' => htmlspecialchars(strip_tags($_SESSION['sessionActive']))
                ]);
            }
        }
    }

}else{
    
    $connected = false;
    header("Refresh:3; url=index.php");
}

?>