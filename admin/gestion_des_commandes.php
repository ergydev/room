<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if (!user_is_admin()) {
  header('location: ../connexion.php');
  exit();
}

// DELETE ORDER 

if(isset($_GET['action']) && $_GET['action'] == 'delete'  && isset($_GET['id_commande'])){ 
  $del = $pdo->prepare("DELETE FROM commande WHERE id_commande = :id_commande");
  $del->bindParam(':id_commande', $_GET['id_commande'], PDO::PARAM_STR);
  $del->execute();
  $msg = '<div class = "alert alert-secondary mb-3">Cette commande a bien été supprimé</div>';

}


//recup de la liste des commandes 

$liste_order = $pdo->query("SELECT id_commande, commande.id_membre, produit.id_produit, date_format(commande.date_enregistrement, '%d/%m/%Y %H:%i') AS date_enregistrement, prix, titre, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_arrive, date_format(date_depart, '%d/%m/%Y %H:%i') AS date_depart , email FROM commande, produit, membre, salle WHERE produit.id_produit = commande.id_produit AND commande.id_membre = membre.id_membre");


//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Gestion des commandes </h1>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>
      </div>

      <div class="col-sm-12">
        <table class=" table table-bordered">
          <thead>
            <tr>
            <th class="text-center">ID Commande</th>
              <th class="text-center">ID Membre</th>
              <th class="text-center">ID Produit</th>
              <th class="text-center">Prix</th>
              <th class="text-center">Date d'date_enregistrement</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              while($ligne = $liste_order->fetch(PDO::FETCH_ASSOC)){
                echo '<tr>';
                echo '<td class="text-center">' . $ligne['id_commande'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_membre'] . ' - ' . $ligne['email'] . '</td>';
                echo '<td class="text-center">' . $ligne['id_produit'] . ' - ' . $ligne['titre'] . '</td>';
                echo '<td class="text-center">' . $ligne['prix'] . ' € </td>';
                echo '<td class="text-center">' . $ligne['date_enregistrement'] . '</td>';
                echo '<td class="text-center"><a href=?action=delete&id_commande=' . $ligne['id_commande'] . '" class="btn btn-outline-dark" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette commande?\'))"><i class="fa-solid fa-ban"></i></a>';
                echo '</tr>';

              }
            ?>
          </tbody>
        </table>
      </div>
  </div>







<?php
  include '../inc/footer.inc.php';