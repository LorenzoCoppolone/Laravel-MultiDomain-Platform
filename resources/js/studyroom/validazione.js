function toggleVis(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    
    // Se per caso non trova l'input o l'icona, si ferma senza dare errori
    if (!input || !icon) return; 

    input.type  = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('bx-show');
    icon.classList.toggle('bx-hide');
}

// Leghiamo gli eventi SOLO se gli elementi esistono in questa specifica pagina
const btnTogglePassword = document.getElementById('togglePassword');
if (btnTogglePassword) {
    btnTogglePassword.addEventListener('click', () => toggleVis('password', 'togglePassword'));
}

const btnToggleConferma = document.getElementById('toggleConferma');
if (btnToggleConferma) {
    btnToggleConferma.addEventListener('click', () => toggleVis('confermaPassword', 'toggleConferma'));
}



// Cerca tutte le icone con la classe .toggle-password (indipendentemente dall'ID)
const togglePasswords = document.querySelectorAll('.toggle-password');

togglePasswords.forEach(function(icon) {
    icon.addEventListener('click', function() {
        // Prende l'input HTML che sta immediatamente prima dell'icona
        const input = this.previousElementSibling;
        
        if (input && input.tagName === 'INPUT') {
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('bx-show');
                this.classList.add('bx-hide');
            } else {
                input.type = 'password';
                this.classList.remove('bx-hide');
                this.classList.add('bx-show');
            }
        }
    });
});


// ==========================================
// 3. CONFERMA PASSWORD
// ==========================================
const inputPassword = document.getElementById('password');
const inputConferma = document.getElementById('confermaPassword');
const msgEl = document.getElementById('err-conferma');

// Applica la validazione SOLO se tutti e 3 gli elementi esistono nella pagina attuale
if (inputPassword && inputConferma && msgEl) {
    
    inputConferma.addEventListener('input', function () {
        const v = this.value;

        if (!v) {
            this.classList.remove('valido', 'errore');
            msgEl.textContent = '';
            return;
        }

        if (v !== inputPassword.value) {
            this.classList.remove('valido');
            this.classList.add('errore');
            msgEl.textContent = 'Le password non coincidono.';
            msgEl.classList.remove('ok');
        } else {
            this.classList.remove('errore');
            this.classList.add('valido');
            msgEl.textContent = '✓ Le password coincidono';
            msgEl.classList.add('ok');
        }
    });

    // Rivalida conferma quando si modifica la password principale
    inputPassword.addEventListener('input', function () {
        if (inputConferma.value) {
            inputConferma.dispatchEvent(new Event('input'));
        }
    });
}