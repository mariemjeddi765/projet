<?php
session_start();

// Inclure la base de données
require_once __DIR__ . "/../config/database.php";

$db = new Database();
$conn = $db->connect();

// Vérifier si l'admin existe
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => 'admin']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user){
    // Mettre à jour le mot de passe admin à 'admin123' (hashé)
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = :password, role = 'admin' WHERE username = 'admin'");
    $update->execute([':password' => $hash]);
    echo "Admin existant mis à jour. Username: admin / Password: admin123";
} else {
    // Créer l'admin si inexistant
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')");
    $insert->execute([':username' => 'admin', ':password' => $hash]);
    echo "Admin créé. Username: admin / Password: admin123";
}

echo "<br><a href='index.php'>Retour au site</a>";
