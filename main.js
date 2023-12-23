'use strict';

//Declaration variables..
let buttonAdd = document.getElementById('buttonAdd');
let buttonParticipant = document.getElementById('buttonParticipant');
let localItems;
let localParticipants;
let addTotal;
let buttonDelSession = document.querySelector('#boutonSession');
let buttonDelAccount = document.querySelector('#boutonCompte');
let formDelAccount = document.querySelector('.formAccount');
let formDelSession = document.querySelector('.formSession');

let NoSavedItems;

//Recuperation form "Ma liste"..
let nameItem = document.getElementById('name');
let valueItem = document.getElementById('value');
let qteItem = document.getElementById('qte');

//Recuperation form "Participants"..
let namePart = document.getElementById('inputParticipant');

//Date actuelle pour creation d'item..
let date = new Date()
let year = date.getFullYear().toString();
let month = (date.getMonth()+1).toString();
let day = date.getDate().toString();

//Recuperation form "to-do"..
const nameTask = document.getElementById('task');
const dateFrom = document.getElementById('dateFrom');
const dateTo = document.getElementById('dateTo');
const selectPersonnel = document.getElementById('personnel');
const buttonTask = document.getElementById('addTask');
const divPreLoad = document.getElementById('pre-load');
let localList = [];
//Creation objet type pour to-do..
let newTask = {
    name: '',
    from: '',
    to: '',
    personnel: []
}

//Contiendra les info des items a envoyer en base avec la fonction savingItems()..
let savedForm = document.querySelector('#formToSave');


//______________________________________________Chargement et affichage des items depuis le serveur..
function loadDoc(){

    if(localItems.length == 0){

        let p = document.createElement('p');
        p.style.textAlign = 'center';
        p.innerText = 'Aucun élément pour le moment.';
        document.getElementById('items').appendChild(p);

        addTotal = 0;

    }else{

        let table = document.createElement('table');
        table.style.width = '100%';
        document.getElementById('items').appendChild(table);
        let body = document.createElement('tbody');
        table.appendChild(body);

        let footer = document.createElement('tfoot');
        table.appendChild(footer);
        let totalList = document.createElement('tr');
        totalList.style.background = 'rgb(200, 252, 208)'

        let keyTotal = document.createElement('td');
        keyTotal.setAttribute('colspan', "2");
        keyTotal.style.textAlign = 'center';
        keyTotal.innerText = 'Total';
        totalList.appendChild(keyTotal);

        let numTotal = document.createElement('td');
        numTotal.setAttribute('colspan', "2");
        numTotal.style.textAlign = 'center';
        totalList.appendChild(numTotal);

        addTotal = 0;
        
        let indexItem = 0;

        for(let elements of localItems){

            addTotal = addTotal + (elements['price'] * elements['qte']);

            let row = document.createElement('tr');
            body.appendChild(row);
            

            for(let i = 0; i <= 4; i++){

                if(i == 0){

                    let cellule = document.createElement('td');
                    cellule.innerText = elements[i];
                    row.appendChild(cellule);

                }else if(i == 1){

                    let cellule = document.createElement('td');
                    cellule.innerText = elements[i];
                    row.appendChild(cellule);

                }else if(i == 2){

                    let cellule = document.createElement('td');
                    cellule.innerText = elements[i]+"€";
                    row.appendChild(cellule);

                }else if(i == 3){

                    let cellule = document.createElement('td');
                    cellule.innerText = "x"+elements[i];
                    row.appendChild(cellule);

                }else if(i == 4){

                    if(typeof(moderator) != 'undefined'){
                        let tdcell = document.createElement('td');
                        let cellule = document.createElement('div');
                        cellule.setAttribute('class', 'delete');
                        cellule.setAttribute('value', indexItem);

                        //Ecouteur d'evenement..
                        cellule.addEventListener('click', function(){
                            let valueOf = cellule.getAttribute('value');
                            deleteItem(valueOf);
                        })
                        row.appendChild(tdcell);

                        let img = document.createElement('img');
                        img.setAttribute('src', 'img/fermer.png');
                        img.setAttribute('alt', 'Supprimer l\'item');
                        img.style.width = '100%';
                        img.style.height = '100%';
                        cellule.appendChild(img);
                        tdcell.appendChild(cellule);
                    }

                }
            }
            indexItem++;
        }
        numTotal.innerText = `${addTotal}€`;
        footer.appendChild(totalList);
    }
        
}

