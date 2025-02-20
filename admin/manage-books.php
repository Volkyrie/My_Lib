<?php
session_start();
include('includes/config.php');

// Si l'utilisateur est déconnecté
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login
      header('location:../index.php');
  } else {
      if(isset($_GET['bookId'])) {
        try {
              // On recupere l'identifiant du livre a supprimer
              $bookId = strip_tags($_GET['bookId']);
              // On prepare la requete de suppression
              $sql = "DELETE from tblbooks WHERE id=:bookId";
              $query = $dbh->prepare($sql);
              $query->bindParam(':bookId', $bookId, PDO::PARAM_INT);
              // On execute la requete
              $query->execute();
  
              // On informe l'utilisateur du resultat de loperation
              $_SESSION['bookmsg'] = "La suppression s'est bien déroulée";
        } catch (PDOException $e) {
              $_SESSION['bookmsg'] = "La suppression ne s'est pas bien déroulée";
              exit("Error: " . $e->getMessage());
        }
          
          // On redirige l'utilisateur vers la page manage-books.php
          header('location:manage-books.php');
      }
  }
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
      let openPopup = (bookId) => {
            const popup = document.getElementById('popup');
            let btn = document.getElementById('oui');

            popup.style.display = 'flex';
            btn.setAttribute('onclick', `location.href='manage-books.php?bookId=${bookId}'`);
      }

      let closePopup = () => {
            const popup = document.getElementById('popup');
            popup.style.display = 'none';
      }
    </script>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<div class="container">
      <div class="row">
            <div class="col">
            <h3 class="header-line p-5">GESTION DES LIVRES</h3>
            </div>
      </div>
      <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'un auteur-->
      <div>
      <?php if(isset($_SESSION['bookmsg'])) {
                  echo $_SESSION['bookmsg'];
      } ?>
      </div>
      <!-- On affiche le formulaire de gestion des auteurs-->
      <table class="table table-bordered table-striped">
            <thead>
                  <tr>
                        <th scope="col">#</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Auteur</th>
                        <th scope="col">ISBN</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Action</th>
                  </tr>
            </thead>
            <tbody>
                  <?php 
                        $sql = "SELECT * from tblbooks";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        // On stocke le résultat dans une variable
                        $books = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach($books as $index => $book) {
                              $bookId = $book['id'];
                              $bookCat = $book['CatId'];
                              $bookAuthor = $book['AuthorId'];

                              $sql = "SELECT * from tblcategory WHERE id=:bookCat";
                              $query = $dbh->prepare($sql);
                              $query->bindParam(':bookCat', $bookCat, PDO::PARAM_INT);
                              $query->execute();
                              $currentBook = $query->fetch(PDO::FETCH_OBJ);

                              if(empty($currentBook->CategoryName)) {
                                    $bookCat = "NaN";
                              } else {
                                    $bookCat = $currentBook->CategoryName;
                              }
                              
                              $sql2 = "SELECT * from tblauthors WHERE id=:bookAuthor";
                              $query2 = $dbh->prepare($sql2);
                              $query2->bindParam(':bookAuthor', $bookAuthor, PDO::PARAM_INT);
                              $query2->execute();
                              $currentBook = $query2->fetch(PDO::FETCH_OBJ);
                              $bookAuthor = $currentBook->AuthorName;

                              echo "<tr>";
                              echo "<td scope='row'>$index</td>";
                              echo "<td scope='row'>".$bookCat."</td>";
                              echo "<td scope='row'>".$book['BookName']."</td>";
                              echo "<td scope='row'>".$bookAuthor."</td>";
                              echo "<td scope='row'>".$book['ISBNNumber']."</td>";
                              echo "<td scope='row'>".$book['BookPrice']."</td>";
                              echo "<td scope='row'> <button onCLick='location.href=`edit-book.php?bookId=$bookId`'>Editer</button><button onCLick='openPopup($bookId)'>Supprimer</button></td>";
                              echo "</tr>";
                        }
                  ?>
            </tbody>
      </table>
      <div id='popup' class='container p-3 flex-column position-absolute justify-content-center rounded align-items-center col-md-4 bg-light border border-info' style='display: none; top: 350px; left: 33%;'>
            <div class='row'>
                  <div class='col'>
                        <p>Êtes vous sûr de vouloir supprimer ce livre?</p>
                  </div>
            </div>
            <div class='row'>
                  <div class='col'>
                        <button id='oui'>Oui</button>
                        <button onCLick='closePopup()'>Non</button>
                  </div>
            </div>
      </div>
</div>
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
