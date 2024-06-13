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
        <?php
            // Verifica si hay una sesión iniciada
            if (isset($_SESSION['perfil'])) {
                if ($_SESSION['perfil'] === 'P') {
                    // Muestra un enlace para los profesores
                    echo '<p><a href="index.php?c=controlador_rec&m=vistaProfesor" class="boton">Profesores</a></p>';
                } else if ($_SESSION['perfil'] === 'A') {
                    // Muestra botones para los alumnos
                    echo '<p><a href="index.php?c=controlador_rec&m=mostrarFREC" class="boton">Enviar Reconocimiento</a></p>';
                    echo '<p><a href="index.php?c=controlador_rec&m=verMisReconocimientos" class="boton">Ver Mis Reconocimientos</a></p>';
                }
            }
        ?>
        <!-- Enlace para cerrar sesión -->
        <p><a href="index.php?c=controlador_sesion&m=cerrarSesion" class="boton">Cerrar Sesión</a></p>
    </div>
</body>
</html>
