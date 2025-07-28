<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['role'])) {
    header('Location: connexion.html');
    exit();
}
$nom = $_SESSION['nom'];
$role = $_SESSION['role'];
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style/style.css"> <!-- correction ici -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <section>
    <div class="container-fluid"> <!-- Ajout de container -->
        <div id="UpDiv" class="row">
            <div class="col-md-10">
                <h4 id="header-title">Agence du Bassin Hydraulique de l'Oum Er Rbia</h4>
            </div>
            <div class="col-md-2">
               <a id="ConnexionLink" href="../Connexion/connexion.html">
                <img id="connexionImg" src="img/administrator-2-48.gif" alt="admin image">
                <span>Se deconnecter</span></a>
            </div>
        </div>
        <h4 id="title-page">Bienvenue,
                    <?php
                        echo "Mme/M. $role";
                    ?>
                </h4>
        <div id="BottomDiv" class="row">
            
                <a id="RecalamationLink" href="clients.php" target="_blank">
                <div class="FormContainer">
                    <img id="FormImg" src="img/form-352x431.png">
                       
                </div>
                <span id="form-span">Liste des réclamations</span></a>
           

            
                <a id="RecalamationLink" href="../Reclamation formulaire/reclamation.html" target="_blank">
                <div class="FormContainer">
                    <img id="FormImg2" src="img/image.png">       
                </div>
                <span id="Table-span">Table de bord</span></a>
            
        </div>
    </div>
    </section>
    <script src="Acceuil.js"></script>
</body>
</html>