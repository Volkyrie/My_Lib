<?php
// On recupere la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
if ($_SESSION['rdid'] == 0) {
    // On le redirige vers la page de login
    echo "<script>alert('Vous avez été déconnecté');</script>";
    header('location:index.php');
}
// sinon, on peut continuer,
// si le formulaire a ete envoye : $_POST['change'] existe
if (isset($_POST['change'])) {
	// On recupere le mot de passe et on le crypte (fonction php password_hash)
	$password = strip_tags($_POST['password']);
	$password2 = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
	// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
	$email = $_SESSION['login'];
	// On cherche en base l'utilisateur avec ce mot de passe et cet email
	$sql = "SELECT * FROM tblreaders WHERE EmailId=:email";
	$query = $dbh->prepare($sql);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->execute();
	// On stocke le résultat de recherche dans une variable $result
	$result = $query->fetch(PDO::FETCH_OBJ);
	// Si le resultat de recherche n'est pas vide
	if (!empty($result) && password_verify($password, $result->Password)) {
		// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
		$sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email";
		$query = $dbh->prepare($sql);
		$query->bindParam(':password', $password2, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->execute();
		echo "<script>alert('Votre mot de passe a été mis à jour avec succès.');</script>";
		// On stocke le message d'operation reussie
		// sinon (resultat de recherche vide)
		// On stocke le message "mot de passe invalide"
	} else {
		echo "<script> alert('Votre mot de passe est incorrect'); </script>";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />

	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>
<script type="text/javascript">
	/* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
	Cette fonction retourne un booleen*/
	let valid = () => {
		let newPassword = document.getElementById('newPassword');
		let confirmedPassword = document.getElementById('confirmedPassword');
		let message = document.getElementById('message');

		if(newPassword.value != confirmedPassword.value) {
			alert('Vos nouveaux mots de passe ne corrrespondent pas');
			return false
		}
		return true;
	}
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="content-wrapper">
        <div class="container">
            <div class="row">
                <!--pad-botm-->
                <div class="col-md-12">
                    <h4 class="header-line">CHANGER MON MOT DE PASSE</h4>
                </div>
            </div>
			<!--On affiche le formulaire-->
			<!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 offset-md-3">
					<div class="panel panel-info">
						<div class="panel-body">
							<form role="form" method="post" action="" onSubmit="valid()">
								<div class="form-group">
									<label>Mot de passe actuel</label>
									<input class="form-control" type="password" name="password" id="password"required autocomplete="off" />
								</div>
								<div class="form-group">
									<label>Nouveau mot de passe</label>
									<input class="form-control" type="password" name="newPassword" id="newPassword" required autocomplete="off" />
								</div>
								<div class="form-group">
									<label>Confirmer le mot de passe</label>
									<input class="form-control" type="password" name="confirmedPassword" id="confirmedPassword" required autocomplete="off" />
								</div>

								<button type="submit" name="change" class="btn btn-info">CHANGER </button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>