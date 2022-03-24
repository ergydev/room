<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 
if (empty($_GET['id_produit'])) {
  header('location: index.php');
}

$infos_produit = $pdo->prepare("SELECT produit.id_produit, salle.id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart, prix, etat, titre, description, photo, pays, ville, adresse, cp, capacite, categorie, maps, ROUND(AVG(note)) AS note FROM produit, salle LEFT JOIN avis USING (id_salle) WHERE produit.id_salle = salle.id_salle AND id_produit = :id_produit GROUP BY id_produit ");
$infos_produit->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$infos_produit->execute();

if ($infos_produit->rowCount() < 1) {
  header('location: index.php');
}

// Recup info BDD
$produit = $infos_produit->fetch(PDO::FETCH_ASSOC);


// NOTES 


// Reservation 
if (isset($_GET['action']) && $_SESSION['membre']['id_membre'] && isset($_GET['id_produit']) && $_GET['action'] == 'reserver') {
  $new_etat = $pdo->prepare("UPDATE produit SET etat = 'reservation' WHERE id_produit = :id_produit");
  $new_etat->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
  $new_etat->execute();

  $new_order = $pdo->prepare("INSERT INTO commande (id_commande, id_membre, id_produit, date_enregistrement) VALUES (NULL, :id_membre, :id_produit, NOW() )");
  $new_order->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
  $new_order->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
  $new_order->execute();
    header('location : index.php');
}


// COM & RATINGS into BDD 

if (isset($_POST['commentaire']) && isset($_POST['note'])) {
  $commentaire = trim($_POST['commentaire']);
  $note = trim($_POST['note']);

  $erreur = false;

  if ($erreur == false) {
    $req = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (NULL, :id_membre, :id_salle, :commentaire, :note, NOW() )");
    $req->bindParam(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_STR);
    $req->bindParam(':id_salle', $produit['id_salle'], PDO::PARAM_STR);
    $req->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
    $req->bindParam(':note', $note, PDO::PARAM_STR);
    $req->execute();
    $msg = '<div class = "alert alert-secondary mb-3">Votre commentaire a bien été posté, merci.</div>';
  }
}

// Autres produits 
$liste_recom = $pdo->prepare("SELECT  photo, id_produit, titre, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive  FROM  produit, salle WHERE salle.id_salle = produit.id_salle AND id_produit != :id_produit LIMIT 4");
$liste_recom->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
$liste_recom->execute();




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
      <div class="row col-sm-9">
        <div class="col-sm">
          <h2 class="">Découvrez, l'espace <?= $produit['titre'] ?></h2>
        </div>
        <div class="col-sm-6">
          <?php
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
          echo '<p class="fs-5">' . $produit['note'] . '</p>';
          ?>
        </div>

      </div>
      <?php

      echo '<div class="col-sm">';

      if (user_is_connected() && $produit['etat'] == 'libre') {
        echo '<a href="?action=reserver&id_produit=' . $produit['id_produit'] . '&id_membre=' . $_SESSION['membre']['id_membre'] . ' "><button class="btn btn-outline-dark">Réserver</button></a>';
      } elseif (!user_is_connected() && $produit['etat'] == 'libre') {
        echo  '<a href="connexion.php"><button class="btn btn-outline-dark">Connectez-vous Pour Réserver</button></a>';
      } elseif (user_is_connected() && $produit['etat'] == 'reservation') {
        echo  'Cet espace est indisponible à ces dates <a href="index.php"><button class="btn btn-outline-dark my-2 mx-5">Retour à l\'accueil</button></a>';
      }
      echo '</div>';
      ?>
      <hr>
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
            // while($produit)
            echo '<div class="d-flex">';
            while ($recom = $liste_recom->fetch(PDO::FETCH_ASSOC)) {
              echo '<div class="link_recom mx-2">';
              echo '<a href="?id_produit=' . $recom['id_produit'] . '" class=""><img src="' . URL . 'assets/img_salles/' . $recom['photo'] . '"></a> ';
              echo '<h5 class="pt-2 fw-bold"><a href="?id_produit=' . $recom['id_produit'] . '" class="mx-2">' . $recom['titre'] . '</a></h5>';
              echo '<p><a href="?id_produit=' . $recom['id_produit'] . '" class="mx-2">Disponible à partir du ' . $recom['date_arrive'] . '</a></p>';
              echo '</div>';
            }
            echo '</div>';
            //exclusion pour le Select, faire une boucle pour afficher un par un les produit/photo ( WHERE id_produit !=  )
            ?>
          </div>
        </div>
        <hr>
        <div class="row product-bot">
          <p class="col-sm-9">
            <?php
            if (user_is_connected()) {
              echo '<h5>Déposer un commentaire et une note</h5>';
              echo '<div class="col-sm-12">';
              echo '<form class="row border p-3" method="post" action"#">';

              echo '<div class="mb-3">';
              echo '<label for="commentaire" class="form-label">Ecrivez un commentaire</label>';
              echo '<textarea class="form-control" id="commentaire" name="commentaire"></textarea>';
              echo '</div>';

              echo '<div class="mb-3">';
              echo '<label for="note" class="form-label">Comment avez vous trouvez vôtre séjour?</label>';
              echo '<select class="form-select" id="note" name="note">';
              echo '<option value="1">1</option>';
              echo '<option value="2">2</option>';
              echo '<option value="3">3</option>';
              echo '<option value="4">4</option>';
              echo '<option value="5">5</option>';
              echo '<option value="6">6</option>';
              echo '<option value="7">7</option>';
              echo '<option value="8">8</option>';
              echo '<option value="9">9</option>';
              echo '<option value="10">10</option>';
              echo '</select>';
              echo '</div>';

              echo '<div class="mb-3">';
              echo '<button type="submit" class="btn btn-outline-dark">Envoyer</button>';
              echo '</div>';


              echo '</form>';
              echo '</div>';
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
