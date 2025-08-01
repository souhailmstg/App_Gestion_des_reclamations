//_________________________________fonction d'orientation______________________________

function orienterReclamation(id) {
    document.getElementById('orientationId').value = id;
    document.getElementById('orientationDiv').style.display = 'block';
}

function fermerOrientation() {
    document.getElementById('orientationDiv').style.display = 'none';
}

function validerOrientation() {
    const id = document.getElementById('orientationId').value;
    const service = document.getElementById('serviceResponsable').value;

    if (!service) {
        alert("Veuillez choisir un service.");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Orientation enregistrée !");
            fermerOrientation();
            location.reload();
        } else {
            alert("Erreur lors de l’enregistrement.");
        }
    };
    xhr.send("action=orienter&id=" + encodeURIComponent(id) + "&service=" + encodeURIComponent(service));

}


//________________________________fonction de validation_____________________________________
function validerReclamation(id) {
    if (!confirm("Es-tu sûr de vouloir valider cette réclamation ?")) {
        return; // L'utilisateur a annulé
    }

    // Envoi d'une requête AJAX pour valider la réclamation
    fetch('index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=valider&id=' + encodeURIComponent(id)
    })

    .then(response => response.text())
    .then(data => {
        alert(data); // Affiche la réponse du serveur
        // Optionnel : tu peux recharger la page ou enlever la ligne validée
        location.reload();
    })
    .catch(error => {
        alert('Erreur: ' + error);
    });
}
//________________________________fonction de visualisation_____________________________________<script>
function voirReclamation(id, nom, mail, tel, description, fichier, type, cin, ice, commune, ville, region) {
    document.getElementById("voir_id").value = id;
    document.getElementById("voir_nom").value = nom;
    document.getElementById("voir_mail").value = mail;
    document.getElementById("voir_tel").value = tel;
    document.getElementById("voir_description").value = description;
    document.getElementById("voir_type").value = type;
    document.getElementById("voir_cin").value = cin;
    document.getElementById("voir_ice").value = ice;
    document.getElementById("voir_commune").value = commune;
    document.getElementById("voir_ville").value = ville;
    document.getElementById("voir_region").value = region;

    // Affichage conditionnel des champs CIN et ICE
     if (type === "Particulier") {
        document.getElementById('champCin').style.display = "block";
        document.getElementById('champIce').style.display = "none";
        document.getElementById('voir_cin').value = cin;
    } else {
        document.getElementById('champCin').style.display = "none";
        document.getElementById('champIce').style.display = "block";
        document.getElementById('voir_ice').value = ice;
    }
    // Gestion du lien fichier
    const lienFichier = document.getElementById('voir_fichier');
    if (fichier) {
        // Assure-toi que 'fichier' contient le chemin relatif vers 'uploads/...'
        lienFichier.href = '../Reclamation_formulaire/' + fichier; // Utilise le chemin relatif correct
        lienFichier.style.display = 'inline'; // Affiche le lien
    } else {
        lienFichier.style.display = 'none'; // Cache le lien s'il n'y a pas de fichier
    }
    document.getElementById("modalVoir").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function fermerModal() {
    document.getElementById("modalVoir").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}
//________________________________fonction de suppression_____________________________________
function deleteReclamation(id) {
    document.getElementById('delete_id').value = id;
    document.getElementById('modalDelete').style.display = 'block';
    document.getElementById('overlayDelete').style.display = 'block';
}

function fermerDeleteModal() {
    document.getElementById('modalDelete').style.display = 'none';
    document.getElementById('overlayDelete').style.display = 'none';
}

// Archiver via AJAX
function archiverReclamation() {
    const id = document.getElementById('delete_id').value;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Réclamation archivée !");
            location.reload();
        } else {
            alert("Erreur lors de l’archivage.");
        }
    };

    xhr.send("action=archiver&id=" + encodeURIComponent(id));
}

// Supprimer définitivement via AJAX
function supprimerDefinitivement() {
    const id = document.getElementById('delete_id').value;

    if (!confirm("Cette action est irréversible. Voulez-vous continuer ?")) return;

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert(xhr.responseText); // Affiche la réponse du serveur
            location.reload();
        } else {
            alert("Erreur lors de la suppression.");
        }
    };

    xhr.send("action=supprimer&id=" + encodeURIComponent(id));
}
//________________________________fonction de modification_____________________________________
function enregistrerModification(event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire

    const id = document.getElementById("voir_id").value;
    const nom = document.getElementById("voir_nom").value;
    const mail = document.getElementById("voir_mail").value;
    const tel = document.getElementById("voir_tel").value;
    const description = document.getElementById("voir_description").value;

    // Vérification minimale
    if (!id || !nom || !description) {
        alert("Veuillez remplir les champs obligatoires.");
        return;
    }

    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'modifier',
            id: id,
            nom_client: nom,
            mail: mail,
            tel: tel,
            description: description
        })
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        fermerModal(); // Ferme le modal
        location.reload(); // Recharge la page
    })
    .catch(error => {
        console.error("Erreur :", error);
        alert("Erreur lors de la modification.");
    });
}
//________________________________fonction de filtrage_____________________________________
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".search-input");
  const tableRows = document.querySelectorAll("tbody tr");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase();

    tableRows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });