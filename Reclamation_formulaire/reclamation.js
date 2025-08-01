// Gestion des champs CIN/ICE
console.log("Script chargé !");
function gererChamp() {
  const type = document.getElementById('Type').value;
  const ChampCIN = document.getElementById('ChampCIN');
  const ChampICE = document.getElementById('ChampICE');
  const inputCIN = document.getElementById('CIN');

  if (type === "Entreprise") {
    ChampICE.style.display = 'block';
    ChampCIN.style.display = 'none';
    inputCIN.removeAttribute('required');
  } else {
    ChampICE.style.display = 'none';
    ChampCIN.style.display = 'block';
    inputCIN.setAttribute('required', 'required');
  }
}

// Bien attendre le chargement du DOM :
window.addEventListener("DOMContentLoaded", () => {
  document.getElementById("Type").addEventListener("change", gererChamp);
  gererChamp();
});


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
    commune: "Commune de la réclamation :",
    city: "Ville de la réclamation :",
    region: "Région de la réclamation :",
    file: "Pièce jointe (optionnelle) :",
    fileNone: "Aucun fichier sélectionné",
    entreprise: "Entreprise",
    particulier: "Particulier",
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
      inconnu: "Une erreur inconnue s'est produite.",
      geocode_failed: "Impossible de détecter la ville ou la région à partir de la description."
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
    entreprise: "مقاولة",
    particulier: "فرد",
    commune: "جماعة الشكاية :",
    city: "مدينة الشكاية :",
    region: "جهة الشكاية :",
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
      inconnu: "حدث خطأ غير معروف.",
      geocode_failed: "تعذر تحديد المدينة أو الجهة من الوصف."
    }
  }
};

// Gestion du changement de langue
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
  document.getElementById("city-label").textContent = t.city;
  document.getElementById("region-label").textContent = t.region;
  document.getElementById("commune-label").textContent = t.commune;
  
  if (lang === "fr") {
    document.getElementById("btn-fr").style.display = "none";
    document.getElementById("btn-ar").style.display = "inline-block";
  } else {
    document.getElementById("btn-ar").style.display = "none";
    document.getElementById("btn-fr").style.display = "inline-block";
  }
  document.getElementById("mailInput").dir = "ltr";
}
//Chercher de la ville 
const villesBassin = [
  { fr: "Béni Mellal", ar: "بني ملال" },
  { fr: "Khénifra", ar: "خنيفرة" },
  { fr: "Kasba Tadla", ar: "قصبة تادلة" },
  { fr: "Fkih Ben Salah", ar: "الفقيه بن صالح" },
  { fr: "Azilal", ar: "أزيلال" },
  { fr: "Settat", ar: "سطات" },
  { fr: "Khouribga", ar: "خريبكة" },
  { fr: "El Kelaâ des Sraghna", ar: "قلعة السراغنة" },
  { fr: "Azemmour", ar: "أزمور" },
  { fr: "Casablanca", ar: "الدار البيضاء" },
  { fr: "El Jadida", ar: "الجديدة" },
  { fr: "Safi", ar: "آسفي" },
  { fr: "Marrakech", ar: "مراكش" }
];

// Fonction qui détecte la ville dans le texte selon la langue
function detecterVille(description, lang) {
  const texte = description.toLowerCase();
  for (const ville of villesBassin) {
    const nomVille = lang === "ar" ? ville.ar : ville.fr;
    // On compare en minuscule, sans tenir compte des accents simples (optionnel)
    if (texte.includes(nomVille.toLowerCase())) {
      return nomVille;
    }
  }
  return ''; // pas trouvé
}

async function geocodeDescription() {
  const descriptionInput = document.getElementById('descriptionInput').value;
  const lang = document.documentElement.lang || 'fr';
  const t = translations[lang];

  if (!descriptionInput) {
    document.getElementById('ville-field').value = '';
    document.getElementById('region-field').value = '';
    return { city: '', region: '' };
  }

  // 1) Détection locale de la ville
  const villeDetectee = detecterVille(descriptionInput, lang);
  document.getElementById('ville-field').value = villeDetectee;
  // Geocoding avec OpenCage API
  const apiKey = '1bb71a36722c4d05a9aa4561d74af13c'; // Remplacez par votre clé API OpenCage (stockez côté serveur pour la sécurité)

  try {
    // 2) Appel API pour détecter la région seulement si ville détectée
    if (villeDetectee) {
      const url = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(villeDetectee)}&key=${apiKey}&language=${lang}&countrycode=MA`;
      const response = await fetch(url);
      const data = await response.json();

      if (data.results && data.results.length > 0) {
        const result = data.results[0];
        const region = result.components.state || result.components.region || '';
        document.getElementById('region-field').value = region;
        return { city: villeDetectee, region };
      } else {
        document.getElementById('region-field').value = '';
        console.log('❌: ' + t.errors.geocode_failed);
        return { city: villeDetectee, region: '' };
      }
    } else {
      // Pas de ville détectée localement
      document.getElementById('region-field').value = '';
      return { city: '', region: '' };
    }
  } catch (error) {
    console.error('Erreur API OpenCage :', error);
    document.getElementById('region-field').value = '';
    return { city: villeDetectee, region: '' };
  }
}



// Gestion des événements
document.addEventListener("DOMContentLoaded", function () {
  // Initialisation langue
  const lang = localStorage.getItem("lang") || "fr";
  switchLanguage(lang);

  // Gestion type client
  document.getElementById("Type").addEventListener("change", gererChamp);
  gererChamp();

  // Affichage nom du fichier
  const fileInput = document.getElementById("fileInput");
  const fileName = document.getElementById("fileName");
  fileInput.addEventListener("change", () => {
    fileName.textContent = fileInput.files[0]?.name || translations[document.documentElement.lang].fileNone;
  });

  // Déclencher la géolocalisation sur changement de description
  const descriptionInput = document.getElementById("descriptionInput");
  descriptionInput.addEventListener("input", debounce(geocodeDescription, 500));

  // Envoi AJAX du formulaire
  const form = document.getElementById("reclamationForm");
 
  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    const lang = document.documentElement.lang;
    const t = translations[lang];

    // Effectuer la géolocalisation avant l'envoi
    const { city, region } = await geocodeDescription();
    formData.append('ville', city);
    formData.append('region', region);

    try {
        const response = await fetch("reclamation.php", {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (data.status === "success") {
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
            form.reset(); // Reset form after success
            document.getElementById("fileName").textContent = t.fileNone;
        } else {
            const code = data.code || "inconnu";
            const errorMessage = t.errors[code] || t.errors.inconnu;
            alert("❌: " + errorMessage);
        }
    } catch (error) {
        console.error("Erreur AJAX :", error);
        alert("❌: " + t.errors.inconnu + " (" + error.message + ")");
    }
});
});
function debounce(func, wait) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), wait);
  };
}

