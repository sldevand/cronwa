export class Snackbar {
    static show(id, text) {
        const container = document.querySelector('#' + id);
        const data = {
            message: text,
            timeout: 2000,
            actionHandler: () => {
            },
            actionText: 'annuler'
        };
        container.MaterialSnackbar.showSnackbar(data);
    }
}