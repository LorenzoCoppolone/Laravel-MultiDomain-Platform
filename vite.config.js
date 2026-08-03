import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',

                // Aggiungi qui tutti i CSS del tuo modulo
                'resources/css/studyroom/styleLayout.css',
                'resources/css/studyroom/styleHome.css',
                'resources/css/components/search-form.css',
                'resources/css/components/user-avatar.css',
                'resources/css/components/form-input.css',
                'resources/css/components/profile-input.css',
                'resources/css/components/profile-photo-upload.css',
                'resources/css/components/btn-pill.css',
                'resources/css/studyroom/styleModificaProfilo.css',
                'resources/js/studyroom/modificaProfilo.js'
            ],
            refresh: true,
        }),
    ],
});
