# Gestione dell'Autenticazione (Multi-Guard)
Per soddisfare il requisito di tabelle utente separate (es. studenti e amministratori) senza incorrere in duplicazioni o forzature sull'ereditarietà di Eloquent, è stato configurato un sistema basato su Guard e Provider multipli.

A. I Modelli di Autenticazione
Le classi che rappresentano gli utenti dei moduli (es. Studente e Amministratore) estendono la classe nativa Illuminate\Foundation\Auth\User (alias Authenticatable) e puntano a tabelle di database specifiche:

App\Models\Studente -> Tabella studenti_studyroom

App\Models\Amministratore -> Tabella amministratori_studyroom

B. Configurazione in config/auth.php
Il file di configurazione dell'autenticazione mappa esplicitamente i driver di sessione ai rispettivi Provider basati sui modelli Eloquent dedicati:

Guards: studente_studyroom, admin_studyroom (o similari).

Providers: configurati per utilizzare i driver eloquent associati ai rispettivi Model.

C. Logica di Login e Registrazione
I controller ereditati da Laravel Breeze sono stati adattati per:

Specificare esplicitamente il Guard di riferimento durante le fasi di autenticazione (Auth::guard('nome_guard')->attempt(...)).

Gestire la validazione dell'unicità dell'email puntando dinamicamente alla classe del Modello corretto anziché alla tabella users di default.

Proteggere le rotte tramite i middleware specifici dei guard (es. auth:studente,amministratore).

# Guida per lo Sviluppo di un Nuovo Modulo (Onboarding Sviluppatori)
Se un altro sviluppatore deve aggiungere un nuovo modulo (es. Modulo2), dovrà replicare la struttura a silos seguendo questi passaggi standardizzati:

# Definizione dei Dati e dei Modelli
Creare le migration per le nuove tabelle necessarie al modulo (es. php artisan make:migration create_utenti_modulo2_table).

Creare il Modello Eloquent corrispondente all'interno della cartella app/Models/Modulo2/ (o analoga) assicurandosi che estenda Authenticatable:

PHP
use Illuminate\Foundation\Auth\User as Authenticatable;

class UtenteModulo2 extends Authenticatable {
    protected $table = 'utenti_modulo2';
    protected $fillable = ['name', 'email', 'password'];
}


# Configurazione dei Guard
Aprire il file config/auth.php.

Aggiungere il nuovo Provider associato al nuovo Model.

Aggiungere il nuovo Guard associato al Provider appena creato.

# Configurazione delle Rotte Isolate
Creare un nuovo file di rotte dedicato nella cartella routes/ (es. routes/modulo2.php).

Registrare il file all'interno del metodo withRouting nel file bootstrap/app.php, applicando un prefisso URL e di naming:

PHP
Route::middleware('web')
    ->prefix('modulo2')
    ->name('modulo2.')
    ->group(base_path('routes/modulo2.php'));
# Implementazione dei Controller di Autenticazione
Duplicare o adattare i controller di autenticazione (ispirandosi allo standard Breeze).

Assicurarsi che le chiamate ad Auth::guard(...) utilizzino il nuovo guard configurato nel punto 2 e che la validazione dell'unicità faccia riferimento alla classe del nuovo Model.
