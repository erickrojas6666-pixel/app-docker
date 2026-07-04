<!DOCTYPE html>
<html>
<head>
<title>Biblioteca - Práctica Docker</title>
<style>
table { border-collapse: collapse; width: 80%; margin: 20px auto; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>
</head>
    <body>
        <h1 style="text-align: center;">Libros Disponibles</h1>
        <?php
        $host = 'localhost';
        $dbname = 'biblioteca';
        $username = 'biblioteca_user';
        $password = 'secret123';
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM libros");
            $stmt->execute();
            
            // VERIFICACIÓN: ¿Hay datos?
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
    </body>
</html>