<?php 
require_once("includes/config.php");
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/
$inputId = strip_tags($_GET['id']);
// On prepare la requete de recherche du lecteur correspondnat
$sql = "SELECT * from tblreaders WHERE ReaderId=:readerId";
// On execute la requete
$query = $dbh->prepare($sql);
$query->bindParam(':readerId', $inputId, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);

// Si un resultat est trouve
if(isset($result->ReaderId) && ($result->Status == 1)) {
	// On affiche le nom du lecteur
	echo json_encode(array("rep" => "ok", "msg" => $result->FullName));
	// On active le bouton de soumission du formulaire
}
// Sinon
else {
	// Si le lecteur n existe pas
	if(!isset($result->Status)) {
		// On affiche que "Le lecteur est non valide"
		// On desactive le bouton de soumission du formulaire
		echo json_encode(array("rep" => "nok", "msg" => "Aucun lecteur trouvé avec cet identifiant"));
	}
	// Si le lecteur est bloque
	else if ($result->Status == 0){
		// On affiche lecteur bloque
		// On desactive le bouton de soumission du formulaire
		echo json_encode(array("rep" => "nok", "msg" => "Cet utilisateur est bloqué"));
	} else {
		echo json_encode(array("rep" => "nok", "msg" => "Une erreur s'est produite"));
	}
}
?>
