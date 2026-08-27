import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',

                // css Studyroom
                'resources/css/studyroom/styleLayout.css',
                'resources/css/studyroom/styleHome.css',
                'resources/css/studyroom/styleCarica.css',
                'resources/css/studyroom/styleRicercaMateriale.css',
                'resources/css/studyroom/styleProfiloUtente.css',
                'resources/css/studyroom/styleModificaProfilo.css',
                'resources/css/studyroom/styleBanned.css',
                'reources/css/studyroom/styleRecensioniMateriale.css',
                'resources/css/studyroom/styleRecensioniUtente.css',
                'resources/css/studyroom/styleAssistenza.css',
                'resources/css/studyroom/styleAdmin.css',
                'resources/css/studyroom/styleDettagliMateriale.css',
                'resources/css/studyroom/styleForm.css',
                'resources/css/studyroom/styleInfo.css',
                'resources/css/studyroom/styleMotivi.css',

                // js Studyroom
                'resources/js/studyroom/upload.js',
                'resources/js/studyroom/modificaProfilo.js',
                'resources/js/studyroom/validazione.js',
                'resources/js/studyroom/ajax.js',


                // css Components
                'resources/css/components/alert-box.css',
                'resources/css/components/alert.css',
                'resources/css/components/btn-pill.css',
                'resources/css/components/checkbox.css',
                'resources/css/components/combo-box.css',
                'resources/css/components/file-upload.css',
                'resources/css/components/form-input.css',
                'resources/css/components/hero-search.css',
                'resources/css/components/material-card.css',
                'resources/css/components/material-filter.css',
                'resources/css/components/pagination.css',
                'resources/css/components/popup.css',
                'resources/css/components/profile-header.css',
                'resources/css/components/profile-input.css',
                'resources/css/components/profile-photo-upload.css',
                'resources/css/components/search-form.css',
                'resources/css/components/star-rating.css',
                'resources/css/components/status-icon.css',
                'resources/css/components/user-avatar.css',

                // js components
                'resources/js/components/popup.js',


                
                // aggiungi qui i tuoi file css e js
                
            ],
            refresh: true,
        }),
    ],
});
