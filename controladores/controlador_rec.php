<?php
    require_once './modelos/m_reconocimiento.php';

    /**
     * Clase Controlador_rec
     * Controlador para gestionar reconocimientos entre alumnos.
     */
    class Controlador_rec {
        public $nombre_vista;
        private $reconocimiento;

        /**
         * Constructor de la clase Controlador_rec.
         * Inicia la sesión si no está iniciada y verifica la sesión del usuario.
         */
        public function __construct() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Verificar si la sesión está activa
            $this->verificarLaSesion();
            // Crear una nueva instancia del modelo M_reconocimiento
            $this->reconocimiento = new M_reconocimiento();
        }

        /**
         * Muestra el formulario de envío de reconocimiento.
         * @return array Datos de la vista con la lista de alumnos.
         */
        public function mostrarFREC() {
            // Obtener el ID del alumno que envía el reconocimiento desde la sesión
            $idAlumnoEnvia = $_SESSION['num_usuario'];
            
            // Obtener la lista de alumnos desde el modelo
            $datos_vista = $this->reconocimiento->obtenerAlumnos($idAlumnoEnvia);
            
            // Asignar el nombre de la vista
            $this->nombre_vista = 'enviar_reconocimiento';
        
            // Retornar los datos de la vista
            return $datos_vista;
        }

        /**
         * Comprueba y envía un reconocimiento.
         * @return string|null Mensaje de error o null si el envío fue exitoso.
         */
        public function comprobarRec() {
            $msj = null;
            
            // Verificar que todos los campos del formulario estén completos
            if (!empty($_POST['momento']) && !empty($_POST['descripcion']) && !empty($_POST['idAlumnoRecibe'])) {

                // Obtener los datos del formulario y de la sesión
                $idAlumnoEnvia = $_SESSION['num_usuario']; 
                $momento = $_POST['momento'];
                $descripcion = $_POST['descripcion'];
                $idAlumnoRecibe = $_POST['idAlumnoRecibe'];
            
                // Enviar el reconocimiento a través del modelo
                $resultado = $this->reconocimiento->enviar($idAlumnoEnvia, $idAlumnoRecibe, $momento, $descripcion);

                // Verificar el resultado del envío
                if ($resultado === true) {
                    // Guardar el último reconocimiento enviado
                    $this->ultimoReconocimiento($idAlumnoRecibe);
                    $this->nombre_vista = 'exito';
                } else {
                    // Mensaje de error en caso de fallo
                    $msj = "Hubo un error al enviar el reconocimiento.";
                    $this->nombre_vista = 'enviar_reconocimiento';
                }
            } else { 
                // Mensaje de error si faltan campos
                $msj = "Por favor, complete todos los campos.";
                $this->nombre_vista = 'enviar_reconocimiento';
            }

            // Retornar el mensaje
            return $msj;
        }   
        
        /**
         * Muestra los reconocimientos recibidos por el usuario actual.
         * @return array Datos de la vista con los reconocimientos recibidos.
         */
        public function verMisReconocimientos() {  
            // Obtener el ID del alumno que recibe el reconocimiento desde la sesión
            $idAlumnoRecibe = $_SESSION['num_usuario'];
            
            // Obtener los reconocimientos desde el modelo
            $datos_vista = $this->reconocimiento->obtenerReconocimientos($idAlumnoRecibe);
        
            // Asignar el nombre de la vista

            $this->nombre_vista = 'listado';

            // Retornar los datos de la vista
            return $datos_vista;
        }

        /**
         * Muestra un reconocimiento específico.
         * @param int $id ID del reconocimiento.
         * @return array Datos de la vista con el reconocimiento específico.
         */
        public function verUnReconocimiento($id) {
            // Obtener los datos del reconocimiento desde el modelo
            $datos_vista = $this->reconocimiento->obtenerReconocimiento($id);
            // Asignar el nombre de la vista
            $this->nombre_vista = 'verMiReconocimiento';
            // Retornar los datos de la vista
            return $datos_vista;
        }

        /**
         * Muestra la vista de inicio.
         */
        public function mostrarInicio() {
            // Asignar el nombre de la vista
            $this->nombre_vista = 'inicio';
        }

        /**
         * Guarda el último reconocimiento recibido en una cookie.
         * @param int $idAlumnoRecibe ID del alumno que recibe el reconocimiento.
         */
        public function ultimoReconocimiento($idAlumnoRecibe) {
            if (!empty($idAlumnoRecibe)) {
                // Obtener el último reconocimiento desde el modelo
                $ultimo_alumno = $this->reconocimiento->ultimoReconocimiento($idAlumnoRecibe);
                setcookie('ultimo', $ultimo_alumno, time() + 3600, "/");
            }
        }

        /**
         * Muestra la vista para el perfil de profesor.
         * Cambia a la vista de inicio si el perfil no es de profesor.
         */
        public function vistaProfesor() {
            // Verificar si el perfil es de profesor
            if ($_SESSION['perfil'] === 'P') {
                // Asignar la vista de profesor
                $this->nombre_vista = 'profesor';
            } else {
                // Asignar la vista de inicio
                $this->nombre_vista = 'inicio';
            }
        }

        /**
         * Verifica si la sesión del usuario está activa.
         * Redirige al índice si la sesión no está activa.
         */
        private function verificarLaSesion() {
            // Verificar si el número de usuario está en la sesión
            if (!isset($_SESSION['num_usuario'])) {
                // Redirigir a la página de inicio de sesión
                header('Location: index.php');
                exit();
            }
        }
    }

