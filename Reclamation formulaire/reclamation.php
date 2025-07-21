<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=abhoer", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Données du formulaire
    $nom = $_POST['nom_client'];
    $num_tel = $_POST['num-tel'];
    $mail = $_POST['mail'];
    $adress = $_POST['adress'];
    $type_user = $_POST['Type'];  // Corrigé ici
    $CIN = $_POST['CIN'];
    $ICE = $_POST['ICE'];
    $description = $_POST['description']; // Corrigé ici

    // Gestion du fichier
    $fichier_joint = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
        $uploads_dir = 'uploads';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        $tmp_name = $_FILES["fichier"]["tmp_name"];
        $name = basename($_FILES["fichier"]["name"]);
        $chemin_final = "$uploads_dir/$name";

        if (move_uploaded_file($tmp_name, $chemin_final)) {
            $fichier_joint = $chemin_final;
        }
    }

    // Insertion en BDD
    $sql = "INSERT INTO reclamation (nom_client,adress,user_type,CIN,ICE,telephone_client,email_client,description,piece_jointe)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $adress, $type_user, $CIN, $ICE, $num_tel, $mail, $description, $fichier_joint]);

    echo "✅ Réclamation envoyée avec succès.";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>
