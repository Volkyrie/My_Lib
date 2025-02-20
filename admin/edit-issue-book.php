<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../index.php');
  } else {
      // Sinon
      $id = strip_tags($_GET['id']);

      $sql = "SELECT ReturnStatus from tblissuedbookdetails WHERE id=:id";
      $query = $dbh->prepare($sql);
      $query->bindParam(':id', $id, PDO::PARAM_INT);
      $query->execute();
      $book = $query->fetch(PDO::FETCH_OBJ);

      $status = $book->ReturnStatus;
      // Apres soumission du formulaire de retour de livre
      if (isset($_POST['edit'])) {
          // On recupere le statut
          $status = strip_tags($_POST['status']);

          if($book->ReturnStatus != $status) {
            // On prepare la requete de mise a jour
            $sql = "UPDATE tblissuedbookdetails SET ReturnStatus=:status WHERE id=:id";
            // On prepare la requete de recherche des elements de la categorie dans tblcategory
            $query= $dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            // On execute la requete
            $query->execute();
            // On stocke dans $_SESSION le message "Categorie mise a jour"
            $_SESSION['issuedmsg'] = "Le status du livre a bien été mis à jour";
          } else {
            $_SESSION['issuedmsg'] = "Le status du livre n'a pas été mis à jour";
          }
          // On redirige l'utilisateur vers edit-categories.php
          header('location:manage-issued-books.php');
      }
  }
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Sorties</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="container">
    <div class="row">
      <div class="col">
      <h3 class="header-line p-5">EDITION D'UNE SORTIE</h3>
      </div>
    </div>
      <?php 
            $id = strip_tags($_GET['id']);
            $sql = "SELECT tblbooks.BookName, tblauthors.AuthorName, tblreaders.FullName, tblissuedbookdetails.ReturnStatus FROM tblissuedbookdetails
                  JOIN tblbooks ON tblissuedbookdetails.BookId = tblbooks.id
                  JOIN tblauthors ON tblbooks.AuthorId = tblauthors.id
                  JOIN tblreaders ON tblissuedbookdetails.ReaderID = tblreaders.ReaderId
                  WHERE tblissuedbookdetails.id =:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            $currentBook = $query->fetch(PDO::FETCH_OBJ);

            echo "<p> Lecteur : ".$currentBook->FullName."</p>";
            echo "<p> Titre : ".$currentBook->BookName."</p>";
            echo "<p> Auteur : ".$currentBook->AuthorName."</p>";
      ?>
      <form action="" method="post">
            <label>Status</label>
            <div class="form-inline">
                  <?php
                        // Si la categorie est active (status == 1)
                        // On coche le bouton radio "actif"
                        echo '<input class="form-control col-lg-2" type="radio" name="status" value="1"'.($status == 1 ? 'checked' : '').'> Retourné <br>';
                        // Sinon
                        // On coche le bouton radio "inactif"
                        echo '<input class="form-control col-lg-2" type="radio" name="status" value="0"'.($status == 0 ? 'checked' : '').'> Non retourné <br>';
                  ?>
            </div>
            <button type="submit" name="edit" class="btn btn-info"> Mettre à jour </button>
      </form>
</div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
