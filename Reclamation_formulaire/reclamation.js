function gererChamp() {
      const type = document.getElementById('Type').value;
      const ChampCIN = document.getElementById('ChampCIN');
      const ChampICE = document.getElementById('ChampICE');
      if (type === "Entreprise") {
        ChampICE.style.display = 'block';
        ChampCIN.style.display = 'none';
      } else {
        ChampICE.style.display = 'none';
        ChampCIN.style.display = 'block';
      }
    }

    // Traductions
    const translations = {
      fr: {
        dir: "ltr",
        headerTitle: "Agence du Bassin Hydraulique de l'Oum Er Rbia",
        formTitle: "Formulaire de réclamation",
        nom: "Nom Complet :",
        type: "Type :",
        cin: "CIN :",
        ice: "ICE :",
        adress: "Adresse :",
        tel: "Téléphone :",
        mail: "Mail :",
        desc: "Description :",
        file: "Pièce jointe (optionnelle) :",
        fileNone: "Aucun fichier sélectionné",
        entreprise:"Entreprise",
        particulier:"Particulier",
        browse: "📎 Parcourir un fichier",
        submit: "Envoyer",
        successMsg: "Merci d'avoir soumis votre réclamation. Nous la traiterons dans les plus brefs délais.",
        closeBtn: "Quitter la page",
        errors: {
        nom_obligatoire: "Le nom est obligatoire.",
        cin_obligatoire: "Le numéro CIN est obligatoire.",
        desc_obligatoire: "La description est obligatoire.",
        mail_invalide: "L'adresse e-mail est invalide.",
        tel_invalide: "Le numéro de téléphone est invalide.",
        fichier_non_autorise: "Type de fichier non autorisé.",
        fichier_trop_gros: "Le fichier est trop volumineux (max 5 Mo).",
        fichier_erreur: "Erreur lors du téléchargement du fichier.",
        inconnu: "Une erreur inconnue s'est produite."
      }
      },
      ar: {
        dir: "rtl",
        headerTitle: "وكالة الحوض المائي لأم الربيع",
        formTitle: "نموذج الشكاية",
        nom: "الاسم الكامل :",
        type: "النوع :",
        cin: "ر.ب.و (CIN) :",
        ice: "المعرف الموحد للمقاولة (ICE) :",
        adress: "العنوان :",
        tel: "الهاتف :",
        mail: "البريد الإلكتروني :",
        desc: "وصف الشكاية :",
        file: "ملف مرفق (اختياري) :",
        fileNone: "لم يتم اختيار أي ملف",
        entreprise:"مقاولة",
        particulier:"فرد",
        browse: "📎 اختيار ملف",
        submit: "إرسال",
        successMsg: "شكراً لتقديمكم الشكاية. سنقوم بمعالجتها في أقرب وقت ممكن.",
        closeBtn: "الخروج من الصفحة",
        errors: {
        nom_obligatoire: "الاسم الكامل مطلوب.",
        cin_obligatoire: "رقم البطاقة الوطنية مطلوب.",
        desc_obligatoire: "وصف الشكاية مطلوب.",
        mail_invalide: "البريد الإلكتروني غير صالح.",
        tel_invalide: "رقم الهاتف غير صالح.",
        fichier_non_autorise: "نوع الملف غير مسموح به.",
        fichier_trop_gros: "الملف كبير جدًا (الحد الأقصى 5 ميغابايت).",
        fichier_erreur: "حدث خطأ أثناء تحميل الملف.",
        inconnu: "حدث خطأ غير معروف."
    }
      }
    };

    function switchLanguage(lang) {
      const t = translations[lang];
      document.documentElement.dir = t.dir;
      document.documentElement.lang = lang;
      document.getElementById("langInput").value = lang;
      document.getElementById("header-title").textContent = t.headerTitle;
      document.getElementById("form-title").textContent = t.formTitle;
      document.getElementById("label-nom").textContent = t.nom;
      document.getElementById("user_type").textContent = t.type;
      document.getElementById("label-cin").textContent = t.cin;
      document.getElementById("label-ice").textContent = t.ice;
      document.getElementById("label-adress").textContent = t.adress;
      document.getElementById("label-tel").textContent = t.tel;
      document.getElementById("label-mail").textContent = t.mail;
      document.getElementById("label-desc").textContent = t.desc;
      document.getElementById("label-file").textContent = t.file;
      document.getElementById("fileName").textContent = t.fileNone;
      document.getElementById("btn-submit").textContent = t.submit;
      document.getElementById("opt-entreprise").textContent = t.entreprise;
      document.getElementById("opt-particulier").textContent = t.particulier;
      document.getElementById("browse-span").textContent = t.browse;
      if (lang === "fr") {
    document.getElementById("btn-fr").style.display = "none";
    document.getElementById("btn-ar").style.display = "inline-block";
  } else {
    document.getElementById("btn-ar").style.display = "none";
    document.getElementById("btn-fr").style.display = "inline-block";
  }
  document.getElementById("mailInput").dir = "ltr";
    }

    // Gestion fichier sélectionné
    window.addEventListener("DOMContentLoaded", function () {
      // Changement type client
        const lang = localStorage.getItem("lang") || "fr";
        switchLanguage(lang);
      document.getElementById("Type").addEventListener("change", gererChamp);
      gererChamp(); // au chargement

      // Affichage nom du fichier
      const input = document.getElementById("fileInput");
      const fileName = document.getElementById("fileName");
      input.addEventListener("change", () => {
        if (input.files.length > 0) {
          fileName.textContent = input.files[0].name;
        } else {
          fileName.textContent = translations[document.documentElement.lang].fileNone;
        }
      });
    });
    /*Ajax handling*/
  
  document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("reclamationForm");
  const fileInput = document.getElementById("fileInput");
  const fileNameSpan = document.getElementById("fileName");

  // Affiche le nom du fichier sélectionné
  fileInput.addEventListener("change", function () {
    fileNameSpan.textContent = fileInput.files[0]?.name || "Aucun fichier sélectionné";
  });

  // Envoi AJAX du formulaire
  form.addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(form);
  const lang = document.documentElement.lang;
  const t = translations[lang];
  fetch("reclamation.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if(data.status === "success") {
      document.getElementById("FormContainer").style.display = "none";

      const successDiv = document.getElementById("successMessage");
      successDiv.style.display = "block";
      successDiv.innerHTML = `
        <div class="alert alert-success" role="alert" style="direction: ${t.dir}; text-align: ${t.dir === 'rtl' ? 'right' : 'left'}">
              <h4 class="alert-heading">✅ ${t.formTitle}</h4>
              <p>${t.successMsg}</p>
              <hr>
              <button class="btn btn-primary" onclick="window.location.href='../Acceuil/Acceuil.html'">${t.closeBtn}</button>
        </div>
      `;
    } else {
      const code = data.code || "inconnu";
      const errorMessage = t.errors[code] || t.errors.inconnu;
      alert("❌: " + errorMessage);
    }
  })
  .catch(error => {
    console.error("Erreur AJAX :", error);
    alert("❌: "+(t.errors.inconnu));
  });
});
});
