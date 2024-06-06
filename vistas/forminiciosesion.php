<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/estilo.css">
        <title>Formulario de inicio de sesión</title>
    </head>
    <body>
        <div class="contenedor">
            <h2>Iniciar Sesión</h2>
            <form action="index.php?c=Controlador_sesion&m=comprobar" method="post">
                <label>Correo del Usuario:</label><br/>
                <input type="email" name="correo"><br/>

                <label>Contraseña:</label><br/>
                <input type="password" name="contrasenia"><br/><br/>

                <input type="submit" value="Enviar">
            </form>
            <!-- Botón o enlace para redirigir a registro_form.html -->
            <p><a href="index.php?c=Controlador_sesion&m=mostrarFRG">¿No tienes una cuenta? ¡Regístrate!</a></p>
            <?php 
                if (isset($datos_vista)) { 
                    echo "<p class='error-message'>" . $datos_vista . "</p>";
                } 
            ?>
        </div>
    </body>
</html>
