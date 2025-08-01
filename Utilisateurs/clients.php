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
    $pdo = new PDO("mysql:host=localhost;dbname=abhoer_;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Filtrage des données par rôle
    if ($role === 'Directeur') {
        $stmt = $pdo->query("SELECT id, nom_client, date_reclamation, tel, mail, statut, description, fichier, Type, CIN, ICE, commune, ville, region FROM reclamation where statut='validé'");
    } elseif ($role === 'Secretaire') {
        $stmt = $pdo->query("SELECT id, nom_client, date_reclamation, tel, mail, statut, description, fichier, Type, CIN, ICE, commune, ville, region FROM reclamation where statut='nouveau'");
    } else {
        $stmt = $pdo->prepare("SELECT id, nom_client, date_reclamation, tel, mail, statut, description, fichier,Type, CIN, ICE, commune, ville, region FROM reclamation where statut='validé'");
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
            <a href="#" onclick="voirReclamation(<?= $rec['id'] ?>, 
                '<?= htmlspecialchars($rec['nom_client'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['mail'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['tel'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['description'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['fichier'] ?? '', ENT_QUOTES) ?>',   
                '<?= htmlspecialchars($rec['Type'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['CIN'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['ICE'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['commune'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['ville'] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($rec['region'] ?? '', ENT_QUOTES) ?>'
                )">
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


<!-- MODALE UNIQUE POUR LA VISUALISATION -->
<div id="modalVoir" style="display:none; position:fixed; top:10%; left:30%; width:40%; background:#fff; border:1px solid #ccc; padding:20px; z-index:1000;">
    <h3>Détails de la réclamation</h3>
    <form id="formVoir" method="post">
        <input type="hidden" name="action" value="modifier">
        <input type="hidden" name="id" id="voir_id">

        <label>Nom :</label>
        <input type="text" name="nom_client" id="voir_nom" required>
        <label>Type :</label>
        <input type="text" name="type" id="voir_type" required>
        <div id="champCin" style="display:none;">
            <label>CIN :</label>
            <input type="text" name="cin" id="voir_cin"><br>
        </div>

        <div id="champIce" style="display:none;">
            <label>ICE :</label>
            <input type="text" name="ice" id="voir_ice"><br>
        </div>

        <label>Email :</label>
        <input type="email" name="mail" id="voir_mail"><br>

        <label>Téléphone :</label>
        <input type="text" name="tel" id="voir_tel"><br>
        <label>Commune :</label>
        <input type="text" name="Commune" id="voir_commune">
        <label>Ville :</label>
        <input type="text" name="Ville" id="voir_ville">
        <label>Région :</label>
        <input type="text" name="Region" id="voir_region"><br>
        <label>Description :</label><br>
        <textarea name="description" id="voir_description" rows="4" cols="50"></textarea><br><br>

        <label>Fichier joint :</label><br>
        <a id="voir_fichier" href="#" target="_blank" style="display:none;">Voir le fichier</a><br><br>

        <?php if ($role === 'Directeur' || $role === 'Secretaire'): ?>
            <button type="submit" onclick="enregistrerModification(event)">Enregistrer</button>
        <?php endif; ?>
        <button type="button" onclick="fermerModal()">Fermer</button>
    </form>
</div>
<!-- MODALE DE SUPPRESSION -->
<div id="modalDelete" style="display:none; position:fixed; top:20%; left:35%; width:30%; background:#fff; border:1px solid #ccc; padding:20px; z-index:1000; text-align:center;">
    <h3>Que souhaitez-vous faire ?</h3>
    <input type="hidden" id="delete_id">
    <br><br>
    <button onclick="archiverReclamation()" class="btn btn-secondary">Archiver</button>
    <button onclick="supprimerDefinitivement()" class="btn btn-danger">Supprimer définitivement</button>
    <br><br>
    <button onclick="fermerDeleteModal()" class="btn btn-secondary">Annuler</button>
</div>
<!-- MODALE D'ORIENTATION -->
 <div id="orientationDiv" style="">
        <h5>Orienter la réclamation</h5>
        <input type="hidden" id="orientationId">
        <select id="serviceResponsable" class="form-select">
            <option value="">-- Choisir un service --</option>
            <option value="admin">Admin</option>
            <option value="Directeur">Directeur</option>
            <option value="Secretaire">Secretaire</option>
            <option value="Chef_de_division_des_affaires_administrative_et_financiers">Chef de division des affaires administrative et financièrs</option>
            <option value="Chef_de_division_de_domaine_public_hydraulique">Chef de division de domaine public hydraulique</option>
            <option value="Chef_de_division_de_gestion_durable_des_ressources_en_eau">Chef de division de gestion durable des ressources en eau</option>
            <option value="Chef_de_division_d_evaluation_et_planification_des_RE">Chef de division d'évaluation et planification des RE</option>
            <option value="chefDeService(Marches_compabilite)">Chef de service(Marchés et comptabilité)</option>
            <option value="chefDeService(RH_moyens_Generaux)">Chef de service(RH et moyens généraux)</option>
            <option value="chefDeService(service_financement_programmation)">Chef de service(financement et programmation)</option>
            <option value="chefDeService(DPH)">Chef de service(DPH)</option>
            <option value="chefDeService(affaires_juridiques_contentieux)">Chef de service(juridique et contentieux)</option>
            <option value="chefDeService(Aides_redevances)">Chef de service(aides et redevances)</option>
            <option value="chefDeService(Gestion_devlopement_RE)">Chef de service(gestion des RE)</option>
            <option value="chefDeService(travaux_amenagements_hydrauliques)">Chef de service(travaux hydrauliques)</option>
            <option value="chefDeService(planification_ressource_eau_etudes)">Chef de service(planification RE)</option>
            <option value="chefDeService(suivi_eveluation_ressource_eau)">Chef de service(suivi RE)</option>
        </select>
        <br>
        <br>
        <button onclick="validerOrientation()" class="btn btn-success">Enregistrer</button>
        <button onclick="fermerOrientation()" class="btn btn-secondary">Annuler</button>
    </div>
<div id="overlayDelete" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:999;" onclick="fermerDeleteModal()"></div>

<div id="overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:999;" onclick="fermerModal()">

</div>

</div>   
<script src="clients.js"></script>
</body>
</html>