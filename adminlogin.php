<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['alogin'] = '';
}

// A faire :
// Apres la soumission du formulaire de login (plus bas dans ce fichier)
if (isset($_POST['login'])) {
    // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas
    if ($_POST["vercode"] != $_SESSION["vercode"] or $_SESSION["vercode"] == '' or $_POST['vercode'] == '') {
		// Le code est incorrect
		echo "<script>alert('Code de verification incorrect');</script>";
	} else {
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        $userName = strip_tags($_POST['username']);
        // On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction md5)
        $userPassword = $_POST['password'];
        // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
        // depuis la table admin
        $sql = "SELECT UserName,Password FROM admin WHERE UserName=:name";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $userName, PDO::PARAM_STR);
        // $query->bindParam(':password', $userPassword, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        // Si le resultat de recherche n'est pas vide 
        if($result && password_verify($userPassword, $result->Password)) {
            // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
            $_SESSION['alogin'] = $_POST['username'];
            // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
            header('location:admin/dashboard.php');
        } else {
            // sinon le login est refuse. On le signal par une popup
            echo "<script>alert('Mot de passe invalide');</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>

    <div class="content-wrapper">
        <!--On affiche le titre de la page-->
        <div class="content-wrapper">
		<div class="container">
			<div class="row">
				<!--pad-botm-->
				<div class="col-md-12">
					<h4 class="header-line">ADMINISTRATEUR</h4>
				</div>
			</div>

			<!--On ins�re le formulaire de login-->
			<!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
			<div class="row">
				<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
					<div class="panel panel-info">
						<div class="panel-body">
							<form role="form" method="post" action="">
								<div class="form-group">
									<label>Votre nom</label>
									<input class="form-control" type="text" name="username" required autocomplete="off" />
								</div>
								<div class="form-group">
									<label>Mot de passe</label>
									<input class="form-control" type="password" name="password" required autocomplete="off" />
									<p class="help-block">
										<a href="user-forgot-password.php">Mot de passe oublié ?</a>
									</p>
								</div>
								<div class="form-group">
									<label>Code de Verification : </label>
									<input type="text" class="form-control" name="vercode" maxlength="5" autocomplete="off" required style="height:25px;" />&nbsp;<img src="captcha.php">
								</div>

								<button type="submit" name="login" class="btn btn-info">LOGIN </button>&nbsp;&nbsp;&nbsp;<a href="signup.php">Je n'ai pas de compte</a>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!---LOGIN PABNEL END-->
		</div>
	</div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    
</body>

</html>