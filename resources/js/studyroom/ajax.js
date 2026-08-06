document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('pdf-container');
    if (!container) return; 

    const url = container.dataset.streamUrl;
    if (!url) return;

    // Configura il worker di pdf.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/pdf'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error("Errore dal server: " + response.status);
        return response.arrayBuffer();
    })
    .then(buffer => {
        pdfjsLib.getDocument({ data: buffer }).promise.then(pdf => {
            
            // Cicliamo per tutte le pagine del PDF
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                
                // 1. Creiamo il canvas fisicamente subito per garantire l'ordine delle pagine
                const canvas = document.createElement('canvas');
                canvas.classList.add('materiale-viewer__frame');
                container.appendChild(canvas);

                // 2. Disegniamo la pagina in modo asincrono
                pdf.getPage(pageNum).then(page => {
                    const context = canvas.getContext('2d');
                    
                    // Scala 1.5 offre un'ottima risoluzione per la lettura
                    const viewport = page.getViewport({ scale: 1.5 });

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    });
                });
            }
        }).catch(err => {
            console.error("Errore durante la lettura del PDF:", err);
            container.innerHTML = '<p style="color:red; text-align:center; margin-top:20px;">Impossibile elaborare il PDF.</p>';
        });
    })
    .catch(err => {
        console.error("Errore AJAX:", err);
        container.innerHTML = '<p style="color:red; text-align:center; margin-top:20px;">Errore di caricamento del file.</p>';
    });
});