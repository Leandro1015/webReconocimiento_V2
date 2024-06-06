
CREATE TABLE tipoUsuario (
    tipoUsuario char(1) NOT NULL,
    nombreTipo varchar(50) NOT NULL,
    constraint pk_tipo_usuario PRIMARY KEY (tipoUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Tabla usuario
CREATE TABLE usuario (
    num_usuario tinyint unsigned NOT NULL, -- Número de usuario
    nombre varchar(80) NOT NULL,
    correo varchar(255) NOT NULL,
    contrasenia varchar(100) NOT NULL,
    webReconocimiento varchar(50) NULL,
    tipoUsuario char(1) NOT NULL,
    CONSTRAINT fk_tipo_usuario FOREIGN KEY (tipoUsuario) REFERENCES tipoUsuario(tipoUsuario), 
    constraint pk_usuario PRIMARY KEY (num_usuario),
    constraint correo_unico UNIQUE(correo),
    constraint web_unica UNIQUE(webReconocimiento)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Tabla reconocimiento
CREATE TABLE reconocimiento (
    idReconocimiento smallint unsigned AUTO_INCREMENT,
    momento varchar(100) NOT NULL,
    descripcion varchar(255) NOT NULL,
    idAlumnoEnvia tinyint unsigned NOT NULL,
    idAlumnoRecibe tinyint unsigned NOT NULL,
    constraint pk_recon PRIMARY KEY (idReconocimiento),
    constraint fk_alumno_env FOREIGN KEY (idAlumnoEnvia) REFERENCES usuario(num_usuario),
    constraint fk_alumno_rec FOREIGN KEY (idAlumnoRecibe) REFERENCES usuario(num_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;


-- Inserciones en tipoUsuario
INSERT INTO tipoUsuario (tipoUsuario, nombreTipo) VALUES ('A', 'Alumno');
INSERT INTO tipoUsuario (tipoUsuario, nombreTipo) VALUES ('P', 'Profesor');

-- Inserciones en usuario
INSERT INTO usuario (num_usuario, nombre, correo, contrasenia, webReconocimiento, tipoUsuario) VALUES 
(1, 'Cheyci', 'cheyci@example.com', '1234', 'cheyci123', 'A'),
(2, 'Lucia', 'lucia@example.com', '1234', NULL, 'A'),
(3, 'Pamela', 'pamela@example.com', '1234', 'pamela123', 'A'),
(4, 'Jason', 'jason@example.com', '1234', 'jason123', 'A'),
(5, 'Miguel', 'profesorx@example.com', '1234', NULL, 'P');

-- Inserciones en reconocimiento
INSERT INTO reconocimiento (momento, descripcion, idAlumnoEnvia, idAlumnoRecibe) VALUES 
('Momento 1', 'Reconocimiento de Cheyci a Lucia', 1, 2),
('Momento 2', 'Reconocimiento de Pamela a Jason', 3, 4),
('Momento 3', 'Reconocimiento de Miguel a Cheyci', 5, 1);



