<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
	// On le redirige vers la page de login
	header('location:../index.php');
} else {
	// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
	// Si le formulaire a bien ete soumis
	if (isset($_POST['change'])) {
		// On recupere le mot de passe courant
		$currentPwd = strip_tags($_POST['pwd']);
		// On recupere le nouveau mot de passe
		$newPwd = password_hash($_POST['new-pwd'], PASSWORD_DEFAULT);
		// On recupere le nom de l'utilisateur stocké dans $_SESSION
		$user = $_SESSION['alogin'];

		// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
		$sql = "SELECT * FROM admin WHERE UserName=:user";
		// dont on connait le nom et le mot de passe actuel
		$query = $dbh->prepare($sql);
      	$query->bindParam(':user', $user, PDO::PARAM_STR);
		// On execute la requete
		$query->execute();
		$result = $query->fetch(PDO::FETCH_OBJ);

		// Si on trouve un resultat
		if(!empty($result) && password_verify($currentPwd, $result->Password)) {
			// On prepare la requete de mise a jour du nouveau mot de passe de cet id
			$sql = "UPDATE admin SET Password=:pwd WHERE UserName=:user";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user', $user, PDO::PARAM_STR);
			$query->bindParam(':pwd', $newPwd, PDO::PARAM_STR);
			// On execute la requete
			$query->execute();
			// On stocke un message de succès de l'operation
			$_SESSION['pwdmsg'] = "Le mot de passe a bien été mis à jour";
			echo "<script> alert('Le mot de passe a bien été mis à jour') </script>";
			// On purge le message d'erreur
		} else {
			// Sinon on a trouve personne	
			// On stocke un message d'erreur
			$_SESSION['pwdmsg'] = "Le mot de passe n'a pas été mis à jour";
			echo "<script> alert('Le mot de passe n'a pas été mis à jour') </script>";
		}
	} else {
		// Sinon le formulaire n'a pas encore ete soumis
		// On initialise le message de succes et le message d'erreur (chaines vides)
		$_SESSION['pwdmsg'] = "";
	}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
	<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
</head>
<script type="text/javascript">
	// On cree une fonction JS valid() qui renvoie
	// true si les mots de passe sont identiques
	// false sinon
	function valid() {
		let newPwd = document.getElementById('new-pwd').value;
		let ctrlPwd = document.getElementById('ctrl-pwd').value;
		let msg = document.getElementById('pwd-msg');
		const popup = document.getElementById('popup');

		if(newPwd != ctrlPwd) {
			alert("Vos mots de passe ne sont pas identiques");
			return false;
		} 
		return true;
	}

</script>

<body>
	<!------MENU SECTION START-->
	<?php include('includes/header.php'); ?>
	<!-- MENU SECTION END-->
	<div class="container">
  <div class="row">
    <div class="col">
    <h3 class="header-line p-5">MODIFIER LE MOT DE PASSE</h3>
    </div>
  </div>
  <!-- On affiche le message de succes ou d'erreur  -->
  
  <!-- On affiche le formulaire de changement de mot de passe-->
  <div class="row">
    <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
	<?php 
		if(isset($_SESSION['pwdmsg'])) {
			echo $_SESSION['pwdmsg'];
		} 
	  ?>
      <div class="panel panel-info">
        <div class="panel-body border border-info rounded">
		<div>
    </div>
			<!-- On affiche le titre du formulaire "Changer le mot de passe"  -->
          <h4 class="bg-info text-dark">Changer le mot de passe</h4>
          <form role="form" method="post" action="" onSubmit="valid()">
            <div class="form-group">
              <label for="name">Mot de passe actuel</label>
              <input id="pwd" class="form-control" type="password" name="pwd" required autocomplete="off"/>
            </div>
			<div class="form-group">
              <label for="new-pwd">Nouveau mot de passe</label>
              <input id="new-pwd" class="form-control" type="password" name="new-pwd" required autocomplete="off"/>
            </div>
            <div class="form-group">
              <label for="ctrl-pwd">Confirmer le mot de passe</label>
              <input id="ctrl-pwd" class="form-control" type="password" name="ctrl-pwd" required autocomplete="off"/>
            </div>
			<!-- La fonction JS valid() est appelee lors de la soumission du formulaire onSubmit="return valid();" -->
            <button type="submit" id="create" name="change" class="btn btn-info"> Changer </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
	<!-- CONTENT-WRAPPER SECTION END-->
	<?php include('includes/footer.php'); ?>
	<!-- FOOTER SECTION END-->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>