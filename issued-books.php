<?php
// On récupère la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
if ($_SESSION['rdid'] == 0) {
    // On le redirige vers la page de login
    echo "<script>alert('Vous avez été déconnecté');</script>";
    header('location:index.php');
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On insere ici le menu de navigation T-->
    <?php include('includes/header.php'); ?>
    <!-- On affiche le titre de la page : LIVRES SORTIS -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <!--pad-botm-->
                <div class="col-md-12">
                    <h4 class="header-line">LIVRES EMPRUNTES</h4>
                </div>
            </div>
            <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
            <!-- Si il n'y a pas de date de retour, on affiche non retourne -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Date de sortie</th>
                        <th scope="col">Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $readerId = $_SESSION['rdid'];
                    $sql = "SELECT * from tblissuedbookdetails WHERE ReaderID=:readerId";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
                    $query->execute();
                    // On stocke le résultat dans une variable
                    $books = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($books as $index => $book) {
                        if($book['ReturnDate']) {
                            $retour = "<td scope='row' class='table-secondary'>".$book['ReturnDate']."</td>";
                        } else {
                            $retour = "<td scope='row' class='table-secondary text-danger'>Non retourné </td>";
                        }

                        $bookId = $book['BookId'];

                        $sql2 = "SELECT * from tblbooks WHERE id=:bookId";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':bookId', $bookId, PDO::PARAM_STR);
                        $query2->execute();
                        $currentBook = $query2->fetch(PDO::FETCH_OBJ);
                        echo "<tr>";
                        echo "<td scope='row' class='table-secondary'>$index</td>";
                        echo "<td scope='row' class='table-secondary'>".$currentBook->BookName."</td>";
                        echo "<td scope='row' class='table-secondary'>".$currentBook->ISBNNumber."</td>";
                        echo "<td scope='row' class='table-secondary'>".$book['IssuesDate']."</td>";
                        echo $retour;
                        echo "</tr>";
                    }
                ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>