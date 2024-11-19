/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (file) {
        // Paso 1: Procesar el archivo
        const dataToBackend = await manejarArchivo(file);

        // Paso 2: Enviar datos al backend
        const dataFromBackend = await enviarDatosAlBackend(dataToBackend);

        // Paso 3: Actualizar el archivo TXT
        if (dataFromBackend.length > 0) {
            await actualizarContenidoArchivo(file, dataFromBackend);
        } else {
            console.error("No se recibieron datos válidos del backend.");
        }
    }
});


async function manejarArchivo(file) {
    const reader = new FileReader();

    reader.onload = async (event) => {
        const contenido = event.target.result;

        // Dividir las líneas del archivo
        const lineas = contenido.split(/\r?\n/);

        // Filtrar las líneas que contienen datos válidos (ejemplo: columnas específicas)
        const datos = lineas
            .map((linea) => linea.trim()) // Quitar espacios innecesarios
            .filter((linea) => /^[0-9]+\s+\w+/.test(linea)) // Coincidir con líneas que empiezan con un número y tienen datos
            .map((linea) => {
                const partes = linea.split(/\s+/); // Dividir la línea por espacios en blanco

                // Extraer campos específicos (storBin y Material no.)
                return {
                    storBin: partes[1], // Ejemplo: Segundo elemento
                    materialNo: partes[5], // Ejemplo: Sexto elemento
                };
            });

        // Enviar datos al backend
        const resultado = await enviarDatosAlBackend(datos);
        console.log("Datos procesados y devueltos del backend:", resultado);

        // Actualizar el archivo directamente en el frontend
        actualizarContenidoArchivo(contenido, resultado);
    };

    reader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
    };

    reader.readAsText(file);
}
async function actualizarContenidoArchivo(file, dataFromBackend) {
    const reader = new FileReader();

    reader.onload = function (event) {
        const lines = event.target.result.split("\n");
        const updatedLines = lines.map((line) => {
            // Buscar coincidencias con storBin y materialNo
            const storBin = line.slice(0, 20).trim(); // Ajusta los índices según el formato
            const materialNo = line.slice(30, 40).trim(); // Ajusta los índices según el formato

            const matchedRecord = dataFromBackend.find(
                (record) => record.storBin.trim() === storBin && record.materialNo.trim() === materialNo
            );

            if (matchedRecord) {
                // Reemplazar el segmento correspondiente con PrimerConteo
                const primerConteo = matchedRecord.PrimerConteo.padEnd(15, " "); // Ajusta el formato
                return (
                    line.slice(0, 70) + // Parte inicial de la línea
                    primerConteo + // PrimerConteo actualizado
                    line.slice(85) // Parte final de la línea
                );
            }

            return line; // Devolver la línea sin cambios si no hay coincidencia
        });

        // Generar el contenido actualizado
        const updatedContent = updatedLines.join("\n");

        // Crear y descargar el archivo actualizado
        const blob = new Blob([updatedContent], { type: "text/plain" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "archivo_actualizado.txt";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    reader.readAsText(file);
}


async function enviarDatosAlBackend(data) {
    try {
        const response = await fetch('dao/daoActualizar-txt.php', {
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

function descargarArchivo(contenido, nombreArchivo) {
    const blob = new Blob([contenido], { type: "text/plain" });
    const enlace = document.createElement("a");

    enlace.href = URL.createObjectURL(blob);
    enlace.download = nombreArchivo;

    // Activar la descarga
    enlace.click();

    // Liberar el objeto
    URL.revokeObjectURL(enlace.href);
}
