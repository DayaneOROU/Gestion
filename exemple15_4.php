<?php
include("bdd.php"); 
$idcom = connexobjet("essaiebdd"); 
$requete = "SELECT * FROM article ORDER BY categorie"; 
$result = $idcom->query($requete);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nos Articles</title>
  <link rel="stylesheet" href="authentification.css" />
  <style>
    body {
      min-height: 100vh;
      height: auto;
      overflow: auto;
      flex-direction: column;
      gap: 20px;
      padding: 40px 20px;
      /* background: linear-gradient(rgb(237, 237, 207), rgb(190, 212, 228)); */
    }

    h3 {
      margin: 0;
      font-size: 22px;
      color: #333;
    }

    h4 {
      margin: 0;
      font-size: 14px;
      color: #666;
      font-weight: 400;
    }

    .header {
      display: flex;
      justify-content: right;
      align-items: right;
      width: 100%;
      max-width: 800px;
    }

    .btn-ajouter {
      padding: 10px 20px;
      background: #534AB7;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
    }

    .btn-ajouter:hover {
      background: #3C3489;
    }

    table {
      width: 100%;
      max-width: 800px;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      border: none;
    }

    th {
      background: #534AB7;
      color: white;
      padding: 12px 16px;
      text-align: left;
      font-weight: 500;
    }

    td {
      padding: 12px 16px;
      border-bottom: 0.5px solid #e0e0e0;
      color: #333;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:nth-child(even) td {
      background: #f9f9f9;
    }

    tr:hover td {
      background: #EEEDFE;
    }

    .empty {
      color: #999;
      font-size: 14px;
    }

     a{
        margin: 10px;
        padding: 7px;
        border: solid 1px black;
        text-decoration: none;
        color: black;
        border-radius: 17px;
        box-shadow: 3px 4px rgba(117, 147, 162, 0.5);
        
       
    }
  </style>
</head>
<body>

  
    <div>
      <h3>Tous nos articles par catégorie</h3>
      <?php if ($result): ?>
        <h4>Il y a <?= $result->num_rows ?> articles en magasin</h4>
      <?php endif; ?>
    </div>
    <div class="header">
    <a href="addarticle.php" class="btn-ajouter">+ Ajouter un article</a>
    </div>

  <?php if (!$result): ?>
    <p class="empty">Lecture impossible</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Code article</th>
          <th>Description</th>
          <th>Prix</th>
          <th>Catégorie</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($ligne = $result->fetch_array(MYSQLI_NUM)): ?>
          <tr>
            <?php foreach ($ligne as $valeur): ?>
              <td><?= $valeur ?></td>
            <?php endforeach; ?>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <?php
    $result->free();
    $idcom->close();
  ?>
     <a href="./accueil.html">ACCUEIL</a>
</body>
</html>