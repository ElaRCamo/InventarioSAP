function cargarDatosParte() {
    fetch('dao/daoConsultarParte.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('bodyParte');
            tableBody.innerHTML = ''; // Limpiar el contenido anterior

            // Verificar si hay datos en "data"
            if (data && data.data) {
                data.data.forEach(parte => {
                    const row = document.createElement('tr');

                    // Crear celdas para cada columna
                    row.innerHTML = `
                            <td>${parte.GrammerNo}</td>
                            <td>${parte.Descripcion}</td>
                            <td>${parte.UM}</td>
                            <td>${parte.ProfitCtr}</td>
                            <td>${parte.Costo}</td>
                            <td>${parte.Por}</td>
                        `;

                    // Agregar la fila a la tabla
                    tableBody.appendChild(row);
                });
            } else {
                // Si no hay datos, mostrar un mensaje en la tabla
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="6" class="text-center">No hay datos disponibles</td>';
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
}
/******************Cargar e insertar datos de Excel*******************/
document.getElementById('btnExcelParte').addEventListener('click', () => {
    document.getElementById('fileInputParte').click();
});

document.getElementById('fileInputParte').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelParte(file);
    }
});
async function insertarExcelParte(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const parteData = jsonData.slice(1).map((row) => {
            return {
                GrammerNo: row[0],
                Descripcion: row[1],
                UM: row[2],
                ProfitCtr: row[3],
                Costo: row[4],
                Por: row[5]
            };
        });

        // Enviar los datos al backend
        const response = await fetch('dao/daoInsertarParte.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ parteDatos: parteData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            cargarDatosParte();
        } else {
            // Mostrar el mensaje de error que viene del backend
            throw new Error(result.message );
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un error al procesar el archivo. Recargue la página e intente nuevamente.'
        });
    }
}
