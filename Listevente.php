<?php
include("bdd.php"); 
$idcom = connexobjet("essaiebdd"); 
$requete = "SELECT c.id_comm, c.date, c.montant_total, cl.nom, cl.prenom 
            FROM commande c
            JOIN client cl ON c.id_client = cl.id_client
            ORDER BY c.date DESC";
$result  = $idcom->query($requete);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventes</title>
  <link rel="stylesheet" href="authentification.css" />
  <style>
    body {
      min-height: 100vh;
      height: auto;
      overflow: auto;
      flex-direction: column;
      gap: 20px;
      padding: 40px 20px;
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

    tr:last-child td { border-bottom: none; }
    tr:nth-child(even) td { background: #f9f9f9; }
    tr:hover td { background: #EEEDFE; }

    .empty { color: #999; font-size: 14px; }

    .montant { color: #534AB7; font-weight: 500; }

    .voir {
      padding: 5px 12px;
      background: #534AB7;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      border: none;
      box-shadow: none;
      margin: 0;
    }

    .voir:hover { background: #3C3489; }

    a {
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
    <h3>Toutes les ventes</h3>
    <?php if ($result): ?>
      <h4>Il y a <?= $result->num_rows ?> vente(s)</h4>
    <?php endif; ?>
  </div>

  <?php if (!$result): ?>
    <p class="empty">Lecture impossible</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>N° Vente</th>
          <th>Client</th>
          <th>Date</th>
          <th>Total</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php while ($ligne = $result->fetch_array(MYSQLI_ASSOC)): ?>
          <tr>
            <td>#<?= $ligne["id_comm"] ?></td>
            <td><?= $ligne["nom"] . " " . $ligne["prenom"] ?></td>
            <td><?= date("d/m/Y H:i", strtotime($ligne["date"])) ?></td>
            <td class="montant"><?= $ligne["montant_total"] ?> FCFA</td>
            <td><a href="detailfacture.php?id=<?= $ligne["id_comm"] ?>" class="voir">Voir →</a></td>
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