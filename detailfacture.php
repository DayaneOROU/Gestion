<?php
include("bdd.php");
$idcom = connexobjet("essaiebdd");

$id_commande = $_GET["id"] ?? 0;

if (!$id_commande) {
  header("Location: Listevente.php");
  exit;
}


$result_commande = $idcom->query("
  SELECT c.id_comm, c.date, c.montant_total,
         cl.nom, cl.prenom, cl.mail, cl.ville
  FROM commande c
  JOIN client cl ON c.id_client = cl.id_client
  WHERE c.id_comm = $id_commande
");

if ($result_commande->num_rows === 0) {
  header("Location: Listevente.php");
  exit;
}

$commande = $result_commande->fetch_array(MYSQLI_ASSOC);

// Récupérer les lignes de vente
$result_lignes = $idcom->query("
  SELECT a.design, a.categorie, lv.quantite, lv.prix_unitaire,
         (lv.quantite * lv.prix_unitaire) as soustotal
  FROM ligne_vente lv
  JOIN article a ON lv.id_article = a.id_article
  WHERE lv.id_comm = $id_commande
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Facture #<?= $commande["id_comm"] ?></title>
  <link rel="stylesheet" href="authentification.css" />
  <style>
    body {
      min-height: 100vh;
      height: auto;
      overflow: auto;
      flex-direction: column;
      gap: 24px;
      padding: 40px 20px;
      align-items: center;
    }

    .container {
      max-width: 600px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .badge {
      display: inline-block;
      background: #F8F9FA;
      color: #534AB7;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
    }

    .btn {
      padding: 8px 20px;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      background: #534AB7;
      color: white;
      transition: 0.5s
    }

    .btn:hover { background: #3C3489; scale:1.05}

    .btn-outline {
      background: white;
      color: #534AB7;
      border: 1px solid #534AB7;
    }

    .btn-outline:hover { background: #EEEDFE; }

    .card {
      background-color: #F8F9FA;
      width: 100%;
      max-width: 550px;
      background: white;
      border-radius: 14px;
      padding: 24px;
      border: 0.5px solid #000000ff;
    }

    .card h4 {
      margin: 0 0 16px;
      font-size: 13px;
      color: #1500ffff;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .info-item label {
      font-size: 12px;
      color: #999;
      display: block;
      margin-bottom: 4px;
    }

    .info-item span {
      font-size: 15px;
      font-weight: 500;
      color: #333;
    }

    .article-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 1);
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 20px;
      border: 0.5px solid rgba(11, 11, 11, 0.3);
      width: 90%;
      box-shadow: 0px 0px 7px 7px rgba(158, 157, 157, 0.7)
    }

    .article-card .design { font-weight: 200; }
    .article-card .info { color: #666; }
    .article-card .montant { color: #35cd56ff; font-weight: 500; }


    .total-section {
      text-align: right;
      font-size: 18px;
      font-weight: 500;
      color: #534AB7;
      margin-top: 12px;
    }
  </style>
</head>
<body>
<div class="container">

  <div class="top-bar">
    <h2>Facture <span class="badge">#<?= $commande["id_comm"] ?></span></h2>
    <div style="display:flex; gap:10px;">
      <a href="Listevente.php" class="btn btn-outline">← Toutes les ventes</a>
      <a href="effectuervente.php" class="btn">+ Nouvelle vente</a>
    </div>
  </div>

  <!-- Infos commande + client -->
  <div class="card">
    <h4>Informations</h4>
    <div class="info-grid">
      <div class="info-item">
        <label>Client</label>
        <span><?= $commande["nom"] . " " . $commande["prenom"] ?></span>
      </div>
      <div class="info-item">
        <label>Ville</label>
        <span><?= $commande["ville"] ?? "—" ?></span>
      </div>
      <div class="info-item">
        <label>Email</label>
        <span><?= $commande["mail"] ?? "—" ?></span>
      </div>
      <div class="info-item">
        <label>Date</label>
        <span><?= date("d/m/Y H:i", strtotime($commande["date"])) ?></span>
      </div>
    </div>
  </div>

  <!-- Articles -->
  <div class="card">
    <h4>Articles achetés</h4>

    <?php while ($ligne = $result_lignes->fetch_array(MYSQLI_ASSOC)): ?>
      <div class="article-card">
        <span class="design"><?= $ligne["design"] ?></span>
        <span class="info">Qté: <?= $ligne["quantite"] ?></span>
        <span class="info"><?= $ligne["prix_unitaire"] ?> FCFA</span>
        <span class="montant"><?= $ligne["soustotal"] ?> FCFA</span>
      </div>
    <?php endwhile; ?>

    <div class="total-section">
      TOTAL : <?= $commande["montant_total"] ?> FCFA
    </div>
  </div>

</div>
<?php $idcom->close(); ?>
</body>
</html>