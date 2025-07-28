<!DOCTYPE html>
<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['role'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion
    exit;
}

$role = $_SESSION['role'];

try {
    // Connexion DB avec UTF-8 pour supporter le français et l'arabe
    $pdo = new PDO("mysql:host=localhost;dbname=abhoer;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Filtrage des données par rôle
    if ($role === 'Directeur') {
        $stmt = $pdo->query("SELECT id, nom_client, date_reclamation, tel, mail, statut FROM reclamation");
    } elseif ($role === 'Secretaire') {
        $stmt = $pdo->query("SELECT id, nom_client, date_reclamation, tel, mail, statut FROM reclamation WHERE statut = 'nouveau'");
    } else {
        $stmt = $pdo->prepare("SELECT id, nom_client, date_reclamation, tel, mail, statut FROM reclamation WHERE service_responsable = :role");
        $stmt->execute(['role' => $role]);
    }
    $reclamations = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage(), 3, 'errors.log');
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    </head>
<!-- Affichage des réclamations (comme dans ton image 2) -->
<body>
    <header>
    <h1>Agence du Bassin Hydraulique de l'Oum Er Rbia</h1>
    <div class="user">
      <!-- <a href="#">Se déconnecter</a> -->
      <img src="img/administrator-2-48.gif" alt="User" class="avatar">
    </div>
  </header>
  <div class="title-bar">
      <h2>Liste des réclamations</h2>
        <div class="searchDiv">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Rechercher..." class="search-input">
        </div>
      </div>
    </div>
    
<table>
    <tr>
        <th>#</th><th>Nom</th><th>Date</th><th>Numéro</th><th>Courriel</th><th>Statut</th><th>Actions</th>
    </tr>
    <?php foreach ($reclamations as $rec) : ?>
        <tr>
            <td><?= htmlspecialchars($rec['id']) ?></td>
            <td><?= htmlspecialchars($rec['nom_client']) ?></td>
            <td><?= htmlspecialchars($rec['date_reclamation']) ?></td>
            <td><?= htmlspecialchars($rec['tel'] ?? '-') ?></td>
            <td><?= htmlspecialchars($rec['mail'] ?? '-') ?></td>
            <td><?= htmlspecialchars($rec['statut']) ?></td>
        <td>
            <!-- Visualisation -->
            <a href="voir_reclamation.php?id=<?= $rec['id'] ?>">
                <img src="img/image copy.png" class="icon" title="Voir">
            </a>

        <?php if ($role === 'Directeur') : ?>
            <!-- Suppression -->
            <a href="#" onclick="deleteReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 2.png" class="icon" title="Supprimer">
            </a>
            <!-- Orientation -->
            <a href="#" onclick="orienterReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 3.png" class="icon" title="Orienter">
            </a>

            <?php elseif ($role === 'Secretaire') : ?>
            <!-- Suppression -->
            <a href="#" onclick="deleteReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 2.png" class="icon" title="Supprimer">
            </a>
            <!-- Validation -->
            <a href="#" onclick="validerReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 5.png" class="icon" title="Valider">
            </a>

            <?php else : ?>
            <!-- Accepter -->
            <a href="#" onclick="accepterReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 5.png" class="icon" title="Accepter">
            </a>
            <!-- Refuser -->
            <a href="#" onclick="refuserReclamation(<?= $rec['id'] ?>)">
                <img src="img/image copy 4.png" class="icon" title="Refuser">
            </a>
        <?php endif; ?>
        </td>

        </tr>
    <?php endforeach; ?>
</table>
</div>   
</body>
</html>