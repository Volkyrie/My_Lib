<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');

// Si l'utilisateur n'est plus logué
if ($_SESSION['rdid'] == 0) {
    // On le redirige vers la page de login
    echo "<script>alert('Vous avez été déconnecté');</script>";
    header('location:index.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de profil
    if (isset($_POST['update'])){
        // On recupere l'id du lecteur (cle secondaire)
        $readerId = $_SESSION['rdid'];
        error_log(print_r($_SESSION, 1));
        // On recupere le nom complet du lecteur
        $userName = $_POST['userName'];
        // On recupere le numero de portable
        $userMobile = $_POST['mobileNumber'];
        // On recupere l'adresse mail
        $userEmail = $_POST['userEmail'];
        // On update la table tblreaders avec ces valeurs
        // On informe l'utilisateur du resultat de l'operation
        $sql = "UPDATE tblreaders SET FullName=:userName, MobileNumber=:mobileNumber, EmailId=:userEmail WHERE ReaderId=:readerId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userName', $userName, PDO::PARAM_STR);
        $query->bindParam(':mobileNumber', $userMobile, PDO::PARAM_STR);
        $query->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
        $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Vous avez mis à jour vos données');</script>";
    }
    // On souhaite voir la fiche de lecteur courant.
    // On recupere l'id de session dans $_SESSION
    $readerId = $_SESSION['rdid'];
    // On prepare la requete permettant d'obtenir
    $sql = "SELECT EmailId, RegDate, UpdateDate, Status, FullName, MobileNumber, EmailId from tblreaders WHERE ReaderId=:readerId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':readerId', $readerId, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    $readerRegistration = $result->RegDate;
    $readerEdit = $result->UpdateDate;
    $readerStatus = $result->Status ? "Actif" : "Inactif";
    $readerName = $result->FullName;
    $readerMobile = $result->MobileNumber;
    $readerEmail = $result->EmailId;
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <!--On affiche le titre de la page : EDITION DU PROFIL-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <!--pad-botm-->
                <div class="col-md-12">
                    <h4 class="header-line">MON COMPTE</h4>
                </div>
            </div>
            <!--On affiche le formulaire-->
            <div class="row">
                <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 offset-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <!--On créé le formulaire de creation de compte-->
                            <form action="my-profile.php" method="POST">
                                <!--On affiche l'identifiant - non editable-->
                                <div class="form-group">
                                    <label>Identifiant: <?php echo $readerId ?></label>
                                </div>
                                <!--On affiche la date d'enregistrement - non editable-->
                                <div class="form-group">
                                    <label>Date d'enregistrement: <?php echo $readerRegistration ?> </label>
                                </div>
                                <!--On affiche la date de derniere mise a jour - non editable-->
                                <div class="form-group">
                                    <label>Dernière mise à jour: <?php echo $readerEdit ?> </label>
                                </div>
                                <!--On affiche la statut du lecteur - non editable-->
                                <div class="form-group">
                                    <label>Status: <?php echo $readerStatus ?> </label>
                                </div>
                                <!--On affiche le nom complet - editable-->
                                <div class="form-group">
                                    <label>Nom complet:</label>
                                    <input class="form-control" type="text" name="userName" required style="height:25px;" placeholder="<?php echo $readerName?>">
                                </div>
                                <!--On affiche le numero de portable- editable-->
                                <div class="form-group">
                                    <label>Numéro portable:</label>
                                    <input class="form-control" type="text" name="mobileNumber" required style="height:25px;" placeholder="<?php echo $readerMobile?>">
                                </div>
                                <!--On affiche l'adresse mail'- editable-->
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input class="form-control" type="text" name="userEmail" required style="height:25px;" placeholder="<?php echo $readerEmail?>">
                                </div>
                                <button type="submit" name="update" class="btn btn-info" id="update">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            

    <!--On affiche l'email- editable-->
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>