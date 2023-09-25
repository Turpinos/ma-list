const passwordInput = document.querySelectorAll('.switchPassword');
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