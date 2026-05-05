

        <?php

        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $tel = $_POST["tel"];
        $log = $_POST["log"];
        $pass = $_POST["pass"];


        $base = "essaiebdd" ;

        include("bdd.php");
        $conn = connexobjet($base);


        if(empty($nom) || empty($prenom) || empty($tel) || empty($log) || empty($pass)){

        echo "Veuillez remplir tt les champs";

        }
        else{

        $requete =  "INSERT INTO users (nom, prenom, contact, login, password)
            VALUES ('$nom', '$prenom', '$tel', '$log', '$pass')";


        $resultat = $conn -> query($requete);


        if ($resultat){
                header("Location: connexion.html");
                exit;

        }
        else{
            echo "le produit n'a pas été ajouter";
        }

        }




    ?>