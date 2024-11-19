/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', async (event) => {
    const file = event.target.files[0]; // El archivo seleccionado
    console.log("Archivo seleccionado:", file);  // Agrega esto para verificar el archivo

    if (file) {
        // Procesar el archivo y enviar los datos al backend
        const dataToBackend = await manejarArchivo(file);
        const dataFromBackend = await enviarDatosAlBackend(dataToBackend);

        if (dataFromBackend.length > 0) {
            // Solo actualiza si dataFromBackend tiene datos
            actualizarContenidoArchivo(file, dataFromBackend);
        } else {
            console.error("No se recibieron datos válidos del backend.");
        }
    } else {
        console.error("No se seleccionó ningún archivo.");
    }
});




/*

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
    };

    reader.onerror = (error) => {
        console.error("Error al leer el archivo:", error);
    };

    reader.readAsText(file);
}
*/
async function manejarArchivo(file) {
    const reader = new FileReader();

    return new Promise((resolve, reject) => {
        reader.onload = async (event) => {
            const contenido = event.target.result;

            // Dividir las líneas del archivo
            const lineas = contenido.split(/\r?\n/);

            // Filtrar las líneas que contienen datos válidos
            const datos = lineas
                .map((linea) => linea.trim())
                .filter((linea) => /^[0-9]+\s+\w+/.test(linea))
                .map((linea) => {
                    const partes = linea.split(/\s+/);
                    return {
                        storBin: partes[1],
                        materialNo: partes[5],
                    };
                });

            // Resolvemos la promesa con los datos procesados
            resolve(datos);
        };

        reader.onerror = (error) => {
            reject("Error al leer el archivo: " + error);
        };

        reader.readAsText(file);
    });
}
async function actualizarContenidoArchivo(file, dataFromBackend) {
    // Verifica si 'file' es un Blob válido
    if (!(file instanceof Blob)) {
        console.error("El archivo no es válido:", file);
        return;
    }

    const reader = new FileReader();

    reader.onload = function (event) {
        // Verifica que el contenido del archivo se esté leyendo correctamente
        const lines = event.target.result.split("\n"); // Dividimos el contenido por líneas
        const updatedLines = lines.map((line) => {
            // Lógica para actualizar las líneas
            // ...
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
