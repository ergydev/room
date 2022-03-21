<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if(!user_is_admin()){
    header('location: ../connexion.php');
    exit();
}




//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Liste des Salles </h1>
    <p class="lead text-white">Salles et Réservations</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>


      <form action="gestion_des_produits.php" method="post" class="row border p-3" enctype="multipart/form-data">
          <input type="hidden" name="id_produit" id="id_produit" value="<?= $id_produit?>" >

          <div class="col-sm-6">
              <div class="mb-3">
                    <label for="produit">Titre de la salle</label>
              </div>
          </div>
      </form>
      </div>
  </div>







<?php
  include '../inc/footer.inc.php';