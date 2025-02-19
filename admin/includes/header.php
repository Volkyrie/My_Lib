<!-- <div class="navbar navbar-inverse set-radius-zero" >
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">
                    img src="assets/img/logo.png" /
                </a>
            </div>

            <div class="right-div">
                <a href="logout.php" class="btn btn-danger pull-right">Déconnexion</a>
            </div>
        </div>
    </div> -->
    <!-- LOGO HEADER END-->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="dropdown" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse" id="collapsibleNavbar">
            <ul class="nav navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">TABLEAU DE BORD</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" id="ddlmenuItem" data-toggle="dropdown"> CATEGORIES</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./add-category.php"  class="dropdown-item">Ajouter une catégorie</a></li>
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./manage-categories.php"  class="dropdown-item">Gérer les catégories</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" id="ddlmenuItem" data-toggle="dropdown"> AUTEURS</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./add-author.php"  class="dropdown-item">Ajouter un auteur</a></li>
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./manage-authors.php"  class="dropdown-item">Gérer les auteurs</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" id="ddlmenuItem" data-toggle="dropdown"> LIVRES</a>
                    <ul class="dropdown-menu nav-item" role="menu" aria-labelledby="ddlmenuItem">
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./add-book.php" class="dropdown-item">Ajouter un livre</a></li>
                        <li role="presentation" class="nav-item "><a role="menuitem" tabindex="-1" href="./manage-books.php" class="dropdown-item">Gérer les livres</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="dropdown-toggle nav-link" id="ddlmenuItem" data-toggle="dropdown"> SORTIES</a>
                    <ul class="dropdown-menu nav-item" role="menu" aria-labelledby="ddlmenuItem">
                        <li role="presentation" class="nav-item"><a role="menuitem" tabindex="-1" href="./add-issue-book.php"  class="dropdown-item">Ajouter une sortie</a></li>
                        <li role="presentation" class="nav-item"><a role="menuitem" tabindex="-1" href="./manage-issued-books.php"  class="dropdown-item">Gérer les sorties</a></li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="dropdown-toggle nav-link" id="ddlmenuItem" data-toggle="dropdown"> Lecteurs <i class="fa fa-angle-down"</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                        <li role="presentation" class="nav-link"><a role="menuitem" tabindex="-1" href="add-reader.php" class="nav-link">Ajouter un lecteur</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="reg-students.php" class="nav-link">Gérer les lecteurs</a></li>
                    </ul>
                </li> -->
                <li class="nav-item"><a href="reg-readers.php" class="nav-link">LECTEURS</a></li>
                <li class="nav-item"><a href="change-password.php" class="nav-link">MODIFIER LE MOT DE PASSE</a></li>
            </ul>
        </div>
        <div class="pull-right">
            <a href="logout.php" class="btn btn-danger pull-right">DECONNEXION</a>
        </div> 
    </nav>