# Establecer la ruta de la carpeta de entrada y la de salida
$inputFolder = "C:\Fotos"
$outputFolder = "C:\Fotos\fotosPNG"

# Crear la carpeta de salida si no existe
if (!(Test-Path -Path $outputFolder)) {
    Write-Host "Creando carpeta de salida: $outputFolder"
    New-Item -ItemType Directory -Path $outputFolder
}

# Buscar imágenes con diversas extensiones (independientemente de mayúsculas/minúsculas)
Write-Host "Buscando imágenes en $inputFolder..."
$images = Get-ChildItem -Path $inputFolder -Include *.jpg, *.jpeg, *.bmp, *.gif, *.tiff, *.png, *.PNG -File

if ($images.Count -eq 0) {
    Write-Host "No se encontraron imágenes para convertir." -ForegroundColor Red
    Read-Host -Prompt "Presiona Enter para salir"
    exit
}

# Convertir imágenes
foreach ($image in $images) {
    $inputFile = $image.FullName

    # Cambiar la extensión a .png (en minúsculas)
    $outputFile = Join-Path $outputFolder ([System.IO.Path]::ChangeExtension($image.Name.ToLower(), ".png"))

    Write-Host "Convirtiendo: $inputFile -> $outputFile"

    # Convertir a PNG
    Add-Type -AssemblyName System.Drawing
    $img = [System.Drawing.Image]::FromFile($inputFile)
    $img.Save($outputFile, [System.Drawing.Imaging.ImageFormat]::Png)
    $img.Dispose()
}

Write-Host "Proceso completado." -ForegroundColor Green
Read-Host -Prompt "Presiona Enter para salir"

