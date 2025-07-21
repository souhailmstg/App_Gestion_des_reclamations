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
        submit: "Envoyer"
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
        submit: "إرسال"
      }
    };

    function switchLanguage(lang) {
      const t = translations[lang];
      document.documentElement.dir = t.dir;
      document.documentElement.lang = lang;
      
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