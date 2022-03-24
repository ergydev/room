<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 

// Recup des catégories
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY categorie");

$liste_ville = $pdo->query("SELECT DISTINCT ville FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY ville");
$liste_pays = $pdo->query("SELECT DISTINCT pays FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY pays");

$liste_capacite = $pdo->query("SELECT DISTINCT capacite FROM produit, salle WHERE salle.id_salle = produit.id_salle ORDER BY capacite");

$date = date('%d/%m/%Y %H:%i');

// Recup des produits 

if (isset($_GET['categorie'])) {
  $liste_produits = $pdo->prepare("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, salle.titre, description, photo, pays, ville, adresse, cp, capacite, salle.categorie, maps,  ROUND(AVG(note)) AS note  FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE salle.id_salle = produit.id_salle AND categorie = :categorie GROUP BY id_produit ORDER BY categorie ");
  $liste_produits->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['ville'])) {
  $liste_produits = $pdo->prepare("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, salle.titre, description, photo, pays, ville, adresse, cp, capacite, salle.categorie, maps,  ROUND(AVG(note)) AS note  FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE salle.id_salle = produit.id_salle AND ville = :ville GROUP BY id_produit ORDER BY ville");
  $liste_produits->bindParam(':ville', $_GET['ville'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['capacite'])) {
  $liste_produits = $pdo->prepare("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, salle.titre, description, photo, pays, ville, adresse, cp, capacite, salle.categorie, maps,  ROUND(AVG(note)) AS note  FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE salle.id_salle = produit.id_salle AND capacite = :capacite GROUP BY id_produit ORDER BY capacite");
  $liste_produits->bindParam(':capacite', $_GET['capacite'], PDO::PARAM_STR);
  $liste_produits->execute();
} elseif (isset($_GET['rechercher'])) {
  $liste_produits = $pdo->prepare("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, salle.titre, description, photo, pays, ville, adresse, cp, capacite, salle.categorie, maps,  ROUND(AVG(note)) AS note  FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE salle.id_salle = produit.id_salle AND (titre LIKE :rechercher OR description LIKE :rechercher) GROUP BY id_produit ORDER BY categorie, titre");
  $rechercher = '%' . $_GET['rechercher'] . '%';
  $liste_produits->bindParam(':rechercher', $_GET['rechercher'], PDO::PARAM_STR);
  $liste_produits->execute();
} else {
  $liste_produits = $pdo->query("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, salle.titre, description, photo, pays, ville, adresse, cp, capacite, salle.categorie, maps, ROUND(AVG(note)) AS note FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE salle.id_salle = produit.id_salle AND date_arrive >= CURDATE()  GROUP BY id_produit ORDER BY categorie, titre");
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
    <?= $msg; // affichage de message utilisateur 
    ?>

  </div>
  <div class="row">
    <div class="col-sm-2">
      <?php

      if (!empty($_GET)) {
        echo '<a href="index.php" class="btn btn-outline-dark w-100"> Annuler les filtres</a><hr>';
      }
      ?>

      <h3 class="pb-3 mt-3 border-bottom">Catégories</h3>
      <ul class="list-group filtres">
        <?php
        while ($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
          echo '<li class="list-group-item"><a href="?categorie=' . $categorie['categorie'] . '">' . $categorie['categorie'] . '</a></li>';
        }
        ?>
      </ul>

      <h3 class="pb-3 mt-3 border-bottom">Pays</h3>
      <ul class="list-group filtres">
        <?php
        while ($pays = $liste_pays->fetch(PDO::FETCH_ASSOC)) {
          echo '<li class="list-group-item"><a href="?pays=' . $pays['pays'] . '">' . $pays['pays'] . '</a></li>';
        }
        ?>
      </ul>

      <h3 class="pb-3 mt-3 border-bottom">Villes</h3>
      <ul class="list-group filtres">
        <?php
        while ($ville = $liste_ville->fetch(PDO::FETCH_ASSOC)) {
          echo '<li class="list-group-item"><a href="?ville=' . $ville['ville'] . '">' . $ville['ville'] . '</a></li>';
        }
        ?>
      </ul>

      <h3 class="pb-3 mt-3 border-bottom">Capacités</h3>
      <ul class="list-group filtres">
        <?php
        while ($capacite = $liste_capacite->fetch(PDO::FETCH_ASSOC)) {
          echo '<li class="list-group-item"><a href="?capacite=' . $capacite['capacite'] . '">' . $capacite['capacite'] . '</a></li>';
        }
        ?>
      </ul>

    </div>
    <div class="col-sm-10">
      <h3 class="pb-3 border-bottom">Découvrez nos espaces</h3>
      <div class="row">
        <?php



        if ($liste_produits->rowCount() > 0) {
          while ($produit = $liste_produits->fetch(PDO::FETCH_ASSOC)) {

            if ($produit['etat'] == 'reservation') {
              $produit['etat'] = 'Réservé(e)';
            } else {
              $produit['etat'] = 'Disponible';
            }


            //NOTE 
            if (empty($produit['note'])) {
              $produit['note'] == 'hidden';
            } elseif ($produit['note'] < 2) {
              $produit['note'] = '<i class="fa-solid fa-star-half-stroke"></i>';
            } elseif ($produit['note'] == 3) {
              $produit['note'] = '<i class="fa-solid fa-star"></i>';
            } elseif ($produit['note'] == 4) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
            } elseif ($produit['note'] == 5) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
            } elseif ($produit['note'] == 6) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
            } elseif ($produit['note'] == 7) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
            } elseif ($produit['note'] == 8) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
            } elseif ($produit['note'] == 9) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
            } elseif ($produit['note'] == 10) {
              $produit['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
            }
            echo '<div class ="col-lg-3 col-md-4 col-sm-6 mb-3">';
            echo '<div class="card">
                  <img src="' . URL . 'assets/img_salles/' . $produit['photo'] . '" class="card-img-top" alt="Image produi : ' . $produit['titre'] . '" > <div class="card-body">
                  <h5 class="card-title">' . $produit['titre'] . '</h5>
                  <p class="card-text"> ' . substr($produit['description'], 0, 30) . '...</p>
                  <p class="fw-bold fs-5">Prix : ' . $produit['prix'] . ' €</p>
                  <p class="">' . $produit['note'] . '</p>
                  <p class="card-text"><span class="fw-bold">Jusqu\'à ' . $produit['capacite'] . ' personne(s)</span></p>
                  <p class="card-text"><span class="fw-bold">Actuellement :</span> ' . $produit['etat'] . '</p>
                  <p cmass="card-text"><span class="fw-bold">Réservez du :</span> ' . $produit['date_arrive'] . ' <br> <span class="fw-bold">au</span> ' . $produit['date_depart'] . '</p>
                  <a href="fiche_produit.php.?id_produit=' . $produit['id_produit'] . '" class="btn btn-outline-dark w-100">Découvrir</a>
                  </div>
                  </div>';
            echo '</div>';
          }
        } else {
          echo '<div class="col-12 text-center text-green mt-3"><h3>Auncun résultat ne correspond à votre recherche.</h3></div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>







<?php
include 'inc/footer.inc.php';
