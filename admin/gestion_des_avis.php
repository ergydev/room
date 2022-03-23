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
                echo '<tr>';
                echo '<td class="text-center">' . $ligne['id_avis'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_salle'] . ' - ' . $ligne['titre'] . '</td>';
                echo '<td class="text-center">' . $ligne['commentaire'] . '</td>';
                echo '<td class="text-center">' . $ligne['note'] . '/10' .'</td>';
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