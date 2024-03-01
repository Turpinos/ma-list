const passwordInput = document.querySelectorAll('.switchPassword');
const indicatorSwitch = document.querySelectorAll('.indicatorSwitch');
const buttonCrt = document.getElementById('createButton');
const confPass = document.getElementById('confPassword');
const alertDiv = document.querySelectorAll('.alert');
const rgpd = document.querySelector('.rgpd input');

rgpd.addEventListener('click', function(e){
    if(e.target.checked){
        buttonCrt.removeAttribute('class');
    }else{
        buttonCrt.setAttribute('class', 'disabled');
    }
});

buttonCrt.addEventListener('click', function(e){

    if(rgpd.checked){
        if(passwordInput[1].value !== confPass.value){
            e.preventDefault();
            const p = document.createElement('p');
            p.innerText = 'Erreur dans la confirmation du mot de passe';
            alertDiv[1].appendChild(p);
        }
    }else{
        e.preventDefault();
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