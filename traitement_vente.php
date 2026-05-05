<?php
session_start();
include("bdd.php");
$idcom = connexobjet("essaiebdd");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: effectuervente.php");
  exit;
}

$nom      = $idcom->real_escape_string($_POST["nom"]);
$prenom   = $idcom->real_escape_string($_POST["prenom"]);
$tel      = $idcom->real_escape_string($_POST["tel"]);
$articles = $_POST["articles"] ?? [];
$total    = $_POST["total"] ?? 0;

if (empty($nom) || empty($prenom) || empty($tel) || empty($articles)) {
  $_SESSION["message"] = "Informations manquantes";
  $_SESSION["type"]    = "erreur";
  header("Location: effectuervente.php");
  exit;
}

// 1. Vérifier si le client existe
$check = $idcom->query("SELECT id_client FROM client WHERE nom='$nom' AND prenom='$prenom'");

if ($check->num_rows > 0) {
  $client    = $check->fetch_array(MYSQLI_ASSOC);
  $id_client = $client["id_client"];
} else {
// Tu envoies le tel depuis effectuervente.php mais tu ne l'insères pas
$idcom->query("INSERT INTO client (nom, prenom) VALUES ('$nom', '$prenom')");

// ✅ Ajoute le tel si ta table client a la colonne tel
$idcom->query("INSERT INTO client (nom, prenom, tel) VALUES ('$nom', '$prenom', '$tel')");
  $id_client = $idcom->insert_id;
}

// 2. Créer la commande
$idcom->query("INSERT INTO commande (id_client, montant_total) VALUES ('$id_client', '$total')");
$id_commande = $idcom->insert_id;

// 3. Insérer les lignes de vente
foreach ($articles as $article) {
  $id_article = (int)$article["id"];
  $qte        = (int)$article["qte"];
  $prix       = (float)$article["prix"];

  $idcom->query("INSERT INTO ligne_vente (id_comm, id_article, quantite, prix_unitaire) 
                 VALUES ('$id_commande', '$id_article', '$qte', '$prix')");}

$idcom->close();
header("Location: detailfacture.php?id=$id_comm");
exit;
?>