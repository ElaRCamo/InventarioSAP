/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        actualizarTxtBitacora(file);
    }
});

function actualizarTxtBitacora(file) {
    const reader = new FileReader();
    reader.onload = async (event) => {
        const content = event.target.result;

        // Procesar contenido del archivo
        const lines = content.split('\n');
        const dataToSend = [];
        const allLines = [];
        for (const line of lines) {
            // Modificar el patrón para capturar `storBin` y `Material no.`
            const match = line.match(/(\d{4})\s+(\d+)\s+\w+\s+\d+\s+\d+\s+\d+/);
            if (match) {
                const [_, storBin, materialNo] = match;
                dataToSend.push({ storBin, materialNo });
            }
            allLines.push(line); // Guardar líneas originales para reconstrucción
        }

        // Enviar datos al backend
        const updatedData = await enviarDatosAlBackend(dataToSend);

        // Actualizar el archivo con los datos recibidos
        const updatedLines = actualizarLineasConDatos(allLines, updatedData);
        descargarArchivoActualizado(updatedLines.join('\n'));
    };
    reader.readAsText(file);
}

async function enviarDatosAlBackend(data) {
    try {
        const response = await fetch('../dao/daoActualizar-txt.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return await response.json(); // Devolvemos los datos procesados por el backend
    } catch (error) {
        console.error('Error enviando datos al backend:', error);
        return [];
    }
}


function actualizarLineasConDatos(lines, updatedData) {
    const dataMap = new Map(
        updatedData.map(({ storBin, materialNo, qty }) => [`${storBin}-${materialNo}`, qty])
    );

    return lines.map((line) => {
        const match = line.match(/(\d{4})\s+(\d+)\s+\w+\s+\d+\s+\d+\s+\d+/);
        if (match) {
            const [_, storBin, materialNo] = match;
            const key = `${storBin}-${materialNo}`;
            const qty = dataMap.get(key) || 'No data';
            return line.replace(/_______________/, qty);
        }
        return line;
    });
}

function descargarArchivoActualizado(content) {
    const blob = new Blob([content], { type: 'text/plain' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'archivo_actualizado.txt';
    a.click();
}