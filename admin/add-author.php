<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login
      header('location:../index.php');
} else {
      // Sinon on peut continuer. Après soumission du formulaire de creation
      if (isset($_POST['create'])) {
            // On recupere le nom de l'auteur
            $author = strip_tags($_POST['author']);
            // On prepare la requete d'insertion dans la table tblauthors
            $sql = "INSERT INTO tblauthors (AuthorName) VALUES (:author)";
            // On execute la requete
            $query = $dbh->prepare($sql);
            $query->bindParam(':author', $author, PDO::PARAM_STR);
            $query->execute();
            $lastId = $dbh->lastInsertId();
            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            if (isset($lastId)) {
                  $_SESSION['authormsg'] = "L'auteur a bien été ajouté";
            } else {
                  $_SESSION['authormsg'] = "L'auteur n'a pas été ajouté";
            }
      }
}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout des auteurs</title>
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
      <div class="container">
            <div class="row">
                  <div class="col">
                  <h3>AJOUT D'UN AUTEUR</h3>
                  </div>
            </div>
            <!-- On affiche le formulaire de creation-->
            <div class="row">
                  <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
                        <div class="panel panel-info">
                              <div class="panel-body border border-info rounded">
                                    <h4 class="bg-info text-dark">Information auteur</h4>
                                    <form role="form" method="post" action="">
                                          <div class="form-group">
                                                <label>Nom</label>
                                                <input class="form-control" type="text" name="author" required autocomplete="off" />
                                          </div>
                                          <button type="submit" name="create" class="btn btn-info"> Créer </button>
                                    </form>
                              </div>
                        </div>
                  </div>
		</div>
      </div>
     <!-- CONTENT-WRAPPER SECTION END-->
      <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
