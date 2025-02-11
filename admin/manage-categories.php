<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

// Si l'utilisateur est déconnecté
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:../index.php');
} else {
    if(isset($_GET['catId'])) {
        // On recupere l'identifiant de la catégorie a supprimer
        $catId = strip_tags($_GET['catId']);

        //On récupère l'ancien status
        $sql = "SELECT Status from tblcategory WHERE id=:catId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':catId', $catId, PDO::PARAM_INT);
        $query->execute();
        $prevStatus = $query->fetch(PDO::FETCH_OBJ);
        error_log(print_r($prevStatus, 1));

        // On prepare la requete de suppression
        $sql = "UPDATE tblcategory SET Status=0 WHERE id=:catId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':catId', $catId, PDO::PARAM_INT);
        // On execute la requete
        $query->execute();

        //On récupère l'ancien status
        $sql = "SELECT Status from tblcategory WHERE id=:catId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':catId', $catId, PDO::PARAM_INT);
        $query->execute();
        $newStatus = $query->fetch(PDO::FETCH_OBJ);
        // On informe l'utilisateur du resultat de loperation
        if ($prevStatus->Status != $newStatus->Status) {
            $_SESSION['catmsg'] = "La suppression s'est bien déroulée";
        } else {
            $_SESSION['catmsg'] = "La suppression ne s'est pas bien déroulée";
        }
        // On redirige l'utilisateur vers la page manage-categories.php
        header('location:manage-categories.php');
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
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
    <!-- On affiche le titre de la page-->
    <div class="container">
      <div class="row">
        <div class="col">
          <h3>GESTION DES CATEGORIES</h3>
        </div>
      </div>
        <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->
        <div>
            <?php if(isset($_SESSION['catmsg'])) {
                    echo $_SESSION['catmsg'];
            } ?>
        </div>
        <!-- On affiche le formulaire de gestion des categories-->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Status</th>
                    <th scope="col">Crée le</th>
                    <th scope="col">Mise à jour le</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * from tblcategory";
                    $query = $dbh->prepare($sql);
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    // On stocke le résultat dans une variable
                    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($categories as $index => $category) {
                        $catId = $category['id'];
                        echo "<tr>";
                        echo "<td scope='row' class='table-secondary'>$index</td>";
                        echo "<td scope='row' class='table-secondary'>".$category['CategoryName']."</td>";
                        if($category['Status'] == 0) {
                            echo "<td scope='row' class='table-secondary'> Inactif </td>";
                        } else {
                            echo "<td scope='row' class='table-secondary'> Actif </td>";
                        }
                        echo "<td scope='row' class='table-secondary'>".$category['CreationDate']."</td>";
                        echo "<td scope='row' class='table-secondary'>".$category['UpdationDate']."</td>";
                        echo "<td scope='row' class='table-secondary'> <button onCLick='location.href=`edit-category.php?catId=$catId`;'>Editer</button><button onCLick='location.href=`manage-categories.php?catId=$catId`;'>Supprimer</button></td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>