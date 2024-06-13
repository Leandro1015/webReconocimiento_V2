<?php
    require_once './modelos/m_identificacion.php';

    class Controlador_sesion {
        public $nombre_vista;
        private $identificacion;

        public function __construct() {
            $this->identificacion = new M_identificacion();
        }

        /**
         * Muestra el formulario de inicio de sesión.
         */
        public function mostrarFIS() {
            $this->nombre_vista = 'forminiciosesion';
        }

        /**
         * Comprueba las credenciales de inicio de sesión.
         *
         * @return string|null Mensaje de error si las credenciales son incorrectas, null si son correctas.
         */
        public function comprobar() {  
            $msj = null;

            // Verificar si los campos de correo y contraseña no están vacíos
            if (!empty($_POST['correo']) && !empty($_POST['contrasenia'])) {
                $correo = $_POST['correo'];
                $contrasenia = $_POST['contrasenia'];

                // Intentar iniciar sesión con las credenciales proporcionadas
                $resultado = $this->identificacion->iniciarSesion($correo, $contrasenia);
                
                if ($resultado) {
                    // Iniciar sesión y almacenar los datos del usuario en la sesión
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['num_usuario'] = $resultado['num_usuario']; 
                    $_SESSION['perfil'] = $resultado['perfil'];
                    $this->nombre_vista = 'inicio';
                } else {
                    $msj = "Correo y/o contraseña incorrectos.";
                    $this->nombre_vista = 'forminiciosesion';
                    return $msj;
                }
            } else { 
                $msj = "Por favor, complete todos los campos.";
                $this->nombre_vista = 'forminiciosesion';
                return $msj;
            }
        }   

        /**
         * Muestra el formulario de registro.
         *
         * @return array Datos de la vista con los tipos de usuario.
         */
        public function mostrarFRG() {
            $this->nombre_vista = 'registro_form';

            // Obtener los tipos de usuario desde el modelo
            $datos_vista = $this->identificacion->listarTipos();

            return ['tipos' => $datos_vista]; // Devolver los tipos de usuario en un array asociativo
        }

        /**
         * Cierra la sesión actual.
         */
        public function cerrarSesion(){
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            session_destroy();
            $this->nombre_vista = 'forminiciosesion'; // Redirige a la página de inicio de sesión
        }

        /**
         * Registra un nuevo usuario.
         *
         * @return array|null Datos de la vista con los tipos de usuario y mensaje de error si los hay, null si el registro es exitoso.
         */
        public function registrar() {
            if ($this->validarCampos()) {
                $num_usuario = $_POST['num_usuario'];
                $nombre = $_POST['nombre'];
                $correo = $_POST['correo'];
                $contrasenia = $_POST['contrasenia'];
                $tipo = $_POST['perfil'];

                if (!empty($_POST['webReconocimiento'])) {
                    $webReconocimiento = $_POST['webReconocimiento'];
                } 
                else {
                    $webReconocimiento = null;
                }

                // Comprobar si ya existen usuarios con el mismo número o correo
                $msj = $this->identificacion->comprobarRegistro($num_usuario, $correo);

                if ($msj === "No hay duplicados, puede proceder con el registro.") {
                    // Registrar el nuevo usuario
                    $resultado = $this->identificacion->registrar($num_usuario, $nombre, $correo, $contrasenia, $webReconocimiento, $tipo);

                    if ($resultado === true) {
                        $this->nombre_vista = 'forminiciosesion';
                        return null;
                    } else {
                        $msj = $resultado;
                    }
                } else {
                    $this->nombre_vista = 'registro_form';
                }
            } else {
                $msj = "Las contraseñas no coinciden o faltan campos por completar.";
                $this->nombre_vista = 'registro_form';
            }

            $this->nombre_vista = 'registro_form';
            $datos_vista = $this->identificacion->listarTipos();
            return ['tipos' => $datos_vista, 'mensaje' => $msj];
        }

        /**
         * Valida los campos del formulario de registro.
         *
         * @return bool true si los campos son válidos y las contraseñas coinciden, false en caso contrario.
         */
        private function validarCampos() {
            if (!empty($_POST['num_usuario']) && !empty($_POST['nombre']) && !empty($_POST['correo']) && !empty($_POST['contrasenia']) 
                && !empty($_POST['confirmarContrasenia'])) 
            {
                return $_POST['contrasenia'] === $_POST['confirmarContrasenia'];
            } 
            else {
                return false;
            }
        }
    }
