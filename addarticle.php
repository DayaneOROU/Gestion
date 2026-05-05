<?php
include "bdd.php";
$idcom = connexobjet("essaiebdd");
$message = "";
$type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $design    = $_POST["design"];
  $prix      = $_POST["prix"];
  $categorie = $_POST["categorie"];

  if (empty($design) || empty($prix) || empty($categorie)) {
    $message = "Veuillez entrer toutes les informations du produit";
    $type = "erreur";
  } else {
    $check = "SELECT * FROM article WHERE design = '$design'";
    $result_check = $idcom->query($check);

    if ($result_check->num_rows > 0) {
      $message = "Le produit '$design' existe déjà !";
      $type = "erreur";
    } else {
      $requete = "INSERT INTO article (design, prix, categorie) VALUES ('$design', '$prix', '$categorie')";
      $result  = $idcom->query($requete);

      if ($result) {
        $message = "Produit '$design' ajouté avec succès !";
        $type = "succes";
      } else {
        $message = "Erreur : le produit n'a pas été ajouté";
        $type = "erreur";
      }
    }
  }
}
$idcom->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Ajouter un article</title>
  <link rel="stylesheet" href="authentification.css" />
  <style>
    body {
      /* background: linear-gradient(rgb(237, 237, 207), rgb(190, 212, 228)); */
    }

    .message {
      width: 75%;
      padding: 10px 16px;
      border-radius: 10px;
      font-size: 14px;
      margin-bottom: 12px;
      text-align: center;
    }

    .succes {
      background: #E1F5EE;
      color: #085041;
      border: 1px solid #5DCAA5;
    }

    .erreur {
      background: #FAECE7;
      color: #993C1D;
      border: 1px solid #F0997B;
    }

     a{
        
        padding: 5px;
        border: solid 1px black;
        text-decoration: none;
        color: black;
        border-radius: 15px;
        box-shadow: 3px 4px rgba(117, 147, 162, 0.5);
        
       
    }
  </style>
</head>
<body>
  <form action="addarticle.php" method="POST">
    <center><h3>Ajouter un article</h3></center>

    <?php if ($message !== ""): ?>
      <div class="message <?= $type ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <input type="text" name="design" placeholder="Désignation" /><br />
    <input type="number" name="prix" placeholder="Prix" /><br />
    <input type="text" name="categorie" placeholder="Catégorie" /><br />
    <input type="submit" value="Ajouter" id="submit" />

    <div class="ligne" style="margin-top: 50px">
      <h3>Voir les articles</h3>
      <a href="exemple15_4.php">Retour</a>
      <a href="./accueil.html">ACCUEIL</a>
    </div>
  </form>
</body>
</html>