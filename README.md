Laravel Multi‑Domain Platform
Piattaforma Laravel strutturata per gestire più moduli, inizialmente vi sarà solo il modulo studyroom che permette la condivione di materiale didattico tra studenti dell'università

Avviare il progetto

1. Installazione dipendenze
bash
composer install

2. Configurazione ambiente
bash
cp .env.example .env
Configura il database nel file .env.

3. Migrazioni
Ogni dominio ha le proprie migrazioni in:

database/migrations/<dominio>
Esempio per Studyroom:

bash
php artisan migrate --path=database/migrations/studyroom
Esempio per Affitti:

bash
php artisan migrate --path=database/migrations/affitti
Documentazione
La documentazione completa dell’architettura si trova nella cartella:

docs/
GestioneModuli.md — struttura del progetto e come lavorare con i moduli