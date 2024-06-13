<?php
require_once './modelos/m_identificacion.php';

class Controlador_sesion {
    public $nombre_vista;
    private $identificacion;

    public function __construct() {
        $this->identificacion = new M_identificacion();
    }

    public function mostrarFIS() {
        $this->nombre_vista = 'forminiciosesion';
    }

    public function comprobar() {
        $msj = null;

        if (!empty($_POST['correo']) && !empty($_POST['contrasenia'])) {
            $correo = $_POST['correo'];
            $contrasenia = $_POST['contrasenia'];

            $resultado = $this->identificacion->iniciarSesion($correo, $contrasenia);

            if ($resultado && is_array($resultado)) {
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

    public function mostrarFRG() {
        $this->nombre_vista = 'registro_form';
        $datos_vista = $this->identificacion->listarTipos();
        return ['tipos' => $datos_vista];
    }

    public function cerrarSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        $this->nombre_vista = 'forminiciosesion';
    }

    public function registrar() {
        if ($this->validarCampos()) {
            $num_usuario = $_POST['num_usuario'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $contrasenia = $_POST['contrasenia'];
            $tipo = $_POST['perfil'];

            if (!empty($_POST['webReconocimiento'])) {
                $webReconocimiento = $_POST['webReconocimiento'];
            } else {
                $webReconocimiento = null;
            }

            $msj = $this->identificacion->comprobarRegistro($num_usuario, $correo);

            if ($msj === "No hay duplicados, puede proceder con el registro.") {
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

    private function validarCampos() {
        if (!empty($_POST['num_usuario']) && !empty($_POST['nombre']) && !empty($_POST['correo']) && !empty($_POST['contrasenia'])
            && !empty($_POST['confirmarContrasenia'])) {
            return $_POST['contrasenia'] === $_POST['confirmarContrasenia'];
        } else {
            return false;
        }
    }
}

