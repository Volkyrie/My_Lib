<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');
} else {
    // Sinon
    $catId = strip_tags($_GET['catId']);
    $sql = "SELECT CategoryName, Status from tblcategory WHERE id=:catId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':catId', $catId, PDO::PARAM_INT);
    $query->execute();
    $category = $query->fetch(PDO::FETCH_OBJ);
    $status = $category->Status;
    $catName = $category->CategoryName;
    // Apres soumission du formulaire de categorie
    if (isset($_POST['edit'])) {
        // On recupere l'identifiant, le statut, le nom
        $status = strip_tags($_POST['status']);
        $catName = strip_tags($_POST['catName']);
        $catId = strip_tags($_GET['catId']);
        // On prepare la requete de mise a jour
        $sql = "UPDATE tblcategory SET CategoryName=:catName, Status=:status WHERE id=:catId";
        // On prepare la requete de recherche des elements de la categorie dans tblcategory
        $query= $dbh->prepare($sql);
        $query->bindParam(':catName', $catName, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':catId', $catId, PDO::PARAM_INT);
        // On execute la requete
        $query->execute();
        // On stocke dans $_SESSION le message "Categorie mise a jour"
        $_SESSION['catmsg'] = "La catégorie a bien été mise à jour";
        // On redirige l'utilisateur vers edit-categories.php
        header('location:edit-category.php?catId='.$catId);
    }
}

?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page "Editer la categorie-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line p-5">Editer la categorie</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <!-- On affiche ici le formulaire d'édition -->
                <form role="form" method="post" action="">
                    <div class="form-group">
                        <label>Nom</label>
                        <?php echo '<input class="form-control" type="text" name="catName" required autocomplete="off" value="'.$catName.'" placeholder="'.$catName.'"/> ';?>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <div class="form-inline">
                            <?php
                                // Si la categorie est active (status == 1)
                                // On coche le bouton radio "actif"
                                echo '<input class="form-control col-lg-2" type="radio" name="status" value="1"'.($status == 1 ? 'checked' : '').'> Actif <br>';
                                // Sinon
                                // On coche le bouton radio "inactif"
                                echo '<input class="form-control col-lg-2" type="radio" name="status" value="0"'.($status == 0 ? 'checked' : '').'> Inactif <br>';
                            ?>
                        </div>
                    </div>
                    <button type="submit" name="edit" class="btn btn-info"> Mettre à jour </button>
                </form>
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