//Configuration d'une tâche..
if(typeof nameTask != null && typeof dateFrom != null && typeof dateTo != null && selectPersonnel != null){

    nameTask.addEventListener('blur', function(e){
        newTask.name =  e.target.value;
        document.getElementById('task-name').innerHTML = '<span class="legend">Nom: </span>' + e.target.value;
    });
    
    dateFrom.addEventListener('blur', function(e){
        if(newTask.to > e.target.value || newTask.to == ''){
            newTask.from = e.target.value;
            document.getElementById('taskFrom').innerHTML = '<span class="legend">De: </span>' + e.target.value;
        }else{
            document.getElementById('taskFrom').innerHTML = '<span class="legend">Il y a une erreur avec les horaires </span>';
        }
        
    });
    
    dateTo.addEventListener('blur', function(e){
        if(newTask.from < e.target.value && newTask.from != ''){
            newTask.to = e.target.value;
            document.getElementById('taskTo').innerHTML = '<span class="legend">À: </span>' + e.target.value;
        }else{
            document.getElementById('taskTo').innerHTML = '<span class="legend">Il y a une erreur avec les horaires</span>';
        }
        
    });
    
    selectPersonnel.addEventListener('blur', function(e){
    
        if(e.target.value != '' && !newTask.personnel.includes(e.target.value)){
            newTask.personnel.push(e.target.value);
            document.getElementById('taskPersonnel').innerHTML = '<span class="legend">Avec: </span>' + newTask.personnel.map((perso) => `<span class='delPerso'> ${perso}</span>`);
            suppPreload();
    
        }
    });
}


//Suppression d'un élément..
function suppPreload(){
    let delPerso = document.querySelectorAll('.delPerso');

    for (let i = 0; i < delPerso.length; i++) {
        delPerso[i].addEventListener('click', function(){
            newTask.personnel.splice(i, 1);
            document.getElementById('taskPersonnel').innerHTML = '<span class="legend">Avec: </span>' + newTask.personnel.map((perso) => `<span class='delPerso'> ${perso}</span>`);
            suppPreload();
        });
    }
}
    
