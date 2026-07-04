<?php
        $database_url = "postgres://biblioteca_db_7m8e_user:0CUHd5d9gCOvTcbPJ3QZb09RHOl281fs@dpg-d9478n7lk1mc73ak6j1g-a/biblioteca_db_7m8e";

        $db = parse_url($database_url);
        $host = $db["host"];
        $port = "5432";
        $dbname = ltrim($db["path"], "/");
        $user = $db["user"];
        $pass = $db["pass"];

        try {
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            $conn = new PDO($dsn, $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Crear la tabla si no existe
            $conn->exec("CREATE TABLE IF NOT EXISTS libros (
                id SERIAL PRIMARY KEY,
                titulo VARCHAR(100) NOT NULL,
                autor VARCHAR(100) NOT NULL,
                anio_publicacion INT
            )");

            // 2. Insertar datos iniciales si la tabla está vacía (Opcional)
            $count = $conn->query("SELECT count(*) FROM libros")->fetchColumn();
            if ($count == 0) {
                $conn->exec("INSERT INTO libros (titulo, autor, anio_publicacion) VALUES
                ('1984', 'George Orwell', 1949),
                ('Don Quijote de la Mancha', 'Miguel de Cervantes', 1605)");
            }

            // 3. Realizar la consulta
            $stmt = $conn->query("SELECT * FROM libros");
            $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($libros) > 0) {
                echo '<table><tr><th>ID</th><th>Título</th><th>Autor</th><th>Año</th></tr>';
                foreach ($libros as $row) {
                    echo '<tr><td>' . $row['id'] . '</td><td>' . htmlspecialchars($row['titulo']) . '</td><td>' . htmlspecialchars($row['autor']) . '</td><td>' . $row['anio_publicacion'] . '</td></tr>';
                }
                echo '</table>';
            } else {
                echo "<p style='text-align:center;'>La base de datos está conectada pero no tiene libros.</p>";
            }
        } catch(PDOException $e) {
            echo "<p style='color: red; text-align: center;'>Error: " . $e->getMessage() . "</p>";
        }
        ?>