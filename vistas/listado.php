<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Lista de Reconocimientos</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Lista de Reconocimientos</h2>
            <ul>
                <?php
                    if (!empty($datos_vista)) {
                        $orden = 1; // Orden de reconocimiento (1, 2, 3...)
                        foreach ($datos_vista as $reconocimiento) {
                            echo "<li>";
                            echo "<span class='reconocimiento'>Reconocimiento " . $orden . ":</span>";
                            echo "<a href='index.php?c=Controlador_rec&m=verUnReconocimiento&id=" . $reconocimiento['idReconocimiento'] . "'>Ver reconocimiento</a>";
                            echo "</li>";
                            $orden++;
                        }
                    } else {
                        echo "<li>No hay reconocimientos disponibles</li>";
                    }
                ?>
            </ul>
            <p><a href="index.php?c=Controlador_rec&m=mostrarInicio" class="boton">Ir a la página de inicio</a></p>
        </div>
    </body>
</html>
