<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 

// Recup des catégories
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY categorie");

$liste_ville = $pdo->query("SELECT DISTINCT ville FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY ville");

$liste_capacite = $pdo->query("SELECT DISTINCT capacite FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY capacite");


// Recup des produits 

if(isset($_GET['categorie'])){
  $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND categorie = :categorie ORDER BY categorie ");
  $liste_produits->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['ville'])){
  $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND ville = :ville ORDER BY ville");
  $liste_produits->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['capacite'])){
  $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND capacite = :capacite ORDER BY capacite");
  $liste_produits->bindParam(':capacite', $_GET['capacite'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['rechercher'])){
  $liste_produits = $pdo->prepare("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle AND (titre LIKE :rechercher OR description LIKE :rechercher) ORDER BY categorie, titre");
  $rechercher = '%' . $_GET['rechercher'] . '%';
  $liste_produits->bindParam(':rechercher', $_GET['rechercher'], PDO::PARAM_STR);
  $liste_produits->execute();
} else{
  $liste_produits = $pdo->query("SELECT * FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY categorie, titre");
}


//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Nos Espaces </h1>
    <p class="lead text-white">Pour vos réunions, formations et Coworking.</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>
      
      </div>
      <div class="row">
        <div class="col-sm-3">
          <?php

              if(!empty($_GET)){
                echo '<a href="index.php" class="btn btn-outline-dark w-100"> Annuler les filtres</a><hr>';
              }
          ?>

          <h3 class="pb-3 mt-3 border-bottom">Catégories</h3>
          <ul class="list-group filtres">
            <?php
              while($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)){
                echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a></li>';
              }
            ?>
          </ul>

          <h3 class="pb-3 mt-3 border-bottom">Villes</h3>
          <ul class="list-group filtres">
            <?php
              while($ville = $liste_ville->fetch(PDO::FETCH_ASSOC)){
                echo '<li class="list-group-item"><a href="?ville=' . $ville['ville'] . '">' . $ville['ville'] . '</a></li>';
              }
            ?>
          </ul>

          <h3 class="pb-3 mt-3 border-bottom">Capacités</h3>
          <ul class="list-group filtres">
            <?php
              while($capacite = $liste_capacite->fetch(PDO::FETCH_ASSOC)){
                echo '<li class="list-group-item"><a href="?capacite=' . $capacite['capacite'] . '">' . $capacite['capacite'] . '</a></li>';
              }
            ?>
          </ul>

        </div>
      </div>
  </div>







<?php
  include 'inc/footer.inc.php';