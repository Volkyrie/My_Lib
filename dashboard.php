<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

if (strlen($_SESSION['rdid']) == 0) {
     // Si l'utilisateur est déconnecté
     // L'utilisateur est renvoyé vers la page de login : index.php

     header('location:index.php');
} else {
     // On récupère l'identifiant du lecteur dans le tableau $_SESSION
     $readerId = strip_tags($_SESSION['rdid']);

     // On veut savoir combien de livres ce lecteur a emprunte
     // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $count = "SELECT COUNT(*) from tblissuedbookdetails WHERE ReaderID=:readerId";
     $query = $dbh ->prepare($count);
     $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $query->execute();

     // On stocke le résultat dans une variable
     $nbBooks = $query->fetchColumn();

     // On veut savoir combien de livres ce lecteur n'a pas rendu
     // On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
     $returnStatus = 0;

     $count = "SELECT COUNT(*) from tblissuedbookdetails WHERE ReaderID=:readerId AND ReturnStatus=:returnStatus";
     $query = $dbh ->prepare($count);
     $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
     $query->bindParam(':returnStatus', $returnStatus, PDO::PARAM_STR);
     $query->execute();

     // On stocke le résultat dans une variable
     $nbNotReturned = $query->fetchColumn();
}

?>

     <!DOCTYPE html>
     <html lang="FR">

     <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
          <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
          <!-- BOOTSTRAP CORE STYLE  -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
          <!-- FONT AWESOME STYLE  -->
          <link href="assets/css/font-awesome.css" rel="stylesheet" />
          <!-- CUSTOM STYLE  -->
          <link href="assets/css/style.css" rel="stylesheet" />
     </head>

     <body>
          <!--On inclue ici le menu de navigation includes/header.php-->
          <?php include('includes/header.php'); ?>
          <!-- On affiche le titre de la page : Tableau de bord utilisateur-->
          <div class="content-wrapper">
               <div class="container">
                    <div class="row">
                         <!--pad-botm-->
                         <div class="col-md-12">
                              <h4 class="header-line">LOGIN LECTEUR</h4>
                         </div>
                    </div>
                    <!-- On affiche la carte des livres emprunt�s par le lecteur-->
                    <div class="row">
                         <!--pad-botm-->
                         <div class="col-md-2">
                              <div class="row justify-content-center">
                                   <i class="fa fa-bars fa-5x text-info"></i>
                              </div>
                              <div class="row justify-content-center text-center">
                                   <?php echo "<p class='text-info'> $nbBooks </p>"?>
                              </div>
                              <div class="row justify-content-center text-center">
                                   <h4 class="text-info">Livres empruntés</h4>
                              </div>
                         </div>
                         <div class="col-md-2">
                              <div class="row justify-content-center">
                                   <i class="fa fa-recycle fa-5x text-warning"></i>
                              </div>
                              <div class="row justify-content-center text-center">
                                   <?php echo "<p class='text-warning'>$nbNotReturned</p>" ?>
                              </div>
                              <div class="row justify-content-center text-center">
                                   <h4 class="text-warning">Livres non encore rendus</h4>
                              </div>
                         </div>
                    </div>
                    <!-- On affiche la carte des livres non rendus le lecteur-->
               </div>
          </div>
          <?php include('includes/footer.php'); ?>
          <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     </body>

     </html>
<?php ?>