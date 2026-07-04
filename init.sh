#!/bin/bash
# Iniciar servicios
service mysql start
service apache2 start
# Configurar MySQL
mysql -e "CREATE DATABASE IF NOT EXISTS biblioteca;"
mysql -e "CREATE USER IF NOT EXISTS 'biblioteca_user'@'localhost' IDENTIFIED BY
'secret123';"
# Asegúrate de usar 'root' o un usuario con permisos para crear otros
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'biblioteca_user'@'localhost' IDENTIFIED BY 'secret123' WITH GRANT OPTION;"
mysql -e "FLUSH PRIVILEGES;"
# Crear tabla e insertar datos
mysql biblioteca <<EOF
CREATE TABLE IF NOT EXISTS libros (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(100) NOT NULL,
autor VARCHAR(100) NOT NULL,
anio_publicacion INT
);
INSERT INTO libros (titulo, autor, anio_publicacion) VALUES
('Cien anos de soledad', 'Gabriel García Márquez', 1967),
('El principito', 'Antoine de Saint-Exupéry', 1943),
('1984', 'George Orwell', 1949),
('Don Quijote de la Mancha', 'Miguel de Cervantes', 1605);
EOF
# Mantener el contenedor en ejecución
tail -f /dev/null