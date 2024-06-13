<?php
    require_once 'conexion.php';

    class M_identificacion extends Conectar
    {   
        public function iniciarSesion($correo, $contrasenia)
        {
            // Consulta preparada para buscar el usuario por correo
            $sql = "SELECT num_usuario, nombre, contrasenia, perfil FROM usuario WHERE correo = ?";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
            
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular los parámetros a la consulta
                $consultaPreparada->bind_param("s", $correo);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Obtener el resultado de la consulta
                $resultado = $consultaPreparada->get_result();
                
                // Verificar si se encontró un usuario
                if ($resultado->num_rows == 1) {
                    $fila = $resultado->fetch_assoc();
                    
                    // Obtener el hash de la contraseña almacenada
                    $contrasenia_hash = $fila['contrasenia'];
                    
                    // Verificar la contraseña ingresada contra el hash almacenado
                    if (password_verify($contrasenia, $contrasenia_hash)) {
                        // Eliminamos la contraseña del array antes de devolverlo
                        unset($fila['contrasenia']);
                    } else {
                        // Contraseña incorrecta
                        $fila = false;
                    }
                } else {
                    // No se encontró el usuario introducido
                    $fila = false;
                }
                
                // Cerrar la consulta
                $consultaPreparada->close();
                
                return $fila;
            } else {
                // Si la preparación de la consulta falla, retornar false
                return false;
            }
        }

        public function registrar($num_usuario, $nombre, $correo, $contrasenia, $webReconocimiento, $tipo)
        {
            $con_cifrada = password_hash($contrasenia, PASSWORD_DEFAULT); // Contraseña cifrada
            
            // Consulta preparada para insertar un nuevo alumno
            $sql = "INSERT INTO usuario (num_usuario, nombre, correo, contrasenia, webReconocimiento, perfil) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);

            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular los parámetros a la consulta
                $consultaPreparada->bind_param("isssss", $num_usuario, $nombre, $correo, $con_cifrada, $webReconocimiento, $tipo);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Verificar si la consulta fue exitosa y se afectaron filas
                if ($consultaPreparada->affected_rows > 0) {
                    $resultado = true;
                } else {
                    // Obtener el error
                    $resultado = "Error al registrar alumno: (".$this->conexion->errno.")".$this->conexion->error;
                }
                
                // Cerrar la consulta
                $consultaPreparada->close();
            } else {
                // Si la preparación de la consulta falla, retornar el error
                $resultado = "Error al preparar la consulta: (".$this->conexion->errno.")".$this->conexion->error;
            }
            
            return $resultado;
        }

        public function comprobarRegistro($num_usuario, $correo) {
            // Consulta preparada para verificar si el ID de alumno o el correo ya están registrados
            $sql = "SELECT num_usuario, correo FROM usuario WHERE num_usuario = ? OR correo = ?";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
        
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular los parámetros a la consulta
                $consultaPreparada->bind_param("is", $num_usuario, $correo);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Obtener el resultado de la consulta
                $resultado = $consultaPreparada->get_result();
                
                // Verificar si se encontraron filas
                if ($resultado->num_rows > 0) {
                    $fila = $resultado->fetch_assoc();
                    if ($fila['num_usuario'] === $num_usuario && $fila['correo'] === $correo) {
                        return "El correo y el ID de alumno ya están registrados. Por favor, utilice otros datos.";
                    } 
                    else
                        if ($fila['num_usuario'] === $num_usuario) {
                            return "El ID de alumno ya está registrado. Por favor, utilice otro ID.";
                    } 
                    else
                        if ($fila['correo'] === $correo) {
                            return "El correo ya está registrado. Por favor, utilice otro correo.";
                    }
                } 
                else {
                    return "No hay duplicados, puede proceder con el registro.";
                }
        
                // Cerrar la consulta
                $consultaPreparada->close();
            } else {
                // Si la preparación de la consulta falla, retornar el error
                return "Error al preparar la consulta: (".$this->conexion->errno.")".$this->conexion->error;
            }
        }

        /**
         * Obtiene una lista de tipos de usuario desde la base de datos.
         *
         * @return array Lista de tipos de usuario, cada elemento es un array asociativo con las claves 'perfil' y 'nombrePerfil'.
         */
        public function listarTipos() {
            $sql = "SELECT perfil, nombrePerfil FROM perfil";
            $resultado = $this->conexion->query($sql);

            $tipos = [];
            if ($resultado && $resultado->num_rows > 0) {
                while ($tipo = $resultado->fetch_assoc()) {
                    $tipos[] = $tipo;
                }
            }
            return $tipos;
        }
    }