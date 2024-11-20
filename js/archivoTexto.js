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
                    return partes.length >= 6
                        ? { storBin: partes[1], materialNo: partes[5] }
                        : null;
                })
                .filter(Boolean);


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
    const reader = new FileReader();

    reader.onload = function (event) {
        const originalContent = event.target.result;
        const originalLines = originalContent.split(/\r?\n/);

        // Reemplazar `______________` con `PrimerConteo` en las líneas correspondientes
        const updatedLines = originalLines.map((line) => {
            if (/______________/.test(line)) {
                const [storBin] = line.trim().split(/\s+/);
                const matchingData = dataFromBackend.find(item => item.storBin === storBin);
                if (matchingData) {
                    return line.replace('______________', matchingData.PrimerConteo);
                }
            }
            return line; // Mantener líneas no modificadas
        });

        // Crear el nuevo contenido del archivo
        const finalContent = updatedLines.join("\n");
        const blob = new Blob([finalContent], { type: "text/plain" });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `actualizado_${file.name}`;
        link.click();
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