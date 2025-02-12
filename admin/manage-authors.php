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
    if(isset($_GET['authorId'])) {
      try {
            // On recupere l'identifiant de l'auteur a supprimer
            $authorId = strip_tags($_GET['authorId']);

            // On prepare la requete de suppression
            $sql = "DELETE from tblauthors WHERE id=:authorId";
            $query = $dbh->prepare($sql);
            $query->bindParam(':authorId', $authorId, PDO::PARAM_INT);
            // On execute la requete
            $query->execute();

            // On informe l'utilisateur du resultat de loperation
            $_SESSION['authormsg'] = "La suppression s'est bien déroulée";
      } catch (PDOException $e) {
            $_SESSION['authormsg'] = "La suppression ne s'est pas bien déroulée";
            exit("Error: " . $e->getMessage());
      }
        
        // On redirige l'utilisateur vers la page manage-authors.php
        header('location:manage-authors.php');
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion auteurs</title>
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
          <h3>GESTION DES AUTEURS</h3>
        </div>
      </div>
        <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'un auteur-->
        <div>
            <?php if(isset($_SESSION['authormsg'])) {
                    echo $_SESSION['authormsg'];
            } ?>
        </div>
        <!-- On affiche le formulaire de gestion des auteurs-->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Crée le</th>
                    <th scope="col">Mise à jour le</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * from tblauthors";
                    $query = $dbh->prepare($sql);
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    // On stocke le résultat dans une variable
                    $authors = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($authors as $index => $author) {
                        $authorId = $author['id'];
                        echo "<tr>";
                        echo "<td scope='row' class='table-secondary'>$index</td>";
                        echo "<td scope='row' class='table-secondary'>".$author['AuthorName']."</td>";
                        echo "<td scope='row' class='table-secondary'>".$author['creationDate']."</td>";
                        echo "<td scope='row' class='table-secondary'>".$author['UpdationDate']."</td>";
                        echo "<td scope='row' class='table-secondary'> <button onCLick='location.href=`edit-author.php?authorId=$authorId`;'>Editer</button><button onCLick='location.href=`manage-authors.php?authorId=$authorId`;'>Supprimer</button></td>";
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