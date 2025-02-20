<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login
    header('location:../index.php');
} else {
    // Sinon on affiche la liste des lecteurs de la table tblreaders
    // Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['deactivate']
    // et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['deactivate'])) {
        try {
              // On recupere l'identifiant du lecteur à mettre à désactiver
              $id = strip_tags($_GET['deactivate']);
              error_log($id, 0);
              // On prepare la requete de mise à jour
              $sql = "UPDATE tblreaders SET Status=0 WHERE ReaderId=:id";
              $query = $dbh->prepare($sql);
              $query->bindParam(':id', $id, PDO::PARAM_STR);
              // On execute la requete
              $query->execute();
  
              // On informe l'utilisateur du resultat de loperation
              $_SESSION['readermsg'] = "La désactivation s'est bien déroulée";
        } catch (PDOException $e) {
              $_SESSION['readermsg'] = "La désactivation ne s'est pas bien déroulée";
              exit("Error: " . $e->getMessage());
        }
        // On redirige l'utilisateur vers la page reg-readers.php
        header('location:reg-readers.php');
    }
  
    // Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['activate']
    // et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['activate'])) {
        try {
            // On recupere l'identifiant du lecteur à activer
            $id = strip_tags($_GET['activate']);
            error_log($id, 0);
            // On prepare la requete de mise à jour
            $sql = "UPDATE tblreaders SET Status=1 WHERE ReaderId=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            // On execute la requete
            $query->execute();

            // On informe l'utilisateur du resultat de loperation
            $_SESSION['readermsg'] = "L'activation s'est bien déroulée";
        } catch (PDOException $e) {
            $_SESSION['readermsg'] = "L'activation ne s'est pas bien déroulée";
            exit("Error: " . $e->getMessage());
        }
        // On redirige l'utilisateur vers la page reg-readers.php
        header('location:reg-readers.php');
    }
      
    // Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['delete']
    // et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur
    if(isset($_GET['delete'])) {
        try {
            // On recupere l'identifiant de l'auteur a supprimer
            $id = strip_tags($_GET['delete']);
            error_log($id, 0);

            // On prepare la requete de suppression
            $sql = "UPDATE tblreaders SET Status=2 WHERE ReaderId=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            // On execute la requete
            $query->execute();

            // On informe l'utilisateur du resultat de loperation
            $_SESSION['readermsg'] = "La suppression s'est bien déroulée";
        } catch (PDOException $e) {
            $_SESSION['readermsg'] = "La suppression ne s'est pas bien déroulée";
            exit("Error: " . $e->getMessage());
        }
        // On redirige l'utilisateur vers la page reg-readers.php
        header('location:reg-readers.php');
    }  
}

// On récupère tous les lecteurs dans la base de données
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>
    <!-- Titre de la page (Gestion du Registre des lecteurs) -->
    <div class="container">
      <div class="row">
            <div class="col">
            <h3 class="header-line p-5">GESTION DES SORTIES</h3>
            </div>
      </div>
      <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'un auteur-->
      <div>
      <?php if(isset($_SESSION['readermsg'])) {
                  echo $_SESSION['readermsg'];
      } ?>
      </div>
      <!--On insère ici le tableau des lecteurs.
       On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->
      <table class="table table-bordered table-striped">
            <thead>
                  <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID Lecteur</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Portable</th>
                        <th scope="col">Date de reg</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                  </tr>
            </thead>
            <tbody>
            <?php 
                    $sql = "SELECT * from tblreaders";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    // On stocke le résultat dans une variable
                    $readers = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach($readers as $index => $reader) {
                        $readerId = $reader['ReaderId'];
                        echo "<tr>";
                        echo "<td scope='row'>$index</td>";
                        echo "<td scope='row'>".$reader['ReaderId']."</td>";
                        echo "<td scope='row'>".$reader['FullName']."</td>";
                        echo "<td scope='row'>".$reader['EmailId']."</td>";
                        echo "<td scope='row'>".$reader['MobileNumber']."</td>";
                        echo "<td scope='row'>".$reader['RegDate']."</td>";
                        if($reader['Status'] == 1) {
                            echo "<td scope='row'> Actif</td>";
                            echo "<td scope='row'> <button type='button' style='color: white;' class='btn btn-warning' onCLick='location.href=`reg-readers.php?deactivate=$readerId`'>Inactif</button> <button type='button' class='btn btn-danger' onCLick='location.href=`reg-readers.php?delete=$readerId`'>Supprimer</button>";
                        } else if($reader['Status'] == 0) {
                            echo "<td scope='row'> Inactif </td>";
                            echo "<td scope='row'> <button type='button' class='btn btn-success' onCLick='location.href=`reg-readers.php?activate=$readerId`'>Actif</button> <button type='button' class='btn btn-danger' onCLick='location.href=`reg-readers.php?delete=$readerId`'>Supprimer</button>";
                        } else {
                            echo "<td scope='row'> Supprimé </td>";
                        }
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.budnle.min.js"></script>
</body>

</html>