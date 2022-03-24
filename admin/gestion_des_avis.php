<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if (!user_is_admin()) {
  header('location: ../connexion.php');
  exit();
}

$id_avis = "";
$id_membre = "";
$id_salle = "";
$commentaire = "";
$note = "";
$date_enregistrement = "";

//DELETE AVIS
if(isset($_GET['action']) && $_GET['action'] == 'delete'  && isset($_GET['id_avis'])){ 
  $del = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
  $del->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
  $del->execute();
  $msg = '<div class = "alert alert-secondary mb-3">Cette avis a bien été supprimé</div>';

}

// Recup liste des Avis 
$liste_avis = $pdo->query("SELECT id_avis, avis.id_membre, email, titre , salle.id_salle , commentaire, note, date_format(avis.date_enregistrement, '%d/%m/%Y %H:%i') AS date_enregistrement FROM avis, salle, membre WHERE avis.id_salle = salle.id_salle AND membre.id_membre = avis.id_membre");




//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Gestions Des Avis </h1>
    <p class="lead">Bienvenue sur notre boutique.</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>
      </div>

      <div class="col-sm-12">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">ID Avis</th>
              <th class="text-center">ID Membre</th>
              <th class="text-center">ID Salle</th>
              <th class="text-center">Commentaire</th>
              <th class="text-center">Note</th>
              <th class="text-center">Date de Publication</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              while($ligne = $liste_avis->fetch(PDO::FETCH_ASSOC)){
                if($ligne['note'] < 2 ){
                  $ligne['note'] = '<i class="fa-solid fa-star-half-stroke"></i>';
                } elseif($ligne['note'] == 3 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i>';
                } elseif($ligne['note'] == 4 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
                } elseif($ligne['note'] == 5 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
                } elseif($ligne['note'] == 6 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                } elseif($ligne['note'] == 7 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                } elseif($ligne['note'] == 8 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
                } elseif($ligne['note'] == 9 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>';
                } elseif($ligne['note'] == 10 ){
                  $ligne['note'] = '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
                }

                echo '<tr>';
                echo '<td class="text-center">' . $ligne['id_avis'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_salle'] . ' - ' . $ligne['titre'] . '</td>';
                echo '<td class="text-center">' . $ligne['commentaire'] . '</td>';
                echo '<td class="text-center">' . $ligne['note'] .'</td>';
                echo '<td class="text-center">' . $ligne['date_enregistrement'] . '</td>';
                echo '<td class="text-center"><a href=?action=delete&id_avis=' . $ligne['id_avis'] . '" class="btn btn-outline-dark" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cet avis?\'))"><i class="fa-solid fa-ban"></i></a>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
      </div>

  </div>







<?php
  include '../inc/footer.inc.php';