<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Detalles del Reconocimiento</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Detalles del Reconocimiento</h2>
            <?php
                if (!empty($datos_vista)) {
                    $reconocimiento = $datos_vista;
                    echo "<p>Momento: " . $reconocimiento['momento'] . "</p>";
                    echo "<p>Descripción: " . $reconocimiento['descripcion'] . "</p>";
                } 
                else {
                    echo "<p>Error: No se encontraron detalles de reconocimiento.</p>";
                }
            ?>
            <p><a href="index.php?c=Controlador_rec&m=verMisReconocimientos" class="boton">Volver</a></p>
        </div>
    </body>
</html>