function loadTask(){
    document.getElementById('to-do-list').innerHTML = '';
    let ind = 0;
    if(localList.length == 0){
        let pEmpty = document.createElement('p');
        pEmpty.innerText = 'Aucune tâche pour le moment';
        pEmpty.style.textAlign = 'center';
        document.getElementById('to-do-list').appendChild(pEmpty);
        
    }
    for (const task of localList) {

        let container = document.createElement('div');
        container.setAttribute('class', 'cardTask');

        let infoContainer = document.createElement('div');
        infoContainer.setAttribute('class', 'infoContainer');

        let titleTask = document.createElement('h5');
        titleTask.innerText = task.nameTask;

        let infoDiv = document.createElement('div');
        infoDiv.setAttribute('class', 'info');

        let pTime = document.createElement('div');
        pTime.setAttribute('class', 'time');

        let pFrom = document.createElement('p');
        pFrom.innerText = 'De: ' + task.dateFrom;

        let pTo = document.createElement('p');
        pTo.innerText = 'À: ' + task.dateTo;

        let pSelect = document.createElement('p');
        if(typeof task.selectPersonnel == 'string'){
            let a = task.selectPersonnel.split(',');
            pSelect.innerHTML = 'Avec: ' + a.map((perso) => ` ${perso}`);
        }else{
            pSelect.innerHTML = 'Avec: ' + task.selectPersonnel.map((perso) => ` ${perso}`);
        }
        

        let timeContainer = document.createElement('div');
        timeContainer.setAttribute('class', 'timeContainer');

        let pLabelTime = document.createElement('p');

        let pCurrentTime = document.createElement('p');
        const from = task.dateFrom.split(':');
        const hrFrom = parseInt(from[0]);
        const mnFrom = parseInt(from[1]);

        const to = task.dateTo.split(':');
        const hrTo = parseInt(to[0]);
        const mnTo = parseInt(to[1]);
        convertTime(container, pLabelTime, pCurrentTime, hrFrom, mnFrom, hrTo, mnTo);
        setInterval(()=>convertTime(container, pLabelTime, pCurrentTime, hrFrom, mnFrom, hrTo, mnTo), 5000)
        

        let suppr;
        if(typeof moderator != 'undefined'){
            suppr = document.createElement('p');
            suppr.setAttribute('data', ind);
            suppr.innerText = 'Supprimer';
            suppr.addEventListener('click', function(){
                localList.splice(suppr.getAttribute('data'), 1);
                localStorage.setItem(`${NoSavedItems}_toDo`, JSON.stringify(localList));
                loadTask();
            });
        }
        

        document.getElementById('to-do-list').appendChild(container);
        infoContainer.appendChild(titleTask);
        infoContainer.appendChild(infoDiv);
        container.appendChild(infoContainer);
        container.appendChild(timeContainer);
        timeContainer.appendChild(pLabelTime);
        timeContainer.appendChild(pCurrentTime);
        if(typeof moderator != 'undefined'){
            timeContainer.appendChild(suppr);
        }
        pTime.appendChild(pFrom);
        pTime.appendChild(pTo);
        infoDiv.appendChild(pTime);
        infoDiv.appendChild(pSelect);
        
        ind++
    }
}

function convertTime(container, pLabelTime, pCurrentTime, hrFrom, mnFrom, hrTo, mnTo){

    const currentTime = new Date();
    const curHr = currentTime.getHours();
    const curMn = currentTime.getMinutes();

    const currTime = (curHr * 60) + curMn;

    const fromTime = (hrFrom * 60) + mnFrom;

    const toTime = (hrTo * 60) + mnTo;

    if(currTime < fromTime){

        const subTime = fromTime - currTime;
        const restHr = subTime / 60;
        const restMin = subTime % 60;

        pLabelTime.innerText = 'Commence dans';
        pCurrentTime.innerHTML = `${Math.floor(restHr)}:${restMin < 10 ? '0' + restMin : restMin}`;

    }else if(currTime >= fromTime && currTime < toTime){

        const subTime = toTime - currTime;
        const restHr = subTime / 60;
        const restMin = subTime % 60;

        pLabelTime.innerText = 'Termine dans';
        pCurrentTime.innerHTML = `${Math.floor(restHr)}:${restMin < 10 ? '0' + restMin : restMin}`;

    }else if( currTime > toTime){

        pCurrentTime.innerText = 'Terminée';
        container.style.opacity = '0.5';
    }    
}

//Creation d'un item..
function addItem(){

    let postTrimName = nameItem.value;
    let postTrimValue = valueItem.value;
    let postTrimQte = qteItem.value;

    if(postTrimName.trim() != '' && postTrimValue.trim() != '' && postTrimQte.trim() != ''){

        let newItem = {0: `${year}-${month.padStart(2,0)}-${day.padStart(2,0)}`, 1: `${nameItem.value}`, 2: `${valueItem.value}`, 3: `${qteItem.value}`, date: `${year}-${month.padStart(2,0)}-${day.padStart(2,0)}`, title: `${nameItem.value}`, price: `${valueItem.value}`, qte: `${qteItem.value}`}

        localItems.push(newItem);

        nameItem.value = '';
        valueItem.value = '';
        qteItem.value = 1;

        localStorage.setItem(NoSavedItems, JSON.stringify(localItems));

        document.getElementById('items').innerText = '';
        document.getElementById('participants').innerText = '';
        loadDoc();
        loadParticipants();
    }
}

