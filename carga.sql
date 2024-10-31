USE mibase_itv;

-- Crear tabla de alumnos
CREATE TABLE alumno_itv (
    id_alumno_itv INT AUTO_INCREMENT PRIMARY KEY,
    nombre_itv VARCHAR(100) NOT NULL,
    fecha_nacimiento_itv DATE NOT NULL,
    telefono_itv VARCHAR(15),
    email_itv VARCHAR(100) NOT NULL
);

-- Insertar alumnos
INSERT INTO alumno_itv (nombre_itv, fecha_nacimiento_itv, telefono_itv, email_itv) VALUES
('Juan Pérez', '2000-01-15', '5551234567', 'juan@example.com'),
('María García', '1999-02-20', '5557654321', 'maria@example.com'),
('Luis Hernández', '2001-03-25', '5559876543', 'luis@example.com');

-- Crear tabla de materias
CREATE TABLE materias_itv (
    id_materia_itv INT AUTO_INCREMENT PRIMARY KEY,
    nombre_materia_itv VARCHAR(100) NOT NULL,
    descripcion_itv TEXT
);

-- Insertar materias
INSERT INTO materias_itv (nombre_materia_itv, descripcion_itv) VALUES
('Matemáticas', 'Materia básica de Matemáticas.'),
('Historia', 'Estudio de la historia mundial.'),
('Biología', 'Introducción a la biología.'),
('Química', 'Conceptos básicos de química.'),
('Literatura', 'Análisis de textos literarios.');

-- Crear tabla de calificaciones
CREATE TABLE calificaciones_itv (
    id_calificacion_itv INT AUTO_INCREMENT PRIMARY KEY,
    id_alumno_itv INT,
    id_materia_itv INT,
    calificacion_itv DECIMAL(5,2),
    FOREIGN KEY (id_alumno_itv) REFERENCES alumno_itv(id_alumno_itv),
    FOREIGN KEY (id_materia_itv) REFERENCES materias_itv(id_materia_itv)
);

-- Insertar calificaciones para cada alumno en cada materia
INSERT INTO calificaciones_itv (id_alumno_itv, id_materia_itv, calificacion_itv) VALUES
(1, 1, 85.50), (1, 2, 90.00), (1, 3, 78.00), (1, 4, 88.00), (1, 5, 92.00),
(2, 1, 75.00), (2, 2, 80.50), (2, 3, 82.00), (2, 4, 70.00), (2, 5, 76.00),
(3, 1, 95.00), (3, 2, 88.00), (3, 3, 91.00), (3, 4, 84.50), (3, 5, 89.00);
