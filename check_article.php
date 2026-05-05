<?php
include("bdd.php");
$idcom = connexobjet("essaiebdd");

header("Content-Type: application/json");

$design = $_GET["design"] ?? "";

if (empty($design)) {
  echo json_encode(["found" => false]);
  exit;
}

$design = $idcom->real_escape_string($design);
$result = $idcom->query("SELECT * FROM article WHERE design = '$design'");

if ($result->num_rows > 0) {
  $article = $result->fetch_array(MYSQLI_ASSOC);
  echo json_encode([
    "found"     => true,
    "id"        => $article["id_article"],
    "design"    => $article["design"],
    "prix"      => $article["prix"],
    "categorie" => $article["categorie"]
  ]);
} else {
  echo json_encode(["found" => false, "design" => $design]);
}

$idcom->close();
?>