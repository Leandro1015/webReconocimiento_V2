<?php
    require_once 'conexion.php';

    class M_reconocimiento extends Conectar 
    {   
        public function enviar($idAlumnoEnvia, $idAlumnoRecibe, $momento, $descripcion) {
            // Consulta preparada para insertar un nuevo reconocimiento
            $sql = "INSERT INTO reconocimiento (idAlumnoEnvia, idAlumnoRecibe, momento, descripcion) 
                    VALUES (?, ?, ?, ?)";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
        
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular los parámetros a la consulta
                $consultaPreparada->bind_param("iiss", $idAlumnoEnvia, $idAlumnoRecibe, $momento, $descripcion);
                
                // Ejecutar la consulta
                $resultado = $consultaPreparada->execute();
                
                // Verificar si la ejecución fue exitosa
                if ($resultado) {
                    if ($consultaPreparada->affected_rows > 0) {
                        // Cerrar la consulta
                        $consultaPreparada->close();
                        return true;
                    } else {
                        // Cerrar la consulta
                        $consultaPreparada->close();
                        return "No se pudo insertar el reconocimiento, ningún registro afectado.";
                    }
                } else {
                    // Cerrar la consulta
                    $consultaPreparada->close();
                    return "Error al ejecutar la consulta: (".$this->conexion->errno.")".$this->conexion->error;
                }
            } else {
                return "Error al preparar la consulta: (".$this->conexion->errno.")".$this->conexion->error;
            }
        }

        public function obtenerAlumnos($idAlumnoEnvia) {
            // Consulta preparada para seleccionar alumnos que no son el remitente
            $sql = "SELECT num_usuario, nombre FROM usuario WHERE perfil = 'A' AND num_usuario != ? ORDER BY num_usuario ";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
        
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular el parámetro a la consulta
                $consultaPreparada->bind_param("i", $idAlumnoEnvia);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Obtener el resultado de la consulta
                $resultado = $consultaPreparada->get_result();
                
                $alumnos = [];
                if ($resultado && $resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $alumnos[] = $fila;
                    }
                }
                
                // Cerrar la consulta
                $consultaPreparada->close();
                
                return $alumnos;
            } else {
                return "Error al preparar la consulta: (" . $this->conexion->errno . ") " . $this->conexion->error;
            }
        }           

        public function obtenerReconocimientos($num_usuario) {
            // Consulta preparada para seleccionar reconocimientos por el id del alumno
            $sql = "SELECT idReconocimiento FROM reconocimiento WHERE idAlumnoRecibe = ?";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
        
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular el parámetro a la consulta
                $consultaPreparada->bind_param("i", $num_usuario);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Obtener el resultado de la consulta
                $resultado = $consultaPreparada->get_result();
                
                $reconocimientos = array();
        
                if ($resultado && $resultado->num_rows > 0) {
                    while ($fila = $resultado->fetch_assoc()) {
                        $reconocimientos[] = $fila;
                    }
                }
                
                // Cerrar la consulta
                $consultaPreparada->close();
                
                return $reconocimientos;
            } else {
                return "Error al preparar la consulta: (".$this->conexion->errno.") ".$this->conexion->error;
            }
        }        

        public function obtenerReconocimiento($id) {
            // Consulta preparada para seleccionar el reconocimiento por idReconocimiento
            $sql = "SELECT momento, descripcion FROM reconocimiento WHERE idReconocimiento = ?";
            
            // Preparar la consulta
            $consultaPreparada = $this->conexion->prepare($sql);
        
            // Verificar si la preparación de la consulta fue exitosa
            if ($consultaPreparada) {
                // Vincular el parámetro a la consulta
                $consultaPreparada->bind_param("i", $id);
                
                // Ejecutar la consulta
                $consultaPreparada->execute();
                
                // Obtener el resultado de la consulta
                $resultado = $consultaPreparada->get_result();
                
                // Verificar si se encontró un reconocimiento
                if ($resultado && $resultado->num_rows > 0) {
                    $consultaPreparada->close(); 
                    return $resultado->fetch_assoc();
                } else {
                    $consultaPreparada->close(); 
                    return null;
                } 
                // Cerrar la consulta
                   
            } else {
              
                return "Error al preparar la consulta: (".$this->conexion->errno.")".$this->conexion->error;
            }
        }

        public function ultimoReconocimiento($idAlumnoRecibe) {
            $SQL = "SELECT nombre FROM usuario WHERE num_usuario = ?";
            $consultaPreparada = $this->conexion->prepare($SQL);
        
            if ($consultaPreparada) {
                $consultaPreparada->bind_param("i", $idAlumnoRecibe);
                $consultaPreparada->execute();
        
                $nombreAlumno = null;
                $consultaPreparada->bind_result($nombreAlumno);
                $consultaPreparada->fetch();
                $consultaPreparada->close();
        
                return $nombreAlumno;
            } else {
                return null;
            }
        }    
    }