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
Nota: Fatto questo la migration viene creata all'interno di database/migrations dunque bisognerebbe creare una cartella <NomeModulo>, e mettere il file al suo interno in modo da mantenere coerenza e separazione tra moduli, una volta concluso questo per generare il database è sufficiente lanciare i seguenti comandi:
php artisan migrate     (effettua la migrazione delle tabelle generiche)
php artisan migrate --path=database/migrations/<NomeModulo>     (effettua la migrazione delle tabelle del dominio di interesse)

Nota: analogamente il roolback necessita della stessa logica: php artisan migrate:rollback --path=....



Configurazione del Framework per un Nuovo Modulo

Ora verrà spiegato brevemente come configurare il framework per "accendere" il nuovo modulo appena creato:

1) Configurare i Guard (config/auth.php)
Aprire il file e aggiungere i propri providers (associandoli al nuovo Model appena creato) e i nuovi guards (associandoli al provider). Seguendo la struttura del framework, questo farà sì che Laravel riconoscerà un determinato utente in modo isolato e si potrà fare accesso ad esso dalla sessione tramite Auth::guard('nome_guard').

2) Configurare il Routing e le Eccezioni (bootstrap/app.php)
All'interno del package routes/ bisogna creare il proprio file di rotte dedicato (es. affitti.php). Dopodiché, nel file bootstrap/app.php, dire al framework che vi è un nuovo file di rotte da considerare (utilizzando un prefisso URL dedicato per evitare conflitti).
In questo stesso file vi sono anche direttive di reindirizzamento dell'utente in certi casi (errore 404, 403, utente non loggato o bannato); configurare il file in modo che rispetti le necessità del proprio modulo.

3) Configurare la compilazione degli Asset (vite.config.js)
Affinché i file CSS e JS inseriti nelle nuove cartelle (es. resources/css/affitti/) vengano elaborati correttamente e resi disponibili alle viste Blade, è obbligatorio registrarli. Aprire il file vite.config.js nella root del progetto e aggiungere i percorsi dei nuovi file all'interno dell'array input. Nei template Blade, tali risorse andranno poi richiamate con la direttiva @vite(['resources/css/affitti/tuo-file.css']).