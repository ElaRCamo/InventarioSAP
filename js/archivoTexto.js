/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        manejarArchivo(file);
    }
});

async function manejarArchivo(archivo) {
    const datos = await procesarArchivoTXT(archivo); // Leer archivo
    const datosActualizados = await actualizarDatosTXT(datos); // Actualizar datos
    generarArchivoTXT(datosActualizados); // Generar el archivo actualizado
}


async function procesarArchivoTXT(archivo) {
    const texto = await archivo.text(); // Leer el contenido del archivo como texto
    const lineas = texto.split("\n"); // Dividir el contenido en líneas

    // Convertir cada línea en un objeto (parsing básico del TXT)
    const datos = lineas.map(linea => {
        const [storBin, materialNo, qtyUoM] = linea.split("\t"); // Usa el delimitador correcto
        return { storBin, materialNo, qtyUoM };
    });

    return datos; // Retornar como array de objetos
}
async function actualizarDatosTXT(datos) {
    const datosBackend = datos.map(({ storBin, materialNo }) => ({ storBin, materialNo }));

    // Llamar a la función para enviar los datos al backend
    const resultados = await enviarDatosAlBackend(datosBackend);

    // Mapear los resultados para actualizar la columna
    const datosActualizados = datos.map(dato => {
        const resultado = resultados.find(
            res => res.storBin === dato.storBin && res.materialNo === dato.materialNo
        );

        return {
            ...dato,
            qtyUoM: resultado ? resultado.PrimerConteo : dato.qtyUoM, // Actualiza solo si hay resultado
        };
    });

    return datosActualizados;
}

function generarArchivoTXT(datosActualizados) {
    const lineasActualizadas = datosActualizados.map(
        ({ storBin, materialNo, qtyUoM }) => `${storBin}\t${materialNo}\t${qtyUoM}`
    );

    const contenidoActualizado = lineasActualizadas.join("\n");

    // Crear un archivo descargable
    const blob = new Blob([contenidoActualizado], { type: "text/plain" });
    const enlace = document.createElement("a");
    enlace.href = URL.createObjectURL(blob);
    enlace.download = "archivo_actualizado.txt";
    enlace.click();
}



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