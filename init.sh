#!/bin/bash

# Iniciar servicios
service mysql start
service apache2 start

# Esperar a que MySQL esté listo (máximo 30 segundos)
echo "Esperando a que MySQL esté disponible..."
for i in {1..30}; do
    if mysql -e "SELECT 1" &>/dev/null; then
        break
    fi
    sleep 1
done

# Configurar base de datos y usuario
mysql -e "CREATE DATABASE IF NOT EXISTS biblioteca_db;"
mysql -e "CREATE USER 'biblioteca_user'@'%' IDENTIFIED BY 'secret123';"
mysql -e "GRANT ALL PRIVILEGES ON biblioteca_db.* TO 'biblioteca_user'@'%';"
mysql -e "FLUSH PRIVILEGES;"

# Crear tabla e insertar datos (con sintaxis corregida)
mysql biblioteca_db <<EOF
CREATE TABLE IF NOT EXISTS libros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  autor VARCHAR(100) NOT NULL,
  anio_publicacion INT
);

INSERT INTO libros (titulo, autor, anio_publicacion) VALUES
  ('Cien años de soledad', 'Gabriel García Márquez', 1967),
  ('El principito', 'Antoine de Saint-Exupéry', 1943),
  ('1984', 'George Orwell', 1949),
  ('Don Quijote de la Mancha', 'Miguel de Cervantes', 1605);
EOF

# Mantener el contenedor vivo (Apache ya está en segundo plano, pero necesitamos un proceso en foreground)
# En lugar de tail -f /dev/null, podemos usar apache2 en foreground:
# apache2-foreground
# O simplemente:
tail -f /dev/null