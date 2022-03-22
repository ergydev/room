<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if(!user_is_admin()){
  header('location: ../connexion.php');
  exit();
}


// Recup liste des membres
$liste_membre = $pdo->query("SELECT * FROM membre");





//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Gestion Des Membres </h1>
    <p class="lead text-white">Liste des membres</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>

      </div>
      <div class="col-12">
        <table class="table table-bordered">
          <thead class="bg-dark text-white text-center">
            <tr>
              <td>ID Membre</td>
              <td>Pseudo</td>
              <td>Nom</td>
              <td>Prenom</td>
              <td>Email</td>
              <td>Civilité</td>
              <td>Statut</td>
              <td>Date d'enregistrement</td>
              <td>Actions</td>
            </tr>
          </thead>
          <tbody>
            <?php
            while ($ligne = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
              if($ligne['civilite'] == 'm'){
                $civilite = 'Homme';
              } elseif ($ligne['civilite'] == 'f'){
                $civilite = 'Femme';
              }

              if($ligne['statut'] == 1 ){
                $statut = 'membre';
              } elseif ($ligne['statut'] == 2 ){
                $statut = 'administrateur';
              }
              echo '<tr>';
              echo '<td>' . $ligne['id_membre'] . '</td>';
              echo '<td>' . $ligne['pseudo'] . '</td>';
              echo '<td>' . $ligne['nom'] . '</td>';
              echo '<td>' . $ligne['prenom'] . '</td>';
              echo '<td>' . $ligne['email'] . '</td>';
              echo '<td>' . $civilite . '</td>';
              echo '<td>' . $statut . '</td>';
              echo '<td>' . $ligne['date_enregistrement'] . '</td>';
              echo '<td> <a href="?action=membre&statut=' . $ligne['statut'] .'" class="btn btn-danger btn-sm">Passer Membre <i class="fa-solid fa-square-minus"></i></a> <a href="?action=admin&statut=' . $ligne['statut'] . '" class="btn btn-primary btn-sm">Passer Admin <i class="fa-solid fa-angle-up"></i></a> </td>';
              echo '</tr>';
            }

            ?>
          </tbody>
        </table>
      </div>
  </div>







<?php
  include '../inc/footer.inc.php';