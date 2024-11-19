/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const file = event.target.files[0]; // El archivo seleccionado
    if (file) {
        // Asegúrate de que 'file' es un objeto File
        console.log(file); // Verifica que sea un archivo válido

        // Procesar el archivo y enviar los datos al backend
        const dataToBackend = await manejarArchivo(file); // Tu función para procesar el archivo
        const dataFromBackend = await enviarDatosAlBackend(dataToBackend); // Datos actualizados del backend

        // Actualizar y descargar el archivo con el contenido actualizado
        if (dataFromBackend.length > 0) {
            await actualizarContenidoArchivo(file, dataFromBackend); // Actualizar y descargar el archivo
        } else {
            console.error("No se recibieron datos válidos del backend.");
        }
    } else {
        console.error("No se seleccionó ningún archivo.");
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
        await actualizarContenidoArchivo(contenido, resultado);
    };

    reader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
    };

    reader.readAsText(file);
}
async function actualizarContenidoArchivo(file, dataFromBackend) {
    const reader = new FileReader();

    reader.onload = function (event) {
        // Verifica que el contenido del archivo se esté leyendo correctamente
        const lines = event.target.result.split("\n"); // Dividimos el contenido por líneas
        const updatedLines = lines.map((line) => {
            // Extraemos storBin y materialNo desde el formato de la línea
            const storBin = line.slice(0, 20).trim(); // Ajusta el índice según tu formato
            const materialNo = line.slice(30, 40).trim(); // Ajusta el índice según tu formato

            // Buscamos si hay coincidencia con el dato del backend
            const matchedRecord = dataFromBackend.find(
                (record) => record.storBin.trim() === storBin && record.materialNo.trim() === materialNo
            );

            if (matchedRecord) {
                const primerConteo = matchedRecord.PrimerConteo.padEnd(15, " "); // Ajustamos el largo del PrimerConteo
                // Reemplazamos el segmento correspondiente en la línea
                return line.slice(0, 70) + primerConteo + line.slice(85); // Ajusta el índice según el formato
            }

            return line; // Si no hay coincidencia, mantenemos la línea sin cambios
        });

        // Generamos el contenido actualizado
        const updatedContent = updatedLines.join("\n");

        // Creamos el Blob con el contenido actualizado
        const blob = new Blob([updatedContent], { type: "text/plain" });

        // Creamos el enlace para la descarga
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "archivo_actualizado.txt"; // Nombre del archivo que se descargará

        // Simulamos el clic en el enlace para iniciar la descarga
        link.click();

        // Limpiamos el enlace temporal
        document.body.removeChild(link);
    };

    // Leemos el archivo (asegúrate de que 'file' sea un objeto File válido)
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
