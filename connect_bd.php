<?php
// Tentative de connexion à la base de données
try {
    $utilisateur = "Sora";
    $motDePasse = "Ggb785niko.";
    $baseDeDonnees  = "projet_ajout_book";

    $db = new PDO(
        "mysql:host=localhost;dbname=".$baseDeDonnees.";charset=utf8",
        $utilisateur,
        $motDePasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
        ]
    );

} catch(Exception $e){
   echo "Connexion à la base de données refusée.";
   exit();
}