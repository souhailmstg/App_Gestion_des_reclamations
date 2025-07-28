<?php
// Connexion à la base de données (à adapter avec tes identifiants)
echo "Test réussi";
$host = 'localhost';
$dbname = 'abhoer';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activer les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer les données POST de manière sécurisée
$id   = $_POST['id'] ?? null;
$nom  = $_POST['nom'] ?? '';
$pass = $_POST['pass'] ?? '';
$role = $_POST['role'] ?? '';
//__________________________________________Suppression d'un utilisateur___________________________________________
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        die("ID invalide pour suppression.");
    }

    try {
        $sql = "DELETE FROM utilisateurs WHERE id_utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        echo "Utilisateur supprimé avec succès.";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Erreur lors de la suppression : " . $e->getMessage();
    }

    exit;
}
//__________________________________________ Ajout d’un utilisateur________________________________________________
if (isset($_POST['action']) && $_POST['action'] === 'add') {

    $nom = $_POST['nom'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $role = $_POST['role'] ?? '';

    if (empty($nom) || empty($pass) || empty($role)) {
        http_response_code(400);
        die("Tous les champs sont requis pour l'ajout.");
    }

    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO utilisateurs (nom, mot_de_passe, role) VALUES (:nom, :pass, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'  => $nom,
            ':pass' => $hashedPass,
            ':role' => $role
        ]);
        echo "Nouvel utilisateur ajouté avec succès.";
    } catch (PDOException $e) {
        http_response_code(500);
        die("Erreur lors de l'ajout : " . $e->getMessage());
    }

    exit;
}

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    die("ID invalide.");
}
//______________________________________________Charger les modidication___________________________________________
try {
    if (!empty($pass)) {
        // Si un nouveau mot de passe est fourni, on le hash et on le met à jour
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateurs SET nom = :nom, mot_de_passe = :pass, role = :role WHERE id_utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'  => $nom,
            ':pass' => $hashedPass,
            ':role' => $role,
            ':id'   => $id
        ]);
    } else {
        // Sinon, on met à jour seulement le nom et le rôle
        $sql = "UPDATE utilisateurs SET nom = :nom, role = :role WHERE id_utilisateur = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'  => $nom,
            ':role' => $role,
            ':id'   => $id
        ]);
    }

    echo "Utilisateur mis à jour avec succès.";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur lors de la mise à jour : " . $e->getMessage();
}


?>
