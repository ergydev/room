<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if (!user_is_admin()) {
  header('location: ../connexion.php');
  exit();
}

// Recup infos BDD 
$stats_note = $pdo->query("SELECT ROUND(AVG(note)) AS note, titre FROM avis, salle, produit WHERE salle.id_salle = produit.id_salle ORDER BY note  ");

$stats_order = $pdo->query("SELECT titre,  COUNT(commande.id_produit) AS id_produit FROM commande, salle, produit WHERE salle.id_salle = produit.id_salle AND produit.id_produit = commande.id_produit ORDER BY id_commande   ");

$stats_member = $pdo->query("SELECT COUNT(commande.id_membre), nom, prenom, membre.id_membre FROM commande, membre WHERE commande.id_membre = membre.id_membre ORDER BY commande.id_membre ");

$stats_prix = $pdo->query("SELECT commande.id_membre, nom, prenom, membre.id_membre, ROUND(AVG(prix)) AS prix FROM commande, membre, produit WHERE commande.id_membre = membre.id_membre AND produit.id_produit = commande.id_produit ORDER BY prix");



//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
  <h1 class="text-white"> Statistiques </h1>
</div>


<div class="row mt-4">
  <div class="col-sm-12">
    <?= $msg; // affichage de message utilisateur 
    ?>
  </div>
  <div class="col-sm-12">
    <h3>Les espaces les mieux notées</h3>
    <table class="table table-bordered">
      <thead class="bg-dark text-white">
        <tr>
          <th>Nom de l'espace</th>
          <th>Note moyenne</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($ligne = $stats_note->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $ligne['titre'] . '</td>';
          echo '<td>' . $ligne['note'] . '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="col-sm-12">
    <h3>Les espaces les plus commandés</h3>
    <table class="table table-bordered">
      <thead class="bg-dark text-white">
        <tr>
          <th>Nombre de commande</th>
          <th>Nom de la salle</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($ligne = $stats_order->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $ligne['id_produit'] . '</td>';
          echo '<td>' . $ligne['titre'] . '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="col-sm-12">
    <h3>Les membres faisant le plus de commandes</h3>
    <table class="table table-bordered">
      <thead class="bg-dark text-white">
        <tr>
          <th>Nombre de commande</th>
          <th>Membre</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($ligne = $stats_member->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $ligne['id_membre'] . '</td>';
          echo '<td>' . $ligne['nom'] . $ligne['prenom'] . '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-12">
    <h3>Les membres faisant le plus de dépenses</h3>
    <table class="table table-bordered">
      <thead class="bg-dark text-white">
        <tr>
          <th>Prix en moyenne dépensés</th>
          <th>Membre</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // NE FONCTIONNE PAS
        while ($ligne = $stats_prix->fetch(PDO::FETCH_ASSOC)) {
          echo '<tr>';
          echo '<td>' . $ligne['prix'] . ' € </td>';
          echo '<td>' . $ligne['nom'] . $ligne['prenom'] . '</td>';
          echo '</tr>';
        }
        ?>
      </tbody>
    </table>
  </div>

</div>







<?php
include '../inc/footer.inc.php';
