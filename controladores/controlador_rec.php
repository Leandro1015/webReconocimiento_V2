<?php
    require_once './modelos/m_reconocimiento.php';

    /**
     * Clase Controlador_rec
     * Controlador para gestionar reconocimientos entre alumnos.
     */
    class Controlador_rec {
        public $nombre_vista;
        private $reconocimiento;
    
        public function __construct() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $this->verificarLaSesion();
            $this->reconocimiento = new M_reconocimiento();
        }
    
        public function mostrarFREC() {
            // Solo accesible para alumnos (A)
            if ($_SESSION['perfil'] !== 'A') {
                $this->nombre_vista = 'inicio';
                return null;
            }
            $idAlumnoEnvia = $_SESSION['num_usuario'];
            $datos_vista = $this->reconocimiento->obtenerAlumnos($idAlumnoEnvia);
            $this->nombre_vista = 'enviar_reconocimiento';
            return $datos_vista;
        }
    
        public function comprobarRec() {
            // Solo accesible para alumnos (A)
            if ($_SESSION['perfil'] !== 'A') {
                $this->nombre_vista = 'inicio';
                return "Acceso denegado.";
            }
            $msj = null;
            if (!empty($_POST['momento']) && !empty($_POST['descripcion']) && !empty($_POST['idAlumnoRecibe'])) {
                $idAlumnoEnvia = $_SESSION['num_usuario'];
                $momento = $_POST['momento'];
                $descripcion = $_POST['descripcion'];
                $idAlumnoRecibe = $_POST['idAlumnoRecibe'];
                $resultado = $this->reconocimiento->enviar($idAlumnoEnvia, $idAlumnoRecibe, $momento, $descripcion);
                if ($resultado === true) {
                    $this->ultimoReconocimiento($idAlumnoRecibe);
                    $this->nombre_vista = 'exito';
                } else {
                    $msj = "Hubo un error al enviar el reconocimiento.";
                    $this->nombre_vista = 'enviar_reconocimiento';
                }
            } else {
                $msj = "Por favor, complete todos los campos.";
                $this->nombre_vista = 'enviar_reconocimiento';
            }
            return $msj;
        }
    
        public function verMisReconocimientos() {
            // Solo accesible para alumnos (A)
            if ($_SESSION['perfil'] !== 'A') {
                $this->nombre_vista = 'inicio';
                return null;
            }
            $idAlumnoRecibe = $_SESSION['num_usuario'];
            $datos_vista = $this->reconocimiento->obtenerReconocimientos($idAlumnoRecibe);
            $this->nombre_vista = 'listado';
            return $datos_vista;
        }
    
        public function verUnReconocimiento($id) {
            // Solo accesible para alumnos (A)
            if ($_SESSION['perfil'] !== 'A') {
                $this->nombre_vista = 'inicio';
                return null;
            }
            $datos_vista = $this->reconocimiento->obtenerReconocimiento($id);
            $this->nombre_vista = 'verMiReconocimiento';
            return $datos_vista;
        }
    
        public function mostrarInicio() {
            $this->nombre_vista = 'inicio';
        }
    
        public function ultimoReconocimiento($idAlumnoRecibe) {
            if (!empty($idAlumnoRecibe)) {
                $ultimo_alumno = $this->reconocimiento->ultimoReconocimiento($idAlumnoRecibe);
                setcookie('ultimo', $ultimo_alumno, time() + 3600, "/");
            }
        }
    
        public function vistaProfesor() {
            if ($_SESSION['perfil'] === 'P') {
                $this->nombre_vista = 'profesor';
            } else {
                $this->nombre_vista = 'inicio';
            }
        }
    
        private function verificarLaSesion() {
            if (!isset($_SESSION['num_usuario'])) {
                header('Location: index.php');
                exit();
            }
        }
    }
    