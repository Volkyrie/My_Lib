<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');
} else {
    // Sinon
    $authorId = strip_tags($_GET['authorId']);
    $sql = "SELECT AuthorName from tblauthors WHERE id=:authorId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':authorId', $authorId, PDO::PARAM_INT);
    $query->execute();
    $author = $query->fetch(PDO::FETCH_OBJ);
    $authorName = $author->AuthorName;
    // Apres soumission du formulaire de categorie
    if (isset($_POST['edit'])) {
        // On recupere l'identifiant, le statut, le nom
        $authorName = strip_tags($_POST['authorName']);
        $authorId = strip_tags($_GET['authorId']);
        // On prepare la requete de mise a jour
        $sql = "UPDATE tblauthors SET AuthorName=:authorName WHERE id=:authorId";
        // On prepare la requete de recherche des elements de la categorie dans tblauthors
        $query= $dbh->prepare($sql);
        $query->bindParam(':authorName', $authorName, PDO::PARAM_STR);
        $query->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        // On execute la requete
        $query->execute();
        // On stocke dans $_SESSION le message "Categorie mise a jour"
        $_SESSION['authormsg'] = "L'auteur a bien été mis à jour";
        // On redirige l'utilisateur vers edit-categories.php
        header('location:edit-author.php?authorId='.$authorId);
    }
}

?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>
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
    <!-- On affiche le titre de la page "Editer l'auteur'-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer l'auteur</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <!-- On affiche ici le formulaire d'édition -->
                <form role="form" method="post" action="">
                    <div class="form-group">
                        <label>Nom</label>
                        <?php echo '<input class="form-control" type="text" name="authorName" required autocomplete="off" value="'.$authorName.'" placeholder="'.$authorName.'"/> ';?>
                    </div>
                    <button type="submit" name="edit" class="btn btn-info"> Mettre à jour </button>
                </form>
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
