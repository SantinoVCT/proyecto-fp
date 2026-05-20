import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    // connect() {
    //     const cookieModal = document.getElementById('cookieModal');
    //     const acceptButton = cookieModal?.querySelector('button');

    //     if (!cookieModal || !acceptButton) {
    //         return;
    //     }

    //     // Mostrar modal si no hay consentimiento
    //     if (!window.localStorage.getItem('cookieConsent')) {
    //         cookieModal.classList.remove('d-none');
    //     }

    //     // Listener directo en el botón
    //     acceptButton.addEventListener('click', () => {
    //         window.localStorage.setItem('cookieConsent', 'accepted');
    //         cookieModal.classList.add('d-none');
    //     });
    // }
}