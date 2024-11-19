/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const file = event.target.files[0];

    if (!file) {
        console.error("No se seleccionó ningún archivo.");
        return;
    }

    // Validar el tipo de archivo (opcional, si es necesario)
    if (file.type !== "text/plain") {
        console.error("Por favor, selecciona un archivo de texto válido.");
        return;
    }

    try {
        await manejarArchivo(file);
    } catch (error) {
        console.error("Error al procesar el archivo:", error);
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

function actualizarContenidoArchivo(contenidoOriginal, datosActualizados) {
    // Separar el contenido original en líneas
    const lineas = contenidoOriginal.split(/\r?\n/);

    // Crear un mapa para acceder rápidamente a los datos actualizados por `storBin`
    const mapaActualizaciones = new Map(
        datosActualizados.map((item) => [item.storBin.trim(), item.qtyUoM]) // Asocia `storBin` con `qtyUoM`
    );

    // Actualizar las líneas del archivo
    const contenidoModificado = lineas.map((linea) => {
        const storBinRegex = /^(?<storBin>[^\s]+)\s+/; // Ajustar el regex según el formato exacto del archivo
        const match = linea.match(storBinRegex);

        if (match) {
            const storBin = match.groups.storBin.trim();

            // Si existe una actualización para este `storBin`, se modifica la línea
            if (mapaActualizaciones.has(storBin)) {
                const qtyUoM = mapaActualizaciones.get(storBin);

                // Actualizar la línea con el nuevo valor de Qty........... UoM
                return linea.replace(/Qty\.+\s+\w+/, `Qty........... ${qtyUoM}`);
            }
        }

        // Si no coincide, devolver la línea sin cambios
        return linea;
    });

    // Combinar las líneas nuevamente en un solo string
    const contenidoFinal = contenidoModificado.join("\r\n");

    // Descargar el archivo modificado
    descargarArchivo(contenidoFinal, "archivo_actualizado.txt");
}




async function enviarDatosAlBackend(data) {
    try {
        const response = await fetch('https://grammermx.com/excelInventario/dao/daoActualizar-txt.php', {
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
