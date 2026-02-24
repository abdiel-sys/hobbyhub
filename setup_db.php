<?php
// Script para verificar y crear la base de datos

try {
    // Conectar a MySQL sin especificar BD
    $pdo = new PDO(
        "mysql:host=localhost;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "✓ Conectado a MySQL\n\n";

    // Crear base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS project_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Base de datos 'project_php' lista\n\n";

    // Seleccionar la BD
    $pdo->exec("USE project_php");

    // Crear tabla posts
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content LONGTEXT NOT NULL,
            category ENUM('cocina','viajes','gaming') NOT NULL,
            read_time INT,
            created_at DATE,
            tags VARCHAR(255),
            INDEX idx_category (category),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Tabla 'posts' creada\n\n";

    // Crear tabla users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✓ Tabla 'users' creada\n\n";

    // Verificar posts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
    $result = $stmt->fetch();
    $postCount = $result['count'];

    if ($postCount === 0) {
        echo "📝 Insertando posts de ejemplo...\n";
        
        $posts = [
            // Cocina
            [
                'title' => '3 recetas rápidas para cuando no quieres complicarte',
                'content' => 'Pasta al ajo, quesadillas con guacamole y avena con frutas...',
                'category' => 'cocina',
                'read_time' => 7,
                'created_at' => '2026-01-19',
                'tags' => 'pasta,recetas,rápidas'
            ],
            [
                'title' => 'Desayunos rápidos para antes de clases',
                'content' => 'Si no tienes mucho tiempo por la mañana, estos desayunos te salvan: licuado de plátano con avena, tostadas con huevo y fruta picada con yogurt.',
                'category' => 'cocina',
                'read_time' => 5,
                'created_at' => '2026-01-10',
                'tags' => 'desayuno,avena,rápido,estudiantes'
            ],
            [
                'title' => 'Comidas baratas para la semana',
                'content' => 'Planear comidas económicas no significa comer mal. Arroz con verduras, lentejas y pollo al horno pueden rendir toda la semana.',
                'category' => 'cocina',
                'read_time' => 6,
                'created_at' => '2026-01-12',
                'tags' => 'comida,barato,planificación'
            ],
            // Viajes
            [
                'title' => 'Cómo viajar ligero sin olvidar lo importante',
                'content' => 'Viajar ligero te ahorra estrés. Lleva ropa versátil, evita zapatos extra y usa bolsas pequeñas para organizar todo.',
                'category' => 'viajes',
                'read_time' => 4,
                'created_at' => '2026-01-08',
                'tags' => 'viajar,ligero,maleta,organización'
            ],
            [
                'title' => 'Lugares económicos para visitar un fin de semana',
                'content' => 'No necesitas mucho dinero para salir. Pueblos mágicos, playas cercanas y zonas naturales son excelentes opciones.',
                'category' => 'viajes',
                'read_time' => 5,
                'created_at' => '2026-01-14',
                'tags' => 'fin de semana,económico,escapada'
            ],
            // Gaming
            [
                'title' => 'Errores comunes al jugar muchas horas',
                'content' => 'Jugar demasiado puede afectar tu rendimiento. Evita sesiones eternas, descansa la vista y mantén horarios claros.',
                'category' => 'gaming',
                'read_time' => 6,
                'created_at' => '2026-01-09',
                'tags' => 'gaming,salud,tiempo,descanso'
            ],
            [
                'title' => 'Cómo equilibrar videojuegos y estudios',
                'content' => 'Estudiar y jugar sí es posible. Establece metas diarias, usa el gaming como recompensa y mantén una rutina.',
                'category' => 'gaming',
                'read_time' => 7,
                'created_at' => '2026-01-16',
                'tags' => 'rutina,estudio,gamer,organización'
            ],
            [
                'title' => 'Organiza tu tiempo libre en solo 30 minutos',
                'content' => 'Dedicar 30 minutos al día a un hobby mejora tu ánimo. Puedes cocinar algo rápido, jugar una partida o planear un viaje.',
                'category' => 'gaming',
                'read_time' => 5,
                'created_at' => '2026-01-18',
                'tags' => 'tiempo,hobbies,organización,30min'
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO posts (title, content, category, read_time, created_at, tags)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($posts as $post) {
            $stmt->execute([
                $post['title'],
                $post['content'],
                $post['category'],
                $post['read_time'],
                $post['created_at'],
                $post['tags']
            ]);
        }

        echo "✓ " . count($posts) . " posts insertados\n\n";
    } else {
        echo "ℹ️  Ya existen $postCount posts en la base de datos\n\n";
    }

    // Verificar usuarios
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch();
    $userCount = $result['count'];

    if ($userCount === 0) {
        echo "👤 Creando usuario admin...\n";
        
        $adminPassword = password_hash("admin1234", PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $adminPassword]);
        
        echo "✓ Usuario 'admin' creado\n";
        echo "   Contraseña: admin1234\n\n";
    } else {
        echo "ℹ️  Ya existen $userCount usuarios en la base de datos\n\n";
    }

    // Mostrar estadísticas finales
    echo "═══════════════════════════════════════\n";
    echo "📊 ESTADÍSTICAS FINALES\n";
    echo "═══════════════════════════════════════\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
    echo "Posts: " . $stmt->fetch()['count'] . "\n";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    echo "Usuarios: " . $stmt->fetch()['count'] . "\n";
    
    $stmt = $pdo->query("SELECT category, COUNT(*) as count FROM posts GROUP BY category");
    $categories = $stmt->fetchAll();
    echo "\nPor categoría:\n";
    foreach ($categories as $cat) {
        echo "  - " . ucfirst($cat['category']) . ": " . $cat['count'] . "\n";
    }

    echo "\n✅ Base de datos lista para usar\n";

} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage() . "\n");
}
?>
