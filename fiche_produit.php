<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 
if (empty($_GET['id_produit'])) {
  header('location: index.php');
}

$infos_produit = $pdo->prepare("SELECT *  FROM produit, salle WHERE produit.id_salle = salle.id_salle AND id_produit = :id_produit ");
$infos_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$infos_produit->execute();

if ($infos_produit->rowCount() < 1) {
  header('location: index.php');
}

$produit = $infos_produit->fetch(PDO::FETCH_ASSOC);

// if($produit['date_arrive']->rowCount()>1  && $produit['date_depart']->rowCount()>1 ){
//   $produit['etat'] == 'reservation';
// } else{
//   $produit['etat'] == 'libre';
// }

//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
  <h1 class="text-white"> <?= ucfirst($produit['titre']); ?> </h1>
  <p class="lead">Bienvenue sur notre boutique.</p>
</div>


<div class="row mt-4">
  <div class="col-sm-12">
    <?= $msg; // affichage de message utilisateur 
    ?>
    <div class="row mb-3">
      <!-- avis -->
      <div class="row">
        <h2 class="col-sm-11">Découvrez, l'espace <?= $produit['titre'] ?></h2>
        <?php
        echo '<div class="col-sm-1">';
        if (user_is_connected()) {
          echo '<a href=""><button class="btn btn-outline-dark">Réserver</button></a>';
        } else {
          echo  '<a href="connexion.php"><button class="btn btn-outline-dark">Connectez-vous Pour Réserver</button></a>';
        }
        echo '</div>';
        ?>
        <hr>
      </div>
    </div>
    <div class="row">

      <div class="col-sm-8 mb-3">
        <?= '<img src="' . URL . 'assets/img_salles/' .  $produit['photo'] . '" alt="' . $produit['titre'] . '">' ?>
      </div>

      <div class="col-sm-4">
        <div class="mb-3">
          <h5>Description</h5>
          <?= '<p>' . $produit['description'] . '</p>' ?>
        </div>
        <div class="mb-3">
          <h5>Localisation</h5>
          <?= '<img src="' . URL . 'assets/img_maps/' . $produit['maps'] . '" alt="' . $produit['titre'] . '">'  ?>
        </div>
      </div>
      <div class="col-md-12 col-sm-3 mb-4">
        <div class="row">
          <h5 class="mb-3">Informations complémentaires</h5>
          <div class="col-sm-4">
            <p><i class="fa-solid fa-calendar-day"></i> Arrivée : <?= $produit['date_arrive'] ?></p>
            <p><i class="fa-solid fa-calendar-day"></i> Départ : <?= $produit['date_depart'] ?></p>
          </div>
          <div class="col-sm-4">
            <p><i class="fa-solid fa-people-group"></i> Jusqu'à <?= $produit['capacite'] ?> personne(s)</p>
            <p><i class="fa-solid fa-building"></i> Catégorie : <?= $produit['categorie'] ?></p>
          </div>
          <div class="col-sm-4">
            <p><i class="fa-solid fa-person"></i> Adresse : <?= $produit['adresse'] ?></p>
            <p><i class="fa-solid fa-euro-sign"></i> Tarif : <?= $produit['prix'] ?> €</p>
          </div>
        </div>

        <div class="col-md-12 col-sm-3 mb-4">
          <h3>Autres Produits</h3>
          <hr>
          <div class="row">
            <?php
            echo '<div class="d-flex">';
            echo '<a href="" class="mx-2"><img src="' . URL . 'assets/img_salles/' . $produit['photo'] . '"></a> ';
            echo '<a href="" class="mx-2"><img src="' . URL . 'assets/img_salles/' . $produit['photo'] . '"></a> ';
            echo '<a href="" class="mx-2"><img src="' . URL . 'assets/img_salles/' . $produit['photo'] . '"></a> ';
            echo '<a href="" class="mx-2"><img src="' . URL . 'assets/img_salles/' . $produit['photo'] . '"></a> ';
            echo '</div>';
            ?>
          </div>
        </div>
        <hr>
        <div class="row product-bot">
          <p class="col-sm-9">
            <?php
            if (user_is_connected()) {
              echo '<a href="">Déposer un commentaire et une note</a>';
            } else {
              echo  '<a href="connexion.php">Connectez-vous pour déposer un avis</button></a>';
            }

            ?>
          </p>
          <p class="col-sm-3">
            <a href="index.php">Retour vers le catalogue</a>
          </p>
        </div>
      </div>

    </div>
  </div>
</div>







<?php
include 'inc/footer.inc.php';
