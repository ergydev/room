<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= URL; ?>index.php"><h1>ROOM</h1></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= URL; ?>index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= URL; ?>panier.php"><i class="fa-solid fa-cart-shopping"></i></a>
        </li>


        <?php 
          if(!user_is_connected()) {
        ?>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-expanded="false">Espace Membre</a>
          <ul class="dropdown-menu" aria-labelledby="dropdown02">
              <li><a class="dropdown-item" href="<?= URL; ?>connexion.php">Connexion</a></li>
              <li><a class="dropdown-item" href="<?= URL; ?>inscription.php">Inscription</a></li>
          </ul>
        </li>

        

        
        <?php } else { ?>
          
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-bs-toggle="dropdown" aria-expanded="false">Espace Membre</a>
          <ul class="dropdown-menu" aria-labelledby="dropdown04">
              <li><a class="dropdown-item" href="<?= URL; ?>profil.php">Profil</a></li>
              <li><a class="dropdown-item" href="<?= URL; ?>connexion.php?action=deconnexion">Déconnexion</a></li>
          </ul>
        </li>

          <?php } ?>


        <?php 
        
        // menu Admin

        if(user_is_admin()){
        ?>  
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-expanded="false">Administration</a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_des_produits.php">Gestion produits</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_des_membres.php">Gestion membres</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_des_commandes.php">Gestion commandes</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_des_salles.php">Gestion Salles</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/gestion_des_avis.php">Gestion Avis</a></li>
                            <li><a class="dropdown-item" href="<?= URL; ?>admin/statistiques.php">Statistiques</a></li>

                        </ul>
                    </li>
        
        <?php
        }
        ?>

      </ul>
      <form class="d-flex" action="<?= URL; ?>">
        <input class="form-control me-2" type="search" name="rechercher" placeholder="Rechercher" aria-label="Search">
        <button class="btn btn-outline-light" type="submit">Rechercher</button>
      </form>
    </div>
  </div>
</nav>

<main class="container">