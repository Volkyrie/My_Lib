<?php

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");
// On recupere dans $_GET l email soumis par l'utilisateur
$inputEmail = strip_tags($_GET['mail']);
// On verifie que l'email est un email valide (fonction php filter_var)
if (filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
	// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
	// On execute la requete et on stocke le resultat de recherche
	
	//TODO
	$sql = "SELECT COUNT(*) from tblreaders WHERE EmailId=:email";

	$query = $dbh->prepare($sql);
	$query->bindParam(':email', $inputEmail, PDO::PARAM_STR);
	$query->execute();
	$results = $query->fetchColumn();
	// Si le résultat de recherche est vide
	if($results > 0) {
	// On echo un objet JSON '{"rep":"ok"}'
		echo json_encode(array("rep" => "nok"));
	} 
	// Sinon
	else {
		// On echo un objet JSON '{"rep":"nok"}'
		echo json_encode(array("rep" => "ok"));
	}
}
?>