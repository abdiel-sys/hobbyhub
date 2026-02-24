<?php
// Actualizar el hash del admin
require_once "src/config/database.php";

try {
    $newHash = "\$2y\$10\$zaja0Wbaf13rG7qcLaT20.DLshp4MJexT2O.hcklVk8c5umGvw7pK";
    
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$newHash, 'admin']);
    
    echo "✅ Hash de contraseña del admin actualizado\n";
    
    // Verificar que funciona
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();
    
    if (password_verify("admin1234", $user['password'])) {
        echo "✅ Contraseña 'admin1234' verificada exitosamente\n";
    } else {
        echo "❌ Error al verificar contraseña\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
