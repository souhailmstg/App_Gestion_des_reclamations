<?php
$pdo = new PDO("mysql:host=localhost;dbname=gestion_reclamations", "root", "");

// Données du formulaire
$nom = $_POST['nom_client'];
$prenom=$_POST['prenom_client'];
$num_tel=$_POST['num-tel'];
$mail=$_POST['mail'];
$adress=$_POST['adress'];
$Type=$_POST['Type'];
$CIN=$_POST['CIN'];
$ICE=$_POST['ICE'];
$desc = $_POST['description'];

// Gestion du fichier
$fichier_joint = null;
if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
    $uploads_dir = 'uploads';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir); // Créer le dossier s’il n’existe pas
    }

    $tmp_name = $_FILES["fichier"]["tmp_name"];
    $name = basename($_FILES["fichier"]["name"]);
    $chemin_final = "$uploads_dir/$name";
    move_uploaded_file($tmp_name, $chemin_final);
    $fichier_joint = $chemin_final;
}

// Insertion en BDD
$sql = "INSERT INTO reclamation (nom_client,adress,type_user,CIN,ICE,num_tel,mail, desctiption, fichier_joint)
        VALUES (?, ?, ?, ?,?,?,?,?,?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nom, $adress,$type_user,$CIN,$ICE,$num_tel,$mail, $description, ,$fichier_joint]);

echo "✅ Réclamation envoyée avec succès.";
?>
