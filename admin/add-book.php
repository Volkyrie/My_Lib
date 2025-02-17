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
    $name = strip_tags($_POST['name']);
    $category = strip_tags($_POST['category']);
    $author = strip_tags($_POST['author']);
    $isbn = strip_tags($_POST['isbn']);
    $price = strip_tags($_POST['price']);
    error_log(print_r($author, 1));

    //On récupère les identifiants category et author
    // $sql = "SELECT * from tblcategory WHERE CategoryName=:category";
    // $query = $dbh->prepare($sql);
    // $query->bindParam(':category', $category, PDO::PARAM_INT);
    // $query->execute();
    // $category = $query->fetch(PDO::FETCH_OBJ);
    // $categoryId = $category->id;

    // $sql = "SELECT * from tblauthors WHERE AuthorName=:author";
    // $query = $dbh->prepare($sql);
    // $query->bindParam(':author', $author, PDO::PARAM_INT);
    // $query->execute();
    // $author = $query->fetch(PDO::FETCH_OBJ);
    // $authorId = $author->id;
    // error_log(print_r($authorId, 1));

    $sql = "SELECT COUNT(*) from tblbooks where ISBNNumber=:isbn";
    $query = $dbh->prepare($sql);
    $query->bindParam(':isbn', $isbn, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchColumn();

    if($result < 1) {
      // On prepare la requete d'insertion dans la table tblbooks
      $sql = "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice) VALUES (:name, :cat, :author, :number, :price)";
      // On execute la requete
      $query = $dbh->prepare($sql);
      $query->bindParam(':name', $name, PDO::PARAM_STR);
      $query->bindParam(':cat', $category, PDO::PARAM_INT);
      $query->bindParam(':author', $author, PDO::PARAM_INT);
      $query->bindParam(':number', $isbn, PDO::PARAM_INT);
      $query->bindParam(':price', $price, PDO::PARAM_INT);
      $query->execute();
      $lastId = $dbh->lastInsertId();
      // On stocke dans $_SESSION le message correspondant au resultat de loperation
      if (isset($lastId)) {
            $_SESSION['bookmsg'] = "Le livre a bien été ajouté";
      } else {
            $_SESSION['bookmsg'] = "Le livre n'a pas été ajouté";
      }
    } else {
      echo "<script> alert('Cet ISBN est déjà pris');</script>";
    }
    
  }
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
      <h3>AJOUT D'UN LIVRE</h3>
      </div>
    </div>
    <!-- On affiche le formulaire de creation-->
    <div class="row">
      <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
        <div class="panel panel-info">
          <div class="panel-body border border-info rounded">
            <h4 class="bg-info text-dark">Informations livre</h4>
            <form role="form" method="post" action="">
              <div class="form-group">
                <label for="name">Titre *</label>
                <input class="form-control" type="text" name="name" required autocomplete="off" />
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
                        echo '<option value="'.$category['id'].'"> '.$category['CategoryName'].' </option>';
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
                      echo '<option value="'.$author['id'].'"> '.$author['AuthorName'].' </option>';
                    }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="isbn">ISBN *</label>
                <input class="form-control" type="text" name="isbn" required autocomplete="off" />
              </div>
              <div class="form-group">
                <label for="price">Prix *</label>
                <input class="form-control" type="text" name="price" required autocomplete="off" />
              </div>
              <button type="submit" name="create" class="btn btn-info"> Ajouter </button>
            </form>
          </div>
        </div>
      </div>
		</div>
</div>
<!-- MENU SECTION END-->

     <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
