<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 

if(!user_is_connected()){
  header('location: connexion.php');
}

if($_SESSION['membre']['civilite'] == 'm'){
   $civilite = 'Monsieur';
}else {
  $civilite = 'Madame';
}

if($_SESSION['membre']['statut'] == 1){
  $statut = 'Membre';
} else if ($_SESSION['membre']['statut'] == 2 ){
  $statut = 'Administrateur';
}




//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"><i class="fa-solid fa-user"></i> Votre Compte : <?= $_SESSION['membre']['pseudo'] ?> </h1>
    <p class="lead text-white">Vos informations personnelles :</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>

      <div class="col-sm-6  mx-auto">
        <ul class="list-group">
          <li class="list-group-item"><strong>Votre Numéro Client :</strong> <?= $_SESSION['membre']['id_membre'] ?></li>
          <li class="list-group-item"><strong>Pseudo : </strong> <?= $_SESSION['membre']['pseudo'] ?></li>
          <li class="list-group-item"><strong>Civilité :</strong> <?= $civilite ?></li>
          <li class="list-group-item"><strong>Nom :</strong> <?= $_SESSION['membre']['nom'] ?></li>
          <li class="list-group-item"><strong>Prénom :</strong> <?= $_SESSION['membre']['prenom'] ?></li>
          <li class="list-group-item"><strong>Adresse Mail :</strong> <?= $_SESSION['membre']['email'] ?></li>
          <li class="list-group-item"><strong>Statut :</strong> <?= $statut ?></li>
        </ul>
      </div>
      </div>
  </div>









<?php
  include 'inc/footer.inc.php';