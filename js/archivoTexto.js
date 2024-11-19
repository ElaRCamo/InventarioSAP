/******************Cargar e insertar datos de txt*******************/
document.getElementById('btnTxtBitacora').addEventListener('click', () => {
    document.getElementById('fileInputTxt').click();
});

document.getElementById('fileInputTxt').addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        actualizarTxtBitacora(file);
    }
});


function  actualizarTxtBitacora(file){

    alert("Se carga txt")

}