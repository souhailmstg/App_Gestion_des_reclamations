<?php
session_start();
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=abhoer_;charset=utf8", "root", "");

// Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST['Nom']));
    $motDePasse = htmlspecialchars(trim($_POST['Mot-De-Passe']));
    $role = htmlspecialchars(trim($_POST['Role']));

    // Requête sans mot de passe hashé
    $sql = "SELECT * FROM utilisateurs WHERE nom = :nom  AND role = :role";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'role' => $role
    ]);

    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($utilisateur && password_verify($motDePasse,$utilisateur['mot_de_passe'])) {
        echo "<h2>Connexion réussie</h2>";
        
        switch ($role) {
            case 'admin':
                header("Location: ../Admin/Admin.html");
                break;
            
            default :    
            
            $_SESSION['nom'] = $utilisateur['nom'];
            $_SESSION['role'] = $utilisateur['role'];
            header('Location: ../Utilisateurs/acceuil.php');
            break;
        }
        exit;
    } else {
        echo "<p style='color:red;'>Nom, mot de passe ou rôle incorrect.</p>";
    }
}
?>