//_______________________________________________________________________Envoie des donnees en base.

//Sauvergarde aussi les participants...
function savingItems(){
    let indexA = 0;
        
    for(let element of localItems){

        let dateInput = document.createElement('input');
        dateInput.setAttribute('name', `items[date${indexA}]`);
        dateInput.setAttribute('value', element['date']);
        dateInput.setAttribute('type', 'hidden');

        let nameInput = document.createElement('input');
        nameInput.setAttribute('name', `items[title${indexA}]`);
        nameInput.setAttribute('value', element['title']);
        nameInput.setAttribute('type', 'hidden');

        let priceInput = document.createElement('input');
        priceInput.setAttribute('name', `items[price${indexA}]`);
        priceInput.setAttribute('value', element['price']);
        priceInput.setAttribute('type', 'hidden');

        let qteInput = document.createElement('input');
        qteInput.setAttribute('name', `items[qte${indexA}]`);
        qteInput.setAttribute('value', element['qte']);
        qteInput.setAttribute('type', 'hidden');

        savedForm.appendChild(dateInput);
        savedForm.appendChild(nameInput);
        savedForm.appendChild(priceInput);
        savedForm.appendChild(qteInput);

        indexA++;
    }

    let indexB = 0;
    for(let element of localParticipants){

        let nameInput = document.createElement('input');
        nameInput.setAttribute('name', `part[name${indexB}]`);
        nameInput.setAttribute('value', element['nameParticipant']);
        nameInput.setAttribute('type', 'hidden');

        let contributionInput = document.createElement('input');
        contributionInput.setAttribute('name', `part[contribution${indexB}]`);
        contributionInput.setAttribute('value', element['contribution']);
        contributionInput.setAttribute('type', 'hidden');

        savedForm.appendChild(nameInput);
        savedForm.appendChild(contributionInput);

        indexB++;
    }

    let indexC = 0;
    for(let element of localList){

        let nameInput = document.createElement('input');
        nameInput.setAttribute('name', `task[nameTask${indexC}]`);
        nameInput.setAttribute('value', element.nameTask);
        nameInput.setAttribute('type', 'hidden');

        let dateInputA = document.createElement('input');
        dateInputA.setAttribute('name', `task[dateFrom${indexC}]`);
        dateInputA.setAttribute('value', element.dateFrom);
        dateInputA.setAttribute('type', 'hidden');

        let dateInputB = document.createElement('input');
        dateInputB.setAttribute('name', `task[dateTo${indexC}]`);
        dateInputB.setAttribute('value', element.dateTo);
        dateInputB.setAttribute('type', 'hidden');

        let selectInput = document.createElement('input');
        selectInput.setAttribute('name', `task[selectPersonnel${indexC}]`);
        if(typeof element.selectPersonnel == 'string'){
            selectInput.setAttribute('value', element.selectPersonnel)
        }else{
            selectInput.setAttribute('value', `${element.selectPersonnel.map((perso) => `${perso}`)}`)
        }
        selectInput.setAttribute('type', 'hidden');

        savedForm.appendChild(nameInput);
        savedForm.appendChild(dateInputA);
        savedForm.appendChild(dateInputB);
        savedForm.appendChild(selectInput);

        indexC++;
    }
    
};

function deleteItem(valueOf){

    localItems.splice(valueOf,1);
    localStorage.setItem(NoSavedItems, JSON.stringify(localItems));

    document.getElementById('items').innerText = '';
    document.getElementById('participants').innerText = '';
    loadDoc();
    loadParticipants();

}

