import './bootstrap';

import Alpine from 'alpinejs';

// 1. IMPORT THE PERSIST PLUGIN
import persist from '@alpinejs/persist'; 

// 2. REGISTER THE PERSIST PLUGIN WITH ALPINE
// This makes the $persist magic property available for use in custom.js
Alpine.plugin(persist);

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('toastHandler', () => ({
        visible: false,
        message: '',
        type: 'info',
        timeout: null,

        showToast({ message, type = 'info', duration = 3000 }) {
            this.message = message;
            this.type = type;
            this.visible = true;

            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.visible = false, duration);
        },

        init() {
            window.addEventListener('toast', e => this.showToast(e.detail));
        }
    }));
});


Alpine.start();
