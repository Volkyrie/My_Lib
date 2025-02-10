<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

//error_log(print_r($_POST, 1));
// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
//$_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if (isset($_POST['signup'])){
  //error_log(print_r($_SESSION, 1));
  if (isset($_POST['vercode']) && ($_POST['vercode'] != $_SESSION['vercode'])) {
    echo "<script>alert('code de vérification incorrect');</script>";
  } else {
    //$ressource = fopen('readerid.txt', 'rb');
    //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
    $ressourceLue = file('readerid.txt'); // est un tableau où chaque ligne est un index du tableau
    // On incrémente de 1 la valeur lue
    $ressourceIncr = str_increment($ressourceLue[0]);
    //error_log(print_r($ressourceIncr, 1));
    // On ouvre le fichier readerid.txt en écriture
    $ressource = 'readerid.txt';
    // On écrit dans ce fichier la nouvelle valeur
    file_put_contents($ressource, $ressourceIncr);
    // On referme le fichier
    // On récupère les infos saisies par le lecteur

    // On récupère le nom saisi par le lecteur
    $userName = strip_tags($_POST['userName']);
    // On récupère l'email
    $userEmail = strip_tags($_POST['email']);
    // On récupère le numéro de portable
    $userMobile = strip_tags($_POST['mobileNumber']);
    // On recupere le mot de passe saisi par l'utilisateur et on le crypte (fonction passwword_hash)
    $userPassword = password_hash($_POST['password1'], PASSWORD_DEFAULT);
    // On fixe le statut du lecteur à 1 par défaut (actif)
    $status = 1;

    // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders

    // TODO
    $sql = "INSERT INTO tblreaders (ReaderId, FullName, EmailId, MobileNumber, Password, Status) VALUES (:readerId, :fullName, :email, :mobile, :password, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId', $ressourceIncr, PDO::PARAM_STR);
    $query->bindParam(':fullName', $userName, PDO::PARAM_STR);
    $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
    $query->bindParam(':mobile', $userMobile, PDO::PARAM_STR);
    $query->bindParam(':password', $userPassword, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->execute();

    // On récupère le dernier id inséré en bd (fonction lastInsertId)
    $lastId = $dbh->lastInsertId();
    // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $ressourceLue[0]), sinon on affiche qu'il y a eu un problème
    if (isset($lastId)) {
      echo "<script>alert('L\'opération s\'est bien déroulée " . $ressourceIncr . "')</script>";
    } else {
      echo "<script>alert('L'opération a échouée, il y eu un problème')</script>";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="FR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
  <title>Gestion de bibliotheque en ligne | Signup</title>
  <!-- BOOTSTRAP CORE STYLE  -->
  <!--link href="assets/css/bootstrap.css" rel="stylesheet" /-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <!-- FONT AWESOME STYLE  -->
  <link href="assets/css/font-awesome.css" rel="stylesheet" />
  <!-- CUSTOM STYLE  -->
  <link href="assets/css/style.css" rel="stylesheet" />

  <script type="text/javascript">
    // On cree une fonction valid() sans paramètre qui renvoie 
    // TRUE si les mots de passe saisis dans le formulaire sont identiques
    // FALSE sinon

    function valid() {
      // TODO
      let password1 = document.getElementById("createdPassword").value;
      let password2 = document.getElementById("confirmedPassword").value;
      if (password1 !== password2) {
        alert("Les mots de passe ne correspondent pas");
        return false;
      }
      return true;
    }

    // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
    // Cette fonction effectue un appel AJAX vers check_availability.php


    function checkAvailability(mail) {
      //TODO
      // Utiliser un appel fetch vers check_avaiability.php
      const msg = document.getElementById('error-mail');
      const email = document.getElementById('email');
      fetch(`check_availability.php?mail=${mail}`)
      .then(response => response.json())
      .then(data => {
        if(data.rep === "nok") {
          msg.style.opacity = "1";
          email.value = "";
        }
        else {
          msg.style.opacity = "0";
        }
      })
      .catch(error => console.error('Erreur lors de la vérification de l\'email:', error));
   }
  </script>
</head>

<body>
  <!-- On inclue le fichier header.php qui contient le menu de navigation-->
  <?php include('includes/header.php'); ?>

  <!-- Titre de la page (LOGIN UTILISATEUR) -->
  <div class="content-wrapper">
    <div class="container">
      <div class="row">
        <!--pad-botm-->
        <div class="col-md-12">
          <h4 class="header-line">CREER UN COMPTE</h4>
        </div>
      </div>


      <div class="row">
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
          <div class="panel panel-info">
            <div class="panel-body">
              <!--On créé le formulaire de creation de compte-->
              <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
              <form action="signup.php" method="POST" onSubmit="return valid()">
                <div class="form-group">
                  <label>Entrez votre nom et prénom</label>
                  <input class="form-control" type="text" name="userName" required>
                </div>
                <div class="form-group">
                  <label>Portable</label>
                  <input class="form-control" type="text" name="mobileNumber" required>
                </div>
                <div class="form-group">
                  <label>Entrez votre email</label>
                  <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->
                  <input class="form-control" type="text" name="email" id="email" required onBlur="checkAvailability(this.value)">
                  <p id="error-mail" style="opacity: 0">Cette adresse mail est déjà utilisée.</p>
                </div>

                <div class="form-group">
                  <label>Saisissez un mot de passe</label>
                  <input class="form-control" id="createdPassword" type="password" name="password1" required><br>
                  <label>Confirmez votre mot de passe</label>
                  <input class="form-control" id="confirmedPassword" type="password" name="password2" required>
                </div>

                <div class="form-group">
                  <label>Code de vérification</label>
                  <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                  <input class="form-control" type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                </div>

                <button type="submit" name="signup" class="btn btn-info" id="submit">CREATE</button>
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
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>