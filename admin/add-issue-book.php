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
        // On recupere l'identifiant
        $rdid = strip_tags($_POST['rd']);

        // On recupere le nom du livre
        $isbn = (int) strip_tags($_POST['isbn']);
        error_log($isbn, 0);
        //On récupère l'Id du livre
        $sql = "SELECT * from tblbooks WHERE ISBNNumber=:isbn";
        $query = $dbh->prepare($sql);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $bookId = $result->id;

        // On prepare la requete d'insertion dans la table tblissuedbookdetails
        $sql = "INSERT INTO tblissuedbookdetails (BookId, ReaderId, ReturnStatus) VALUES (:bookId, :readerId, 0)";
        // On execute la requete
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookId', $bookId, PDO::PARAM_INT);
        $query->bindParam(':readerId', $rdid, PDO::PARAM_STR);
        $query->execute();
        $lastId = $dbh->lastInsertId();
        // On stocke dans $_SESSION le message correspondant au resultat de loperation
        if (isset($lastId)) {
            $_SESSION['issuedmsg'] = "Le livre a bien été emprunté";
        } else {
                $_SESSION['issuedmsg'] = "Le livre n'a pas été emprunté";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
        // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant
        let getReader = () => {
            const msg = document.getElementById('rdId');
            const rdId = document.getElementById('rd').value;
            const btn = document.getElementById('create');

            fetch(`get_reader.php?id=${rdId}`)
            .then(response => response.json())
            .then(data => {
                msg.style.opacity = "1";
                msg.innerHTML = data.msg;
                if(data.rep === "nok") {
                    btn.classList.add("disabled");
                    btn.disabled = true;
                } else {
                    btn.classList.remove("disabled");
                    btn.disabled = false;
                }
            })
            .catch(error => console.error('Erreur lors de la vérification de l\'identifiant:', error));
        }
        // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN
        let getIsbn = () => {
            const msg = document.getElementById('isbnId');
            const isbn = document.getElementById('isbn').value;
            const btn = document.getElementById('create');

            fetch(`get_book.php?isbn=${isbn}`)
            .then(response => response.json())
            .then(data => {
                msg.style.opacity = "1";
                msg.innerHTML = data.msg;
                if(data.rep === "nok") {
                    btn.classList.add("disabled");
                    btn.disabled = true;
                }
                else {
                    btn.classList.remove("disabled");
                    btn.disabled = false;
                }
            })
            .catch(error => console.error('Erreur lors de la vérification de l\'ISBN:', error));
        }
    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

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
          <h4 class="bg-info text-dark">Sortie d'un livre</h4>
          <form role="form" method="post" action="">
            <div class="form-group">
              <label for="name">Identifiant lecteur*</label>
              <input id="rd" class="form-control" type="text" name="rd" required autocomplete="off" onBlur="getReader()"/>
              <p id="rdId" style="opacity: 0;"></p>
            </div>
            <div class="form-group">
              <label for="isbn">ISBN *</label>
              <input id="isbn" class="form-control" type="text" name="isbn" required autocomplete="off" onBlur="getIsbn()"/>
              <p id="isbnId" style="opacity: 0;"></p>
            </div>
            <button type="submit" id="create" name="create" class="btn btn-info"> Créer la sortie </button>
          </form>
        </div>
      </div>
    </div>
      </div>
</div>
    <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre 
 sur evenement onBlur-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>