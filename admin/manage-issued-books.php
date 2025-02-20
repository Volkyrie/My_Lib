<?php
session_start();

include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
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
     <!-- CONTENT-WRAPPER SECTION END-->
<div class="container">
      <div class="row">
            <div class="col">
            <h3 class="header-line p-5">GESTION DES SORTIES</h3>
            </div>
      </div>
      <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'un auteur-->
      <div>
      <?php if(isset($_SESSION['issuedmsg'])) {
                  echo $_SESSION['issuedmsg'];
      } ?>
      </div>
      <!-- On affiche le formulaire de gestion des auteurs-->
      <table class="table table-bordered table-striped">
            <thead>
                  <tr>
                        <th scope="col">#</th>
                        <th scope="col">Lecteur</th>
                        <th scope="col">Titre</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Sortie le</th>
                        <th scope="col">Retourné le</th>
                        <th scope="col">Action</th>
                  </tr>
            </thead>
            <tbody>
                  <?php 
                        $sql = "SELECT * from tblissuedbookdetails";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        // On stocke le résultat dans une variable
                        $books = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach($books as $index => $book) {
                            $bookId = $book['BookId'];
                            $reader = $book['ReaderID'];
                            $id = $book['id'];

                            $sql = "SELECT * from tblreaders WHERE ReaderId=:readerId";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':readerId', $reader, PDO::PARAM_STR);
                            $query->execute();
                            $currentReader = $query->fetch(PDO::FETCH_OBJ);

                            $sql = "SELECT * from tblbooks WHERE id=:bookId";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':bookId', $bookId, PDO::PARAM_INT);
                            $query->execute();
                            $currentBook = $query->fetch(PDO::FETCH_OBJ);

                            echo "<tr>";
                            echo "<td scope='row'>$index</td>";
                            if(isset($currentReader->FullName)) {
                            echo "<td scope='row'>".$currentReader->FullName."</td>";
                            } else {
                            echo "<td scope='row'> Inconnu </td>";
                            }
                            
                            echo "<td scope='row'>".$currentBook->BookName."</td>";
                            echo "<td scope='row'>".$currentBook->ISBNNumber."</td>";
                            echo "<td scope='row'>".$book['IssuesDate']."</td>";
                            if(isset($book['ReturnDate'])) {
                            echo "<td scope='row'>".$book['ReturnDate']."</td>";
                            } else {
                            echo "<td scope='row'> Non retourné </td>";
                            }
                            echo "<td scope='row'> <button onCLick='location.href=`edit-issue-book.php?id=$id`'>Editer</button>";
                            echo "</tr>";
                        }
                  ?>
            </tbody>
      </table>
</div>
 <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

