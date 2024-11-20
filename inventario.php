<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="" type="image/x-icon">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrar</title>

    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >
    <link href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>
<main>

    <section class="tabla-section" id="sectionBitacora">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Bitacora</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelBitacora"> Cargar Excel Bitacora</button>
                    <input type="file" id="fileInputBitacora" accept=".xlsx, .xls" style="display: none;" />

                    <button class="btn btn-secondary text-right btnExcel" id="btnTxtBitacora"> Actualizar txt </button>
                    <input type="file" id="fileInputTxt" accept=".txt" style="display: none;" />

                    <table class="table table-striped table-bordered mt-3" id="tablaBitacora">
                        <thead>
                        <tr>
                            <th>Id_Bitacora</th>
                            <th>NumeroParte</th>
                            <th>FolioMarbete</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Estatus</th>
                            <th>PrimerConteo</th>
                            <th>SegundoConteo</th>
                            <th>TercerConteo</th>
                            <th>Comentario</th>
                            <th>StorageBin</th>
                            <th>StorageType</th>
                            <th>Area</th>
                        </tr>
                        </thead>
                        <tbody id="bodyBitacora"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionStorage">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Storage</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelStorage"> Cargar Excel Storage</button>
                    <input type="file" id="fileInputStorage" accept=".xlsx, .xls" style="display: none;" />

                    <button class="btn btn-secondary text-right btnExcel" id="btnTxtStorage"> Actualizar txt </button>
                    <input type="file" id="fileInputTxtS" accept=".txt" style="display: none;" />

                    <table class="table table-striped table-bordered mt-3" id="tablaStorage">
                        <thead>
                        <tr>
                            <th>id_StorageUnit</th>
                            <th>Numero_Parte</th>
                            <th>Cantidad</th>
                            <th>Storage_Bin</th>
                            <th>Storage_Type</th>
                        </tr>
                        </thead>
                        <tbody id="bodyStorage"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionArea">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Area</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelArea"> Cargar Excel Areas</button>
                    <input type="file" id="fileInputArea" accept=".xlsx, .xls" style="display: none;" />
                    <table class="table table-striped table-bordered mt-3" id="tablaArea">
                        <thead>
                        <tr>
                            <th>IdArea</th>
                            <th>AreaNombre</th>
                            <th>AreaProduccion</th>
                            <th>StLocation</th>
                            <th>StBin</th>
                        </tr>
                        </thead>
                        <tbody id="bodyArea"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionUbicaciones">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Ubicaciones</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelUbicaciones"> Cargar Excel Ubicaciones</button>
                    <input type="file" id="fileInputUbicaciones" accept=".xlsx, .xls" style="display: none;" />
                    <table class="table table-striped table-bordered mt-3" id="tablaUbicaciones">
                        <thead>
                        <tr>
                            <th>GrammerNo</th>
                            <th>PVB</th>
                        </tr>
                        </thead>
                        <tbody id="bodyUbicaciones"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionInventario">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Inventario</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelInventario"> Cargar Excel Inventario</button>
                    <input type="file" id="fileInputInventario" accept=".xlsx, .xls" style="display: none;" />
                    <table class="table table-striped table-bordered mt-3" id="tablaInventario">
                        <thead>
                        <tr>
                            <th>STLocation</th>
                            <th>StBin</th>
                            <th>StType</th>
                            <th>GrammerNo</th>
                            <th>Cantidad</th>
                            <th>AreaCve</th>
                        </tr>
                        </thead>
                        <tbody id="bodyInventario"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionBin">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Bin</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelBin"> Cargar Excel Bin</button>
                    <input type="file" id="fileInputBin" accept=".xlsx, .xls" style="display: none;" />
                    <table class="table table-striped table-bordered mt-3" id="tablaBin">
                        <thead>
                        <tr>
                            <th>StBin</th>
                            <th>StType</th>
                        </tr>
                        </thead>
                        <tbody id="bodyBin"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="tabla-section" id="sectionParte">
        <div class=""></div>
        <div class="container">
            <div class="row">
                <div class="container mt-5">
                    <h2 class="text-center">Tabla Parte</h2>
                    <button class="btn btn-secondary text-right btnExcel" id="btnExcelParte"> Cargar Excel Parte</button>
                    <input type="file" id="fileInputParte" accept=".xlsx, .xls" style="display: none;" />
                    <table class="table table-striped table-bordered mt-3" id="tablaParte">
                        <thead>
                        <tr>
                            <th>GrammerNo</th>
                            <th>Descripcion</th>
                            <th>UM</th>
                            <th>ProfitCtr</th>
                            <th>Costo</th>
                            <th>Por</th>
                        </tr>
                        </thead>
                        <tbody id="bodyParte"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        //cargarDatosParte();
        //cargarDatosBin();
    });



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
            const originalLines = originalContent.split(/\r?\n/); // Divide el archivo en líneas

            //console.log("Contenido original del archivo:");
            //console.log(originalContent);

            const updatedLines = originalLines.map((line) => {
                // Divide la línea en partes basándose en espacios/tabulaciones
                const parts = line.trim().split(/\s+/);

                if (parts.length >= 6) {
                    const storBin = parts[1]; // `storBin` es el segundo elemento
                    const materialNo = parts[5]; // `materialNo` es el sexto elemento

                    //console.log(`Procesando línea: ${line}`);
                    //console.log(`Extracted storBin: ${storBin}, materialNo: ${materialNo}`);

                    // Buscar coincidencia en dataFromBackend
                    const matchingData = dataFromBackend.find(
                        (item) => item.storBin === storBin && item.materialNo === materialNo
                    );

                    if (matchingData) {
                        //console.log(`Coincidencia encontrada para storBin: ${storBin}, materialNo: ${materialNo}`);
                        //console.log(`Reemplazando ______________ con: ${matchingData.conteoFinal}`);
                        return line.replace("______________", matchingData.conteoFinal);
                    } else {
                        //console.log(`No se encontró coincidencia para storBin: ${storBin}, materialNo: ${materialNo}`);
                    }
                } else {
                    //console.log("Formato de línea inesperado:", line);
                }

                return line; // Mantener la línea sin cambios si no hay coincidencia
            });

            //console.log("Contenido actualizado del archivo:");
            //console.log(updatedLines.join("\n")); // Verifica el contenido final

            const finalContent = updatedLines.join("\n"); // Unir las líneas actualizadas
            const blob = new Blob([finalContent], { type: "text/plain" });

            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `actualizado_${file.name}`;
            link.click();
        };

        //console.log("Datos del backend recibidos:");
        //console.log(JSON.stringify(dataFromBackend, null, 2)); // Verifica los datos recibidos del backend

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

</script>

<!-- -Archivos de jQuery-->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- JAVASCRIPT FILES -->
<script src="js/excel.js"></script>
<script src="js/archivoTexto.js"></script>

<!-- BOOSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>


<!-- DataTable -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

</body>

</html>

