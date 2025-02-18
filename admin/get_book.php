<?php 
require_once("includes/config.php");
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
$inputIsbn = strip_tags($_GET['isbn']);
// On prepare la requete de recherch
$sql = "SELECT * from tblbooks WHERE ISBNNumber=:isbn";
// On execute la requete
$query = $dbh->prepare($sql);
$query->bindParam(':isbn', $inputIsbn, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
// Si un resultat est trouve
if(isset($result->BookName)) {
	// On affiche le nom du livre
	// On active le bouton de soumission du formulaire
	echo json_encode(array("rep" => "ok", "msg" => $result->BookName));
} // Sinon
else {
	// On affiche que "ISBN est non valide"
	// On desactive le bouton de soumission du formulaire 
	echo json_encode(array("rep" => "nok", "msg" => "ISBN non valide"));
}
?>