//_________________________________________Section participants____________________________________________..
function loadParticipants(){

    if(localParticipants.length == 0){

        let p = document.createElement('p');
        p.style.textAlign = 'center';
        p.innerText = 'Aucun participant pour le moment.';
        document.getElementById('participants').appendChild(p);

    }else{

        let table = document.createElement('table');
        table.style.width = '100%';
        document.getElementById('participants').appendChild(table);
        let body = document.createElement('tbody');
        table.appendChild(body);

        let footer = document.createElement('tfoot');
        table.appendChild(footer);
        let totalList = document.createElement('tr');
        totalList.style.background = 'rgb(200, 252, 208)'

        let keyTotal = document.createElement('td');
        keyTotal.setAttribute('colspan', "2");
        keyTotal.style.textAlign = 'center';
        keyTotal.innerText = 'Reste à payer';
        totalList.appendChild(keyTotal);

        let numTotal = document.createElement('td');
        numTotal.setAttribute('colspan', "2");
        numTotal.style.textAlign = 'center';
        totalList.appendChild(numTotal);


        let deducTotal = addTotal;
        
        let indexItem = 0;

        let quotient;

        if(localItems.length > 0){
            quotient = deducTotal / localParticipants.length;
        }else{
            quotient = 0;
        }

        for(let elements of localParticipants){

            let row = document.createElement('tr');
            body.appendChild(row);

            for(let i = 0; i <= 4; i++){

                if(i == 0){

                    let cellule = document.createElement('td');
                    cellule.innerText = elements[i];
                    row.appendChild(cellule);

                }else if(i == 1){

                    let cellule = document.createElement('td');
                    if(elements[i] <= 0){

                        cellule.style.color = 'rgb(255, 111, 111)';

                    }else if(elements[i] > 0 && elements[i] < quotient){

                        cellule.style.color = '#5588D4';

                    }else if(elements[i] == quotient){

                        cellule.style.color = 'rgb(93, 175, 111)';

                    }else if(elements[i] > quotient){

                        cellule.style.color = 'rgb(199, 167, 27)';
                        
                    }
                    cellule.innerText = `Part: ${elements[i]}€`;
                    row.appendChild(cellule);
                    if(elements[i] != 0){
                        deducTotal = deducTotal - elements[i];
                    }

                }else if(i == 2){

                    let cellule = document.createElement('td');
                    cellule.innerText = `/${quotient.toFixed(2)}€`;
                    row.appendChild(cellule);

                }else if(i == 3){

                    let cellule = document.createElement('td');
                    let input = document.createElement('input');
                    input.setAttribute('type', 'number');
                    input.setAttribute('id', indexItem);
                    input.style.textAlign = 'center';
                    input.style.margin = 'auto';
                    input.style.width = '80%';
                    if(typeof(moderator) != 'undefined'){
                        input.addEventListener('blur', function(){
                            if(input.value != ''){
                                document.getElementById('participants').innerText = '';
                                localParticipants[input.getAttribute('id')][1] = input.value;
                                localParticipants[input.getAttribute('id')]['contribution'] = input.value;
                                localStorage.setItem(`${NoSavedItems}_part`, JSON.stringify(localParticipants));
                                loadParticipants();
                            }
                        });
                    }
                    cellule.appendChild(input);
                    row.appendChild(cellule);

                }else if(i == 4){

                    if(typeof(moderator) != 'undefined'){
                        let tdcell = document.createElement('td')
                        let cellule = document.createElement('div');
                        cellule.setAttribute('class', 'delete');
                        cellule.setAttribute('value', indexItem);

                        //Ecouteur d'evenement..
                        cellule.addEventListener('click', function(){
                            let valueOf = cellule.getAttribute('value');
                            deletePart(valueOf);
                        })
                        row.appendChild(tdcell);

                        let img = document.createElement('img');
                        img.setAttribute('src', 'img/fermer.png');
                        img.setAttribute('alt', 'Supprimer l\'item');
                        img.style.width = '100%';
                        cellule.appendChild(img);
                        tdcell.appendChild(cellule);
                    }

                }
            }
            indexItem++;
        }
        numTotal.innerText = `${deducTotal.toFixed(2)}€`;
        footer.appendChild(totalList);
    }

}

function deletePart(valueOf){

    localParticipants.splice(valueOf,1);
    localStorage.setItem(`${NoSavedItems}_part`, JSON.stringify(localParticipants));

    document.getElementById('participants').innerText = '';

    loadParticipants();
}

