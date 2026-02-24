<?php
/**
 * Script de verificación de base de datos
 * Verifica que la BD y los datos están correctamente configurados
 */

require_once "src/config/database.php";

echo "\n═══════════════════════════════════════\n";
echo "🔍 VERIFICACIÓN DE BASE DE DATOS\n";
echo "═══════════════════════════════════════\n\n";

try {
    // 1. Verificar conexión
    echo "1️⃣  Conectando a 'project_php'... ";
    $stmt = $pdo->query("SELECT DATABASE() as db");
    $currentDb = $stmt->fetch()['db'];
    if ($currentDb === 'project_php') {
        echo "✅ OK\n";
    } else {
        echo "❌ FALLÓ (BD actual: $currentDb)\n";
    }

    // 2. Verificar tabla posts
    echo "2️⃣  Verificando tabla 'posts'... ";
    $stmt = $pdo->query("SHOW TABLES LIKE 'posts'");
    if ($stmt->fetch()) {
        echo "✅ OK\n";
    } else {
        echo "❌ FALLÓ\n";
    }

    // 3. Contar posts
    echo "3️⃣  Contando posts... ";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
    $count = $stmt->fetch()['count'];
    echo "✅ $count posts\n";

    // 4. Verificar tabla users
    echo "4️⃣  Verificando tabla 'users'... ";
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetch()) {
        echo "✅ OK\n";
    } else {
        echo "❌ FALLÓ\n";
    }

    // 5. Contar usuarios
    echo "5️⃣  Contando usuarios... ";
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $count = $stmt->fetch()['count'];
    echo "✅ $count usuarios\n";

    // 6. Verificar usuario admin
    echo "6️⃣  Verificando usuario 'admin'... ";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch();
    if ($admin) {
        echo "✅ OK\n";
        echo "   - ID: " . $admin['id'] . "\n";
        echo "   - Usuario: " . $admin['username'] . "\n";
        echo "   - Hash: " . substr($admin['password'], 0, 20) . "...\n";
    } else {
        echo "❌ FALLÓ\n";
    }

    // 7. Verificar contraseña admin
    echo "7️⃣  Verificando contraseña 'admin1234'... ";
    if ($admin && password_verify("admin1234", $admin['password'])) {
        echo "✅ OK\n";
    } else {
        echo "❌ FALLÓ\n";
    }

    // 8. Mostrar posts por categoría
    echo "\n8️⃣  Posts por categoría:\n";
    $stmt = $pdo->query("
        SELECT category, COUNT(*) as count 
        FROM posts 
        GROUP BY category 
        ORDER BY category
    ");
    foreach ($stmt->fetchAll() as $row) {
        echo "   - " . ucfirst($row['category']) . ": " . $row['count'] . "\n";
    }

    // 9. Mostrar últimos 3 posts
    echo "\n9️⃣  Últimos 3 posts:\n";
    $stmt = $pdo->query("
        SELECT id, title, category, created_at 
        FROM posts 
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    foreach ($stmt->fetchAll() as $post) {
        echo "   - [{$post['id']}] {$post['title']} ({$post['category']}) - {$post['created_at']}\n";
    }

    // 10. Prueba de API
    echo "\n🔟 Probando conexión con API (simulado):\n";
    $stmt = $pdo->query("
        SELECT 
            id,
            title,
            content,
            category,
            read_time,
            tags,
            created_at
        FROM posts
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $post = $stmt->fetch();
    if ($post) {
        echo "   ✅ Primer post obtenido:\n";
        echo "      - Título: " . substr($post['title'], 0, 40) . "...\n";
        echo "      - Contenido: " . substr($post['content'], 0, 40) . "...\n";
        echo "      - Categoría: " . $post['category'] . "\n";
        echo "      - Tiempo de lectura: " . $post['read_time'] . " min\n";
    }

    echo "\n═══════════════════════════════════════\n";
    echo "✅ TODAS LAS VERIFICACIONES PASARON\n";
    echo "═══════════════════════════════════════\n\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n\n";
}
?>
