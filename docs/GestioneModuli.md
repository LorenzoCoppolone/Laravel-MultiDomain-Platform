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



Ora verrà spiegato brevemente come configurare il framework per aggiungere un nuovo modulo:

1) aprire il file config/auth.php: li si troveranno i "guard" aggiungere i propri, seguendo la struttura del framework.
    Questo farà si che laravel riconoscerà un determinato utente e dunque si potrà fare accesso ad esso dalla sessione.

2) aprire il file bootstrap/app.php qui vi sono direttive di routing, infatti all'interno del package routes bisogna creare il proprio file di rotte dedicato e poi dire al framework tramite questo file che vi è un nuovo file di rotte da considerare,
 qui vi sono anche direttive di reindirizzamento dell'utente in certi casi come un errore 404 o un errore 403, oppure un utente che non è loggato o un utente bannato, configurare il seguente file in modo che rispetti le necessità del proprio modulo.


