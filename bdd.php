<?php
function connexobjet($base)
{

    define("HOST","localhost");
    define("USER","root");
    define("PASS","");
    define("PORT",8889);

    $idcom = new mysqli(HOST,USER,PASS,$base); 
    if (!$idcom) 
    {
        echo "<script type=text/javascript>";
        echo "alert('Connexion impossible à la base')</script>";
        exit(); 
    }
    return $idcom; 
}
?>