function addPart(){

    let postTrimName = namePart.value;

    if(postTrimName.trim() != ''){

        let newpart = {"0": postTrimName, "1": "0", "nameParticipant": postTrimName, "contribution": "0"}

        localParticipants.push(newpart);

        namePart.value = '';

        localStorage.setItem(`${NoSavedItems}_part`, JSON.stringify(localParticipants));

        document.getElementById('participants').innerText = '';

        loadParticipants();
    }
}

//Chargement DOM_________________________________________________________________
window.addEventListener('DOMContentLoaded', function(){

    //Recuperation des infos de connection pour créer localStorage perso..
     const infoUser = document.getElementById('nameUserActive');
     const infoSession = document.getElementById('nameSessionActive');
     localStorage.setItem('infoConnection', `${infoUser.textContent}+${infoSession.textContent}`);
     NoSavedItems = localStorage.getItem('infoConnection');


    //Conversion de la BDD pour les items et participants de json en un object traitable..
    if(localStorage.getItem(NoSavedItems)){
        localItems = JSON.parse(localStorage.getItem(NoSavedItems));
    }else{
        localItems = JSON.parse(elements);
    };

    if(localStorage.getItem(`${NoSavedItems}_part`)){
        localParticipants = JSON.parse(localStorage.getItem(`${NoSavedItems}_part`));
    }else{
        localParticipants = JSON.parse(peoples);
    }

    if(localStorage.getItem(`${NoSavedItems}_toDo`)){
        localList = JSON.parse(localStorage.getItem(`${NoSavedItems}_toDo`))
    }else{
        localList = JSON.parse(toDoList);
    } 
    loadDoc();

    loadParticipants();

    loadTask();

    //Ajout d'un item..
    if(buttonAdd != null){
        buttonAdd.addEventListener('click', function(){

            addItem();

        });
    };

    //Création d'une tâche..
    if(buttonTask != null){

        buttonTask.addEventListener('click', function(){

            let trimName = newTask.name;
            let trimDateFrom = newTask.from;
            let trimDateTo = newTask.to;
            let trimselect = newTask.personnel;
    
            if(trimName.trim() != '' && trimDateFrom.trim() != '' && trimDateTo.trim() != '' && trimselect.length != 0){
               let newToDo = {nameTask: trimName, dateFrom: trimDateFrom, dateTo: trimDateTo, selectPersonnel: trimselect};
               localList.push(newToDo);
               console.log(localList);
               localStorage.setItem(`${NoSavedItems}_toDo`, JSON.stringify(localList));
    
               loadTask();
    
               //On efface les contenus d'informations de la tâche en configuration..
               newTask.name = '';
               newTask.from = '';
               newTask.to = '';
               newTask.personnel = '';
               nameTask.value = '';
               dateFrom.value = '';
               dateTo.value = '';
               selectPersonnel.value = '';
               document.getElementById('task-name').innerHTML = '';
               document.getElementById('taskFrom').innerHTML = '';
               document.getElementById('taskTo').innerHTML = '';
               document.getElementById('taskPersonnel').innerHTML = '';
            }
        });

    }

    //Avertissement de sauvegarde..
    let set = false;
    setInterval(() => {avertissement()},1000);

    function avertissement(){
        if(localStorage.getItem(NoSavedItems) || localStorage.getItem(`${NoSavedItems}_part`) || localStorage.getItem(`${NoSavedItems}_toDo`)){
            if(!set){
                let exclam = document.createElement('img');
                exclam.setAttribute('src', 'img/exclamation.png');
                exclam.setAttribute('alt', 'Sauvegarde possible');
                exclam.setAttribute('class', 'attention');
                document.getElementById('formToSave').appendChild(exclam);
                set = true;
            }
           

        }
    }

    //Evenement de sauvegarde..
    let confirmationSave = false;
    let pressSave = document.getElementById('save');

    if(pressSave != null){
        pressSave.addEventListener('click', function(e){
            if(localStorage.getItem(NoSavedItems) || localStorage.getItem(`${NoSavedItems}_part`) || localStorage.getItem(`${NoSavedItems}_toDo`)){
                if(confirmationSave){
                    savingItems();
                }else{
                    e.preventDefault();
                    pressSave.style.color = 'rgb(255, 78, 78)';
                    confirmationSave = true;
                    setTimeout(() => {
                        pressSave.style.color = '#5588D4';
                        confirmationSave = false;
                    }, 1000)
                }

            }else{
                e.preventDefault();
            };
        });
    };

    let pressDownload = document.getElementById('download');

    //Evenement de synchronisation..
    if(pressDownload  != null){
        let confirmationSynchro = false;
        pressDownload.addEventListener('click', function(e){
            if(localStorage.getItem(NoSavedItems) || localStorage.getItem(`${NoSavedItems}_part`) || localStorage.getItem(`${NoSavedItems}_toDo`)){
                if(confirmationSynchro){
                    localStorage.removeItem(NoSavedItems);
                    localStorage.removeItem(`${NoSavedItems}_part`);
                    localStorage.removeItem(`${NoSavedItems}_toDo`)
                    window.location.reload();
                }else{
                    pressDownload.style.color = 'rgb(255, 78, 78)';
                    confirmationSynchro = true;
                    setTimeout(() => {
                        pressDownload.style.color = '#5588D4';
                        confirmationSynchro = false;
                    }, 1000);
                }
            }else{
                e.preventDefault();
            };
        })
    }

    //Ajout de participants..
    if(buttonParticipant != null){
        buttonParticipant.addEventListener('click', function(){
            addPart();
        });
    };

    //Timing msg erreur..
    let erreurMsg = document.querySelectorAll('.alert');
    for (const error of erreurMsg) {
        if( error.childElementCount != 0){
            setTimeout(() => {
                error.style.display = 'none';
        }, 3000);
        }else{
            error.style.display = 'none';
        }
    }

    //Affichage mdp pour confirmation de suppr..
    const indicatorSwitch = document.querySelectorAll('.indicatorSwitch');

    for (let targetedInput of indicatorSwitch){
        targetedInput.addEventListener('click', function(){
            if(targetedInput.previousSibling.getAttribute('type') == 'password'){
                targetedInput.previousSibling.setAttribute('type', 'text');
                const lock = targetedInput.childNodes;
                lock[0].setAttribute('src', 'img/cadenas-deverrouille.png');
            }else{
                targetedInput.previousSibling.setAttribute('type', 'password');
                const lock = targetedInput.childNodes;
                lock[0].setAttribute('src', 'img/cadenas-verrouille.png');
            };
        });
    }

    if(buttonDelAccount != null && buttonDelSession != null){

    //Delete compte..
    buttonDelAccount.addEventListener('click', function(e){

        if(formDelAccount.getAttribute('class') == 'formAccount'){
            e.preventDefault();
            formDelAccount.classList.add('formConfAccount');
        }else if(formDelAccount.getAttribute('class') == 'formAccount formConfAccount' && document.querySelector('.formAccount div input').value == ""){
            e.preventDefault();
            formDelAccount.classList.remove('formConfAccount');
        }else if(formDelAccount.getAttribute('class') == 'formAccount formConfAccount' && document.querySelector('.formAccount div input').value != ""){

        }else{
            e.preventDefault();
        }

    });

    //Delete session..
    buttonDelSession.addEventListener('click', function(e){

        if(formDelSession.getAttribute('class') == 'formSession'){
            e.preventDefault();
            formDelSession.classList.add('formConfSession');
        }else if(formDelSession.getAttribute('class') == 'formSession formConfSession' && document.querySelector('.formSession div input').value == ""){
            e.preventDefault();
            formDelSession.classList.remove('formConfSession');
        }else if(formDelSession.getAttribute('class') == 'formSession formConfSession' && document.querySelector('.formSession div input').value != ""){

        }else{
            e.preventDefault();
        }
        

    });

    };

});