function cargarDatosParte() {
    fetch('../dao/daoConsultarParte.php')
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
                            <td>${parte.GammerNo}</td>
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
document.getElementById('btnInsertarPrestamosExcel').addEventListener('click', () => {
    document.getElementById('fileInputPrestamos').click();
});

document.getElementById('fileInputPrestamos').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        insertarExcelPrestamos(file);
    }
});
async function insertarExcelPrestamos(file) {
    try {
        // Leer el archivo Excel
        const data = await file.arrayBuffer();
        const workbook = XLSX.read(data, { type: 'array' });
        const worksheet = workbook.Sheets[workbook.SheetNames[0]];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Mapear los datos, asegurándonos de convertir las fechas correctamente
        const prestamosData = jsonData.slice(1).map((row) => {
            return {
                idSolicitud: row[0],
                montoDepositado: row[1],
                // Convertir la fecha usando excelDateToJSDate
                fechaDeposito: excelDateToJSDate(row[2])
            };
        });

        // Enviar los datos al backend
        const response = await fetch('https://grammermx.com/RH/CajitaGrammer/dao/daoActualizarPrestamosExcel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ prestamos: prestamosData })
        });

        // Obtener la respuesta del backend
        const result = await response.json();

        if (result.status === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Actualización exitosa',
                text: result.message
            });

            initDataTablePresAdmin(anioActual);
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
