<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../index.php');
  } else {
      // Sinon
      $bookId = strip_tags($_GET['bookId']);
      $sql = "SELECT * from tblbooks WHERE id=:bookId";
      $query = $dbh->prepare($sql);
      $query->bindParam(':bookId', $bookId, PDO::PARAM_INT);
      $query->execute();
      $book = $query->fetch(PDO::FETCH_OBJ);
      $bookName = $book->BookName;
      $bookAuthor = $book->AuthorId;
      $bookCat = $book->CatId;
      $bookNum = $book->ISBNNumber;
      $bookPrice = $book->BookPrice;

      // Apres soumission du formulaire de categorie
      if (isset($_POST['edit'])) {
            // On recupere l'identifiant, le statut, le nom
            $bookId = strip_tags($_GET['bookId']);
            $name = strip_tags($_POST['bookName']);
            $category = (int) strip_tags($_POST['category']);
            $author = (int) strip_tags($_POST['author']);
            $isbn = (int) strip_tags($_POST['isbn']);
            $price = strip_tags($_POST['price']);

            $sql = "SELECT COUNT(*) from tblbooks where ISBNNumber=:isbn";
            $query = $dbh->prepare($sql);
            $query->bindParam(':isbn', $isbn, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchColumn();

            if($result < 2) {
                  // On prepare la requete de mise a jour
                  $sql = "UPDATE tblbooks SET BookName=:name, CatId=:catId, AuthorId=:authorId, ISBNNumber=:isbnNumber, BookPrice=:bookPrice  WHERE id=:BookId";
                  // On execute la requete
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':name', $name, PDO::PARAM_STR);
                  $query->bindParam(':catId', $category, PDO::PARAM_INT);
                  $query->bindParam(':authorId', $author, PDO::PARAM_INT);
                  $query->bindParam(':isbnNumber', $isbn, PDO::PARAM_INT);
                  $query->bindParam(':bookPrice', $price, PDO::PARAM_INT);
                  $query->bindParam(':BookId', $bookId, PDO::PARAM_INT);
                  $query->execute();

                  // On stocke dans $_SESSION le message correspondant au resultat de loperation
                  $_SESSION['bookmsg'] = "Le livre a bien été modifié";
            } else {
                  echo "<script> alert('Cet ISBN est déjà pris');</script>";
            }
                
            // On redirige l'utilisateur vers manage-books.php
            header('location:manage-books.php');
      }
  }
?>

<!DOCTYPE html>
<html>

<head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

      <title>Gestion de bibliothèque en ligne | Livres</title>
      <!-- BOOTSTRAP CORE STYLE  -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
      <!-- FONT AWESOME STYLE  -->
      <link href="assets/css/font-awesome.css" rel="stylesheet" />
      <!-- CUSTOM STYLE  -->
      <link href="assets/css/style.css" rel="stylesheet" />>
</head>

<body>
      <!------MENU SECTION START-->
      <?php include('includes/header.php'); ?>

<div class="container">
    <div class="row">
      <div class="col">
      <h3>EDITION D'UN LIVRE</h3>
      </div>
    </div>
    <!-- On affiche le formulaire d'edition'-->
      <div class="row">
            <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
                  <div class="panel panel-info">
                        <div class="panel-body border border-info rounded">
                              <h4 class="bg-info text-dark">Informations livre</h4>
                              <form role="form" method="post" action="">
                                    <div class="form-group">
                                          <label for="name">Titre *</label>
                                          <?php echo '<input class="form-control" type="text" name="bookName" required autocomplete="off" value="'.$bookName.'" placeholder="'.$bookName.'"/> ';?>
                                    </div>
                                    <div class="form-group">
                                          <label for="category">Categorie *</label><br>
                                          <select class="form-control" name="category" id="category" required>
                                                <?php 
                                                      $sql = "SELECT * from tblcategory";
                                                      $query = $dbh->prepare($sql);
                                                      $query->execute();
                                                      $categories = $query->fetchAll(PDO::FETCH_ASSOC);
                                                      foreach($categories as $category) {
                                                            if($category['id'] == $bookCat) {
                                                                  echo '<option value="'.$category['id'].'" selected> '.$category['CategoryName'].'  </option>';
                                                            } else {
                                                                  echo '<option value="'.$category['id'].'"> '.$category['CategoryName'].' </option>';
                                                            }
                                                      }
                                                ?>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label for="author">Auteur *</label><br>
                                          <select class="form-control" name="author" id="author" required>
                                                <?php 
                                                      $sql = "SELECT * from tblauthors";
                                                      $query = $dbh->prepare($sql);
                                                      $query->execute();
                                                      $authors = $query->fetchAll(PDO::FETCH_ASSOC);
                                                      foreach($authors as $author) {
                                                            if($author['id'] == $bookAuthor) {
                                                                  echo '<option value="'.$author['id'].'" selected> '.$author['AuthorName'].' </option>';
                                                            } else {
                                                                  echo '<option value="'.$author['id'].'"> '.$author['AuthorName'].' </option>';
                                                            }
                                                      }
                                                ?>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label for="isbn">ISBN *</label>
                                          <?php echo '<input class="form-control" type="text" name="isbn" required autocomplete="off" value="'.$bookNum.'" placeholder="'.$bookNum.'"/> ';?>
                                    </div>
                                    <div class="form-group">
                                          <label for="price">Prix *</label>
                                          <?php echo '<input class="form-control" type="text" name="price" required autocomplete="off" value="'.$bookPrice.'" placeholder="'.$bookPrice.'"/> ';?>
                                    </div>
                                          <button type="submit" name="edit" class="btn btn-info"> Mettre à jour </button>
                              </form>
                        </div>
                  </div>
            </div>
      </div>
</div>      
      <!-- MENU SECTION END-->

      <!-- CONTENT-WRAPPER SECTION END-->
      <?php include('includes/footer.php'); ?>
      <!-- FOOTER SECTION END-->
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>