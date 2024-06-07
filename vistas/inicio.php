<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Página de Inicio</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Inicio</h2>
            <p><a href="index.php?c=Controlador_rec&m=mostrarFREC" class="boton">Enviar Reconocimiento</a></p>
            <p><a href="index.php?c=Controlador_rec&m=verMisReconocimientos" class="boton">Ver Mis Reconocimientos</a></p>
            <?php
                if ($_SESSION['perfil'] === 'P') {
                    echo '<p><a href="index.php?c=Controlador_rec&m=vistaProfesor">Profesores</a></p>';
                }
            ?>
            <p><a href="index.php?c=Controlador_sesion&m=cerrarSesion" class="boton">Cerrar Sesión</a></p>
        </div>
    </body>
</html>
