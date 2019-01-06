import {Snackbar} from './snackbar';

window.addEventListener('load', onInit);

function onInit(){
    initFlash();
    initErrors();
}

function initFlash(){
    const flash = document.querySelector('#flash ul');
    if(flash.textContent.length !== 0){
        Snackbar.show('snackbar',flash.textContent);
        flash.innerHTML='';
    }
}


function initErrors(){
    const errors = document.querySelector('.errorMessages');
    if(errors.textContent.length !== 0){
       setTimeout(()=>{
           errors.innerHTML='';
       },3000);
    }
}
