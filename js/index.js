import {Snackbar} from './snackbar';

window.addEventListener('load', onInit);

function onInit() {
    initFlash();
    initErrors();
    initDelete();
}

function initFlash() {
    const flash = document.querySelector('#flash ul');
    if (null === flash) return;
    if (flash.textContent.length !== 0) {
        Snackbar.show('snackbar', flash.textContent);
        flash.innerHTML = '';
    }
}

function initErrors() {
    const errors = document.querySelector('.errorMessages');
    if (null === errors) return;
    if (errors.textContent.length !== 0) {
        setTimeout(() => {
            errors.innerHTML = '';
        }, 3000);
    }
}

function initDelete() {
    const deleteButtons = document.getElementsByClassName('delete');
    const dialog = document.querySelector('#dialog');
    if (!dialog.showModal) {
        dialogPolyfill.registerDialog(dialog);
    }


    if (null === deleteButtons) return;
    for (let deleteButton of deleteButtons) {
        deleteButton.onclick = (event) => {
            event.preventDefault();
            const href = event.target.parentNode.attributes['href'];

            document.querySelector('#cron-url').textContent = href.textContent;
            dialog.showModal();

        }

        dialog.querySelector('#dialog-cancel')
            .addEventListener('click', function () {
                dialog.close();
            });

        dialog.querySelector('#dialog-ok')
            .addEventListener('click', function () {

                const url = document.querySelector('#cron-url').textContent;
                Response.redirect(url, 302);
                window.location = url;
                dialog.close();
            });
    }
}
