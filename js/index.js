import {Snackbar} from './snackbar';

window.addEventListener('load', onInit);

function onInit(){
    initFlash();
}

function initFlash(){
    const flash = document.querySelector('#flash');
    if(flash.textContent.length !== 0){
        Snackbar.show('snackbar',flash.textContent);
        flash.innerHTML='';
    }
}
