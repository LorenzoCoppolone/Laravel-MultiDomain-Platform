# Gestione del codice
Il codice viene diviso in sotto cartelle per rendere separati i vari moduli dell'applicazione, in particolare l'idea è la seguente:
Conoscendo come il framework laravel lavora, si va ad operare al suo interno mettendo: 
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


Poiché questo progetto prevede l'integrazione di più moduli bisogna mantenere coerente la UI, per questo motivo all'interno del file
app.css troviamo tutto il css "comune" mentre dentro components troviamo i template, ciò significa che se si necessita di uno dei componenti presenti dentro components e bene utilizzare quelli.

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