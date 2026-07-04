<!DOCTYPE html>
<html>
<head>
    <title>Mi Biblioteca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f0f2f5;
        }
        h1 {
            color: #1a73e8;
            text-align: center;
        }
        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th {
            background: #1a73e8;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .mensaje {
            text-align: center;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #4caf50;
        }
        .mensaje-info {
            text-align: center;
            padding: 15px;
            background: #e3f2fd;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #2196f3;
        }
        .error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #f44336;
        }
        .total {
            text-align: right;
            margin-top: 10px;
            color: #666;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Libros Disponibles</h1>

    <?php
    // Tu conexión a PostgreSQL en Render
    $database_url = "postgres://biblioteca_db_7m8e_user:0CUHd5d9gCOvTcbPJ3QZb09RHOl281fs@dpg-d9478n7lk1mc73ak6j1g-a/biblioteca_db_7m8e";

    $db = parse_url($database_url);
    $host = $db["host"];
    $port = "5432";
    $dbname = ltrim($db["path"], "/");
    $user = $db["user"];
    $pass = $db["pass"];

    try {
        // Conectar a la base de datos
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear tabla si no existe
        $conn->exec("CREATE TABLE IF NOT EXISTS libros (
            id SERIAL PRIMARY KEY,
            titulo VARCHAR(200) NOT NULL,
            autor VARCHAR(150) NOT NULL,
            anio_publicacion INT
        )");

        // ============================================
        // 🔥 AQUÍ ESTÁ LA MAGIA: TRUNCATE + INSERT
        // ============================================
        
        // 1. Primero vaciamos la tabla (elimina todos los registros)
        $conn->exec("TRUNCATE TABLE libros RESTART IDENTITY");
        // RESTART IDENTITY reinicia el contador del ID a 1
        
        // 2. Lista COMPLETA de libros que quieres insertar
        $libros = [
            ['Cien años de soledad', 'Gabriel García Márquez', 1967],
            ['El principito', 'Antoine de Saint-Exupéry', 1943],
            ['1984', 'George Orwell', 1949],
            ['Don Quijote de la Mancha', 'Miguel de Cervantes', 1605],
        ];

        // 3. Insertar TODOS los libros
        $insertados = 0;
        foreach ($libros as $libro) {
            // Usamos comillas simples escapadas para evitar problemas
            $titulo = addslashes($libro[0]);
            $autor = addslashes($libro[1]);
            $anio_publicacion = (int)$libro[2];
            
            $conn->exec("INSERT INTO libros (titulo, autor, anio_publicacion) VALUES (
                '$titulo', 
                '$autor', 
                $anio_publicacion
            )");
            $insertados++;
        }

        // Mostrar todos los libros
        $result = $conn->query("SELECT * FROM libros ORDER BY id");
        $libros_mostrar = $result->fetchAll(PDO::FETCH_ASSOC);

        if (count($libros_mostrar) > 0) {
            echo '<table>';
            echo '<tr><th>ID</th><th>Título</th><th>Autor</th><th>Año</th></tr>';
            foreach ($libros_mostrar as $libro) {
                echo '<tr>';
                echo '<td>' . $libro['id'] . '</td>';
                echo '<td>' . htmlspecialchars($libro['titulo']) . '</td>';
                echo '<td>' . htmlspecialchars($libro['autor']) . '</td>';
                echo '<td>' . $libro['anio_publicacion'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<div class="total">📖 Total: ' . count($libros_mostrar) . ' libros</div>';
        }

    } catch(PDOException $e) {
        echo '<div class="error">❌ Error: ' . $e->getMessage() . '</div>';
    }
    ?>
</body>
</html>