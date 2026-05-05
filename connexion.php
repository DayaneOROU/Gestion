 

  <?php
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {header("Location: connexion.html");
  exit;}
    $log = $_POST["log"];
    $pass = $_POST["pass"];
    $base = "essaiebdd" ;

    include("bdd.php");
    $conn = connexobjet($base);

    if(empty($log) || empty($pass)){

          echo "Veuillez remplir tt les champs";

          }
          else{

          $requete = "SELECT * FROM users WHERE login = '$log' AND password = '$pass'";

          $resultat = $conn -> query($requete);


          if ($resultat->num_rows > 0){  
                  echo "Connexion reussi";
                  header("Location: accueil.html");
                  exit;

          }
          else{
              echo "Information incorect";
          }

          }

    


    ?>








