<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
if (isset($_POST['send'])){
     // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
     if (isset($_POST['vercode']) && ($_POST['vercode'] != $_SESSION['vercode'])) {
          // Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
          echo "<script>alert('code de vérification incorrect');</script>";
     } else {
          // Sinon on continue
          // on recupere l'email et le numero de portable saisi par l'utilisateur
          $userEmail = strip_tags($_POST['email']);
          $userMobile = strip_tags($_POST['mobileNumber']);
          // et le nouveau mot de passe que l'on encode (fonction password_hash)
          $userPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
          // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
          $sql = "SELECT EmailId, MobileNumber from tblreaders WHERE EmailId=:email AND MobileNumber=:mobileNumber";
          $query = $dbh->prepare($sql);
          $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
          $query->bindParam(':mobileNumber', $userMobile, PDO::PARAM_STR);
          $query->execute();
          $results = $query->fetch(PDO::FETCH_OBJ);
          // Si le resultat de recherche n'est pas vide
          error_log(print_r($results, 1));
          if($results) {
               // On met a jour la table tblreaders avec le nouveau mot de passe
               $sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email AND MobileNumber=:mobileNumber";
               $query = $dbh->prepare($sql);
               $query->bindParam(':password', $userPassword, PDO::PARAM_STR);
               $query->bindParam(':email', $userEmail, PDO::PARAM_STR);
               $query->bindParam(':mobileNumber', $userMobile, PDO::PARAM_STR);
               $query->execute();
               // On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
               echo "<script>alert('Votre mot de passe a été mis à jour avec succès.');</script>";
          }  
   }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
          let valid = () => {
               let password1 = document.getElementById('password');
               let password2 = document.getElementById('password-check');

               if(password1.value != password2.value) {
                    alert("Vos mots de passe ne sont pas identiques.");
                    password1.value = "";
                    password2.value = "";
                    return false;
               } else {
                    return true;
               }
          }
     </script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="content-wrapper">
     <div class="container">
          <div class="row">
               <!--pad-botm-->
               <div class="col-md-12">
                    <h4 class="header-line">Recuperation de mot de passe</h4>
               </div>
          </div>
     <!--On insere le formulaire de recuperation-->
     <div class="row">
               <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
                    <div class="panel panel-info">
                         <div class="panel-body">
                              <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->
                              <form role="form" method="post" action="" onSubmit="valid();">
                                   <div class="form-group">
                                        <label>Email</label>
                                        <input class="form-control" type="text" name="email" required autocomplete="off" />
                                   </div>
                                   <div class="form-group">
                                        <label>Tel portable</label>
                                        <input class="form-control" type="text" name="mobileNumber" required>
                                   </div>
                                   <div class="form-group">
                                        <label>Nouveau mot de passe</label>
                                        <input id="password" class="form-control" type="password" name="password" required autocomplete="off" />
                                   </div>
                                   <div class="form-group">
                                        <label>Confirmer le mot de passe</label>
                                        <input id="password-check" class="form-control" type="password" name="password-check" required autocomplete="off" />
                                   </div>
                                   <div class="form-group">
                                   <label>Code de vérification</label>
                                        <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                                        <input class="form-control" type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                                   </div>
                                   <button type="submit" name="send" class="btn btn-info">ENVOYER </button>&nbsp;&nbsp;&nbsp;<a href="signup.php">Je n'ai pas de compte</a>
                              </form>
                         </div>
                    </div>
               </div>
          </div>
          <!---LOGIN PABNEL END-->
     </div>
</div>
          
     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>