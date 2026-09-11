# Gestione del codice
Il codice viene diviso in sotto cartelle per rendere separati i vari moduli dell'applicazione, in particolare l'idea è la seguente:
Conoscendo come il framework laravel lavora, si va ad operare al suo interno mettendo package dentro quelli principali di laravel: 
models/<NomeModulo>
controller/<NomeModulo>
.....
Questo per ogni modulo presente, la struttura sarà ad esempio, supponendo di aggiungere un modulo chiamato Affitti, la seguente:

app/
  Models/
    Studyroom/
      Materiale.php
      ......
    Affitti/
      Appartamento.php
      ......

  Http/
    Controllers/
      Studyroom/
        MaterialeController.php
        .....
      Affitti/
        AppartamentoController.php
        .....

database/
  migrations/
    studyroom/
      2026_07_30_000000_create_materiali_table.php
      .....
    affitti/
      2026_07_30_000001_create_Appartamenti_table.php
      .....

resources/
  views/
    layouts/
      app.blade.php
      .....

    components/
      button.blade.php
      card.blade.php
      hero.blade.php
      search.blade.php
      upload-box.blade.php
      .....

    studyroom/
      homepage.blade.php
      upload.blade.php
      search.blade.php
      .....

    affitti/
      index.blade.php
      create.blade.php
      .....

  css/
    app.css
    studyroom/
        ......
    affitti/
        ......

routes/
  web.php
  studyroom.php
  affitti.php


Poiché questo progetto prevede l'integrazione di più moduli bisogna mantenere coerente la UI, per questo motivo all'interno del package
resources/css/components/... troviamo tutto il css "comune" mentre dentro resources/views/components troviamo i template, ciò significa che prima di sviluppare il proprio modulo e bene studiare cosa vi è all'interno.

Per generare questa struttura è sufficiente lanciare da terminale i seguenti comandi:
php artisan make:controller <NomeModulo>/<Nome>Controller
ad esempio nel caso di studyroom per generare MaterialeController: php artisan make:controller Studyroom/MaterialeController

per generare il model: php artisan make:model <NomeModulo>/<Classe> 
ad esempio nel caso di studyroom: php artisan make:model Studyroom/Materiale

per creare la migration è consigliato crearla insieme al model in modo da semplificare la gestione e dunque lanciare il comando
php artisan make:model <NomeModulo>/<Classe> -m
nel caso di studyroom: php artisan make:model Studyroom/Materiale -m

Fatto questo la migration viene creata all'interno di database/migrations dunque bisogna creare una cartella <NomeModulo>, e mettere i file al suo interno in modo da mantenere coerenza e separazione tra moduli come fatto per studyroom.

# IMPORTANTE:
# Guida all'Estensione Multi-Database per i Moduli
Prima di lanciare la migration è necessario effettuare un paio di accorgimenti per far si che si generino database indipendenti fra loro per ogni migration
---

## 1. Configurazione dell'Ambiente (.env)
Aggiungi le credenziali e il nome del nuovo database all'interno del file `.env` principale del progetto. Ad esempio, per il modulo Affitti:

```env
DB_CONNECTION_AFFITTI=mysql
DB_HOST_AFFITTI=127.0.0.1
DB_PORT_AFFITTI=3306
DB_DATABASE_AFFITTI=affitti_laravel
DB_USERNAME_AFFITTI=root
DB_PASSWORD_AFFITTI=

All'interno del file config\database.php mappare la nuova connessione, nel seguente modo:

'affitti' => [
    'driver' => 'mysql',
    'session' => env('SESSION_DRIVER', 'database'),
    'url' => env('DB_URL_AFFITTI'),
    'host' => env('DB_HOST_AFFITTI', '127.0.0.1'),
    'port' => env('DB_PORT_AFFITTI', '3306'),
    'database' => env('DB_DATABASE_AFFITTI', 'forge'),
    'username' => env('DB_USERNAME_AFFITTI', 'forge'),
    'password' => env('DB_PASSWORD_AFFITTI', ''),
    'unix_socket' => env('DB_SOCKET_AFFITTI', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => null,
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],

Fatto questo per generare il database è sufficiente lanciare i seguenti comandi:
php artisan migrate --database=nome_connection  (effettua la migrazione delle tabelle generiche)
php artisan migrate --database=nome_connection --path=database/migrations/<NomeModulo>     (effettua la migrazione delle tabelle del dominio di interesse)

Attenzione: Per nome_connection si intende il nome inserito nello step precedente dunque quello che trovo in config\database.php

Nota: analogamente il roolback necessita della stessa logica: php artisan migrate:rollback --database=.... --path=....


Configurazione del Framework per un Nuovo Modulo

Ora verrà spiegato brevemente come configurare il framework per "accendere" il nuovo modulo appena creato:

1) Configurare i Guard (config/auth.php)
Aprire il file e aggiungere i propri providers (associandoli al nuovo Model appena creato) e i nuovi guards (associandoli al provider). Seguendo la struttura del framework, questo farà sì che Laravel riconoscerà un determinato utente in modo isolato e si potrà fare accesso ad esso dalla sessione tramite Auth::guard('nome_guard').

2) Configurare il Routing e le Eccezioni (bootstrap/app.php)
All'interno del package routes/ bisogna creare il proprio file di rotte dedicato (es. affitti.php). Dopodiché, nel file bootstrap/app.php, dire al framework che vi è un nuovo file di rotte da considerare (utilizzando un prefisso URL dedicato per evitare conflitti).
In questo stesso file vi sono anche direttive di reindirizzamento dell'utente in certi casi (errore 404, 403, utente non loggato o bannato); configurare il file in modo che rispetti le necessità del proprio modulo.

3) Configurare la compilazione degli Asset (vite.config.js)
Durante lo sviluppo locale (npm run dev), Vite è in grado di servire gli asset "al volo" anche se non dichiarati. Tuttavia, è obbligatorio registrarli per la messa in produzione. Affinché i file CSS e JS (es. resources/css/affitti/) vengano compilati correttamente con il comando npm run build, bisogna aprire il file vite.config.js nella root del progetto e aggiungere i loro percorsi all'interno dell'array input.