const translations = {
    fr: {
      dir: "ltr",
      headerTitle: "Agence du Bassin Hydraulique de l'Oum Er Rbia",
      titlePage: "Accueil",
      formText: "Ajouter une réclamation",
      loginText: "Se connecter",
    },
    ar: {
      dir: "rtl",
      headerTitle: "وكالة الحوض المائي لأم الربيع",
      titlePage: "الصفحة الرئيسية",
      formText: "إضافة شكاية",
      loginText: "تسجيل الدخول",
    }
  };

    function switchLanguage(lang) {
    localStorage.setItem("lang", lang); 
    const t = translations[lang];
    document.documentElement.dir = t.dir;

    document.getElementById("header-title").textContent = t.headerTitle;
    document.getElementById("title-page").textContent = t.titlePage;
    document.getElementById("form-span").textContent = t.formText;
    document.querySelector("#ConnexionLink span").textContent = t.loginText;

    // Masquer le bouton actif
    document.getElementById("btn-fr").style.display = (lang === "fr") ? "none" : "inline-block";
    document.getElementById("btn-ar").style.display = (lang === "ar") ? "none" : "inline-block";
  }

// Appliquer la langue par défaut (français)
document.addEventListener("DOMContentLoaded", () => {
        switchLanguage("fr");
});
      
