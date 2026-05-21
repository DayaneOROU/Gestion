<?php
session_start();
$message = $_SESSION["message"] ?? "";
$type    = $_SESSION["type"] ?? "";
unset($_SESSION["message"], $_SESSION["type"]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Effectuer une vente</title>
  <link rel="stylesheet" href="authentification.css"/>
  <style>
    body {
      min-height: 100vh;
      height: 100vh;
      overflow: auto;
      flex-direction: column;
      gap: 24px;
      padding: 40px 20px;
      align-items: center;
    }

    .container {
      width: 100%;
      max-width: 800px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .ligne {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    input {
      margin: 10px;
      padding: 12px;
      border: solid 1px black;
      border-radius: 17px;
      transition: border 0.2s;
    }

    input:focus { border-color: #534AB7; }

    .btn {
      padding: 8px 20px;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.2s;
      background: #6366F1;
      color: white;
    }

    .btn:hover { background: #3C3489; }

    .notfound-box {
      display: none;
      justify-content: space-between;
      gap: 20px;
      align-items: center;
      background: #FAECE7;
      border: 0.5px solid #b35a3cff;
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 13px;
      color: #993C1D;
      margin-bottom: 15px;
    }

    .notfound-box a {
      background: #993C1D;
      color: white;
      padding: 6px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      transition: 0.2s;
    }

    .notfound-box a:hover {
      background: #773017;
      scale: 1.05;
    }

    .article-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 1);
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 8px;
      border: 0.5px solid rgba(11, 11, 11, 0.3);
      width: 100%;
      /* box-shadow: 0px 0px 7px 7px rgba(158, 157, 157, 0.7) */
    }

    .article-card .design { font-weight: 200; }
    .article-card .info { color: #666; }
    .article-card .montant { color: #35cd56ff; font-weight: 500; }

    .btn-suppr {
      background: #b93333ff;
      color: #ffffffff;
      border: none;
      border-radius: 8px;
      padding: 4px 10px;
      cursor: pointer;
    }

    .total-section {
      text-align: right;
      font-size: 16px;
      font-weight: 500;
      color: #534AB7;
      margin-top: 8px;
    }

    .message { padding: 10px 16px; border-radius: 10px; font-size: 14px; text-align: center; }
    .succes { background: #E1F5EE; color: #085041; border: 1px solid #5DCAA5; }
    .erreur { background: #FAECE7; color: #993C1D; border: 1px solid #F0997B; }
  </style>
</head>
<body>
<div class="container">

  <?php if ($message !== ""): ?>
    <div class="message <?= $type ?>"><?= $message ?></div>
  <?php endif; ?>

  <form id="venteForm" action="traitement_vente.php" method="POST">

    <center><h2>Effectuer une vente</h2></center>

    <h4>Informations client</h4>
    <input type="text" name="nom" id="nom" placeholder="Nom" required />
    <input type="text" name="prenom" id="prenom" placeholder="Prénom" required />
    <input type="tel" name="tel" id="tel" placeholder="Téléphone" required />

    <h4>Ajouter un article</h4>
    <input type="text" id="search_article" placeholder="Nom de l'article" />
    <div class="ligne">
      <input type="number" id="quantite" placeholder="Qté" min="1" value="1" />
      <button type="button" class="btn" onclick="verifierArticle()">Vérifier & Ajouter</button>
    </div>

    <div class="notfound-box" id="notfound">
      <span id="notfound_msg"></span>
      <a href="addarticle.php" target="_blank">+ Ajouter cet article</a>
    </div>

    <h4>Articles ajoutés</h4> <br>
    <div id="articles_container">
      <p style="color:#999; font-size:13px; text-align:center;">Aucun article ajouté</p>
    </div>

    <div class="total-section">
      TOTAL : <span id="total_display">0 FCFA</span>
    </div>

    <div id="hidden_inputs"></div>

    <div class="ligne" style="margin-top: 20px;">
      <a href="accueil.html" class="btn" style="background:white; color:#534AB7; border: 1px solid #534AB7;">Annuler</a>
      <button type="submit" class="btn" onclick="return validerVente()">Valider la vente</button>
    </div>

  </form>
</div>

<script>
  let articles = [];

  function verifierArticle() {
    const design   = document.getElementById("search_article").value.trim();
    const qte      = parseInt(document.getElementById("quantite").value);
    const notfound = document.getElementById("notfound");

    if (design === "") { alert("Entrez un nom d'article"); return; }
    if (!qte || qte < 1) { alert("Entrez une quantité valide"); return; }

    fetch("check_article.php?design=" + encodeURIComponent(design))
      .then(r => r.json())
      .then(data => {
        notfound.style.display = "none";

        if (data.found) {
          const existe = articles.find(a => a.id === data.id);
          if (existe) { alert("Cet article est déjà dans la liste"); return; }

          const sousTot = data.prix * qte;
          articles.push({ id: data.id, design: data.design, prix: data.prix, qte: qte, soustotal: sousTot });

          renderTableau();
          document.getElementById("search_article").value = "";
          document.getElementById("quantite").value = 1;

        } else {
          document.getElementById("notfound_msg").textContent = '"' + design + '" n\'existe pas dans la base.';
          notfound.style.display = "flex";
        }
      })
      .catch(() => alert("Erreur de connexion au serveur"));
  }

  function supprimerArticle(id) {
    articles = articles.filter(a => a.id !== id);
    renderTableau();
  }

  function renderTableau() {
    const container = document.getElementById("articles_container");
    const hidden    = document.getElementById("hidden_inputs");

    container.innerHTML = "";
    hidden.innerHTML    = "";

    if (articles.length === 0) {
      container.innerHTML = '<p style="color:#999; font-size:13px; text-align:center;">Aucun article ajouté</p>';
      document.getElementById("total_display").textContent = "0 FCFA";
      return;
    }

    let hiddenHTML = "";

    articles.forEach((a, i) => {
      const card = document.createElement("div");
      card.className = "article-card";

      const design = document.createElement("span");
      design.className = "design";
      design.textContent = a.design + "  |";

      const qte = document.createElement("span");
      qte.className = "info";
      qte.textContent = "Qté: " + a.qte + "  |";

      const prix = document.createElement("span");
      prix.className = "info";
      prix.textContent = "Prix U: " + a.prix + " XOF  |";

      const montant = document.createElement("span");
      montant.className = "montant";
      montant.textContent = "Prix T: " +a.soustotal + " XOF";

      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "btn-suppr";
      btn.textContent = "✕";
      btn.onclick = () => supprimerArticle(a.id);

      card.appendChild(design);
      card.appendChild(qte);
      card.appendChild(prix);
      card.appendChild(montant);
      card.appendChild(btn);
      container.appendChild(card);

      hiddenHTML += `
        <input type="hidden" name="articles[${i}][id]" value="${a.id}">
        <input type="hidden" name="articles[${i}][qte]" value="${a.qte}">
        <input type="hidden" name="articles[${i}][prix]" value="${a.prix}">
        <input type="hidden" name="articles[${i}][soustotal]" value="${a.soustotal}">`;
    });

    const t = articles.reduce((s, a) => s + a.soustotal, 0);
    document.getElementById("total_display").textContent = t + " FCFA";

    hiddenHTML += `<input type="hidden" name="total" value="${t}">`;
    hidden.innerHTML = hiddenHTML;
  }

  function validerVente() {
    const nom    = document.getElementById("nom").value.trim();
    const prenom = document.getElementById("prenom").value.trim();
    const tel    = document.getElementById("tel").value.trim();

    if (!nom || !prenom || !tel) { alert("Veuillez remplir les informations du client"); return false; }
    if (articles.length === 0) { alert("Ajoutez au moins un article"); return false; }
    return true;
  }
</script>
</body>
</html>