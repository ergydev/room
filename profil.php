<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 

if (!user_is_connected()) {
  header('location: connexion.php');
}

if ($_SESSION['membre']['civilite'] == 'm') {
  $civilite = 'Monsieur';
} else {
  $civilite = 'Madame';
}

if ($_SESSION['membre']['statut'] == 1) {
  $statut = 'Membre';
} else if ($_SESSION['membre']['statut'] == 2) {
  $statut = 'Administrateur';
}

// RECUP DES COMMANDES

$liste_order = $pdo->query("SELECT titre, photo, prix, date_format(date_arrive ,'%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart , commande.date_enregistrement FROM commande, salle, membre, produit WHERE membre.id_membre = commande.id_membre GROUP BY id_commande");



//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
  <h1 class="text-white"><i class="fa-solid fa-user"></i> Votre Compte : <?= $_SESSION['membre']['pseudo'] ?> </h1>
</div>


<div class="row mt-4">
  <div class="col-sm-12">
    <?= $msg; // affichage de message utilisateur 
    ?>
    <h3>Vos Informations personnelles</h3>

    <div class="col-sm-6 mb-3 mx-auto">
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
    <div class="col-sm-12">
      <h3>Vos dernières commandes</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center">Espace</th>
            <th class="text-center">Date de la commande</th>
            <th class="text-center">Date de réservation</th>
            <th class="text-center">Prix</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while ($ligne = $liste_order->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td class="text-center">' . $ligne['titre'] . '</td>';
            echo '<td class="text-center">' . $ligne['date_arrive'] . ' - ' . $ligne['date_depart'] . '</td>';
            echo '<td class="text-center">' . $ligne['date_enregistrement'] . '</td>';
            echo '<td class="text-center">' . $ligne['prix'] . ' €</td>';

            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>









<?php
include 'inc/footer.inc.php';
