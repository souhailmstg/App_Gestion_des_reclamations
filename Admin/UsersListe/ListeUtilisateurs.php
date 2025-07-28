<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Liste des utilisateurs</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
  <header>
    <h1>Agence du Bassin Hydraulique de l'Oum Er Rbia</h1>
    <div class="user">
      <a href="#">Se déconnecter</a>
      <img src="img/reglage.png" alt="User" class="avatar">
    </div>
  </header>

  <main>
    <div class="title-bar">
      <h2>Liste des utilisateurs</h2>
      <div class="actions">
        <a href="#" class="btn-add" onclick="openAddModal()">Ajouter un utilisateur <i class="bi bi-person-plus-fill"></i></a>
        <div class="searchDiv">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Rechercher..." class="search-input">
        </div>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>NOM D’UTILISATEURS</th>
          <th>MOT DE PASSE</th>
          <th>ROLE</th>
          <th>MODIFICATION & VISUALISATION</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Connexion à la base de données
        $conn = new mysqli("localhost", "root", "", "abhoer");
        if ($conn->connect_error) {
            die("Échec de la connexion : " . $conn->connect_error);
        }

        $sql = "SELECT * FROM utilisateurs";
        $result = $conn->query($sql);
        $i = 1;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["nom"]) . "</td>";
                echo "<td>••••••••••</td>";
                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                $id = $row['id_utilisateur'];
                $nom = htmlspecialchars(json_encode($row['nom']), ENT_QUOTES, 'UTF-8');
                $mot_de_passe = htmlspecialchars(json_encode($row['mot_de_passe']), ENT_QUOTES, 'UTF-8');
                $role = htmlspecialchars(json_encode($row['role']), ENT_QUOTES, 'UTF-8');

                echo '<td>
                  <a href="#"><img src="img/image copy.png" class="icon" onclick="openModal(' . $id . ', ' . $nom . ', ' . $mot_de_passe . ', ' . $role . ')"></a>
                  <a href="#"><img src="img/image copy 2.png" class="icon" onclick="deleteUser(' . $id . ')"></a>
                </td>';

                // echo '<td>
                // <a href="#"><img src="img/image copy.png" class="icon" onclick="openModal(\'' . $row['id_utilisateur'] . '\', \'' . htmlspecialchars($row['nom'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['mot_de_passe'], ENT_QUOTES) . '\', \'' . htmlspecialchars($row['role'], ENT_QUOTES) . '\')"></a>
                // <a href="#"><img src="img/image copy 2.png" class="icon"></a>
                // </td>';

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Aucun utilisateur trouvé</td></tr>";
        }

        $conn->close();
        ?>
      </tbody>
    </table>

    <!-- Modal -->
    <div id="userModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()"><img src="img/close-window-48.jpg" alt="annuler" id="AnnulerIcon"></span>
        <form onsubmit="saveChanges(event)">
          <label>Nom d’utilisateur :</label>
          <input type="text" id="modalNom">

          <label>Mot de passe :</label>
          <input type="text" id="modalPass">

          <label>Role :</label>
          <select id="modalRole">
            <option>Directeur</option>
            <option value="Secretaire">Secretaire</option>
            <option value="Chef_de_division_des_affaires_administrative_et_financiers">Chef de division des affaires administrative et financièrs</option>
            <option value="Chef_de_division_de_domaine_public_hydraulique">Chef de division de domaine public hydraulique</option>
            <option value="Chef_de_division_de_gestion_durable_des_ressources_en_eau">Chef de division de gestion durable des ressources en eau</option>
            <option value="Chef_de_division_d_evaluation_et_planification_des_RE">Chef de division d'évaluation et planification des RE</option>

            <option value="chefDeService(Marches_compabilite)">Chef de service(Marchés et compabilité)</option>
            <option value="chefDeService(RH_moyens_Generaux)">Chef de service(Ressources humaines et moyens généraux)</option>
            <option value="chefDeService(service_financement_programmation)">Chef de service(Service financement et programmation)</option>
            <option value="chefDeService(DPH)">Chef de service(Gestion du DPH)</option>
            <option value="chefDeService(affaires_juridiques_contentieux)">Chef de service(Des affaires juridiques et contentieux)</option>
            <option value="chefDeService(Aides_redevances)">Chef de service(Aides et redevances)</option>
            <option value="chefDeService(Gestion_devlopement_RE)">Chef de service(Gestion et developement des Re)</option>
            <option value="chefDeService(travaux_amenagements_hydrauliques)">Chef de service(travaux et aménagements hydrauliques)</option>
            <option value="chefDeService(planification_ressource_eau_etudes)">Chef de service(planification des ressources en eau et etudes)</option>
            <option value="chefDeService(suivi_eveluation_ressource_eau)">Chef de service(Suivi et évaluation des ressources en eau)</option>
            <option value="admin">Admin</option>
          </select>

          <button type="submit">Enregistrer</button>
        </form>
      </div>
    </div>  
      <!-- Modal de confirmation de suppression -->
    <!-- Modale de confirmation de suppression -->
    <div id="confirmDeleteModal" class="modal delete-modal">
      <div class="delete-modal-content">
        <p class="delete-warning">⚠️ Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
          <div class="delete-buttons">
            <button class="btn-cancel" onclick="cancelDelete()">Annuler</button>
            <button class="btn-delete" onclick="confirmDelete()">Supprimer</button>
          </div>
      </div>
    </div>

  </main>

  <script src="script.js"></script>
</body>
</html>
