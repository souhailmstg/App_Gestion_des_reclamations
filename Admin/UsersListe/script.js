console.log("chargement de données succés");
let currentUserId = null;
let isAddMode = false; // ← à ajouter
// fonction d'ovreture de eye icon
function openModal(id, nom, motDePasse, role) {
  currentUserId = id;
  document.getElementById("modalNom").value = nom;
  document.getElementById("modalPass").value = '';
  document.getElementById("modalRole").value = role;

  document.getElementById("userModal").style.display = "block";
}
function openAddModal() {
  isAddMode = true;
  currentUserId = null;
  document.getElementById('modalNom').value = '';
  document.getElementById('modalPass').value = '';
  document.getElementById('modalRole').value = 'admin';
  document.getElementById('userModal').style.display = 'block';
}

  // foncltion de fermeture de eye icon
  function closeModal() {
    isAddMode = false; // Réinitialiser après fermeture
    document.getElementById("userModal").style.display = "none";
  }
  /*Save Chnges function*/
  
  function saveChanges(event) {
  event.preventDefault();

  const nom = document.getElementById("modalNom").value;
  const pass = document.getElementById("modalPass").value;
  const role = document.getElementById("modalRole").value;

  const formData = new FormData();
  formData.append("nom", nom);
  formData.append("pass", pass);
  formData.append("role", role);

  if (!isAddMode) {
    formData.append("id", currentUserId);
  } else {
    formData.append("action", "add");
  }

  fetch("Admin.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) throw new Error("Erreur serveur");
      return response.text();
    })
    .then((data) => {
      alert(data);
      closeModal();
      location.reload();
    })
    .catch((error) => {
      console.error("Erreur:", error);
      alert("Une erreur est survenue !");
    });
}

//delete handling
  function deleteUser(id) {
      userIdToDelete = id;
      document.getElementById("confirmDeleteModal").style.display = "block";
    }

    function cancelDelete() {
      userIdToDelete = null;
      document.getElementById("confirmDeleteModal").style.display = "none";
    }

    function confirmDelete() {
      if (!userIdToDelete) return;
      const formData = new FormData();
      formData.append("action", "delete");
      formData.append("id", userIdToDelete);

      fetch("Admin.php", {
        method: "POST",
        body: formData,
      })
      .then(response => {
        if (!response.ok) throw new Error("Erreur de suppression");
        return response.text();
      })
      .then(data => {
        alert(data);
        location.reload();
      })
      .catch(error => {
        alert("Erreur lors de la suppression");
        console.error(error);
      });

      document.getElementById("confirmDeleteModal").style.display = "none";
      userIdToDelete = null;
    }
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

  // Fermer si on clique en dehors
  window.onclick = function(event) {
    var modal = document.getElementById("userModal");
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

