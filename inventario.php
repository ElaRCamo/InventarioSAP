<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="" type="image/x-icon">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administrar</title>

    <!--Enlace de iconos: icons8, licencia con mención -->
    <link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css" >

    <!-- Tippy.js core styles -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">

    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">

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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelBitacora"> Cargar Excel Bitacora</button>
                    <input type="file" id="fileInputBitacora" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltipBitacora"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

                    <button class="btn btn-primary text-right btnExcel" id="btnTxtBitacora"> Actualizar txt </button>
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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelStorage"> Cargar Excel Storage</button>
                    <input type="file" id="fileInputStorage" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltipStorage"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

                    <button class="btn btn-primary text-right btnExcel" id="btnTxtStorage"> Actualizar txt </button>
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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelArea"> Cargar Excel Areas</button>
                    <input type="file" id="fileInputArea" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltipArea"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelUbicaciones"> Cargar Excel Ubicaciones</button>
                    <input type="file" id="fileInputUbicaciones" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltipUbicaciones"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelInventario"> Cargar Excel Inventario</button>
                    <input type="file" id="fileInputInventario" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltiInventario"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelBin"> Cargar Excel Bin</button>
                    <input type="file" id="fileInputBin" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltiBin"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

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
                    <button class="btn btn-success text-right btnExcel" id="btnExcelParte"> Cargar Excel Parte</button>
                    <input type="file" id="fileInputParte" accept=".xlsx, .xls" style="display: none;" />
                    <button class="btn btn-secondary text-right btnExcel" id="tooltiParte"><i class="far fa-question-circle position-absolute"></i>? Ejemplo excel</button>

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
        //cargarDatosStorage();

    });

    document.getElementById("tooltipBitacora").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltipBitacora",
            "https://grammermx.com/excelInventario/imgs/bitacora.png",
            320,
            140
        );
    });

    document.getElementById("tooltipStorage").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltipStorage",
            "https://grammermx.com/excelInventario/imgs/storage.png",
            320,
            100
        );
    });

    document.getElementById("tooltipArea").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltipArea",
            "https://grammermx.com/excelInventario/imgs/area.png",
            320,
            120
        );
    });

    document.getElementById("tooltipUbicaciones").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltipUbicaciones",
            "https://grammermx.com/excelInventario/imgs/area.png",
            320,
            120
        );
    });

    document.getElementById("tooltiInventario").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltiInventario",
            "https://grammermx.com/excelInventario/imgs/inventarioSap.png",
            310,
            120
        );
    });

    document.getElementById("tooltiBin").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltiBin",
            "https://grammermx.com/excelInventario/imgs/bin.png",
            250,
            120
        );
    });

    document.getElementById("tooltiParte").addEventListener("click", function() {
        mostrarImagenTooltip(
            "tooltiParte",
            "https://grammermx.com/excelInventario/imgs/parte.png",
            320,
            120
        );
    });
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


<script src="https://unpkg.com/tippy.js@6"></script>
</body>

</html>

