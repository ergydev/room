<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 




//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Template </h1>
    <p class="lead">Bienvenue sur notre boutique.</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>

      </div>
  </div>







<?php
  include 'inc/footer.inc.php';