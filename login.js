const passwordInput = document.querySelectorAll('.switchPassword');
const indicatorSwitch = document.querySelectorAll('.indicatorSwitch');
const buttonCrt = document.getElementById('createButton');
const confPass = document.getElementById('confPassword');
const alertDiv = document.querySelectorAll('.alert');

buttonCrt.addEventListener('click', function(e){
    
    if(passwordInput[1].value !== confPass.value){
        e.preventDefault();
        const p = document.createElement('p');
        p.innerText = 'Erreur dans la confirmation du mot de passe';
        alertDiv[1].appendChild(p);
    }
})

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