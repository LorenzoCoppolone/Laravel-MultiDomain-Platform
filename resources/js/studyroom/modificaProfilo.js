// =============================================
// StudyRoom — modificaProfilo.js
// Anteprima live dell'immagine profilo selezionata
// nella pagina di modifica profilo.
// =============================================

const inputImmagine = document.getElementById("immagine");

if (inputImmagine) {
    inputImmagine.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const wrapper = document.getElementById("previewWrapper");
        let img = document.getElementById("previewImg");

        // Se non c'è ancora un'immagine, rimuovo l'icona placeholder e creo l'<img>
        if (!img) {
            const placeholder = document.getElementById("previewPlaceholder");
            if (placeholder) placeholder.remove();

            img = document.createElement("img");
            img.id = "previewImg";
            img.alt = "Foto profilo";
            wrapper.appendChild(img);
        }

        img.src = URL.createObjectURL(file);
    });

    document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formModificaProfilo');
    if (!form) return;

    // Trova il pulsante di submit (il button di tipo submit o l'elemento custom del componente btn-pill)
    // Se usi il componente x-btn-pill per il salvataggio, assicurati che generi un <button type="submit"> o diamogli un ID specifico.
    const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('.edit-actions button:last-child');
    if (!submitBtn) return;

    // Disabilitiamo il bottone all'avvio della pagina
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-disabled'); // Opzionale: per gestire l'aspetto grafico se hai una classe CSS apposita

    // Salviamo lo stato iniziale di tutti i campi input del form
    const inputs = form.querySelectorAll('input');
    const initialValues = {};

    inputs.forEach(input => {
        if (input.type === 'file') {
            initialValues[input.name] = ''; // I file partono vuoti
        } else {
            initialValues[input.name] = input.value;
        }
    });

    // Funzione che controlla se ci sono differenze rispetto ai valori iniziali
    function checkChanges() {
        let hasChanged = false;

        inputs.forEach(input => {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    hasChanged = true; // Se viene selezionata una foto, c'è una modifica
                }
            } else {
                if (input.value !== initialValues[input.name]) {
                    hasChanged = true; // Se un testo è diverso dall'iniziale, c'è una modifica
                }
            }
        });

       // Attiva o disattiva il bottone visivamente e funzionalmente
        if (hasChanged) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-disabled');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-disabled');
        }
    }

    // Ascoltiamo gli eventi di modifica su tutti gli input del form
    inputs.forEach(input => {
        input.addEventListener('input', checkChanges);
        input.addEventListener('change', checkChanges); // Utile soprattutto per l'input type="file"
    });
});
}