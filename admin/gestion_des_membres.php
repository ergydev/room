<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if (!user_is_admin()) {
  header('location: ../connexion.php');
  exit();
}


// Recup liste des membres
$liste_membre = $pdo->query("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, date_format(date_enregistrement, '%d/%m/%Y %H:%i:%s') AS date_enr FROM membre ORDER BY statut, id_membre");

// ------------------------

if(isset($_GET['action']) && isset($_GET['statut']) && isset($_GET['action']) == 'membre'){
  $new_statut = $pdo->prepare("UPDATE membre SET statut = 1 WHERE id_membre = :id_membre");
  $new_statut->bindParam(':id_membre' , $_GET['id_membre'], PDO::PARAM_STR);
  $new_statut->execute();
}

if(isset($_GET['action']) && isset($_GET['statut']) && isset($_GET['action']) == 'admin'){
  $new_statut = $pdo->prepare("UPDATE membre SET statut = 2 WHERE id_membre = :id_membre");
  $new_statut->bindParam(':id_membre' , $_GET['id_membre'], PDO::PARAM_STR);
  $new_statut->execute();
}


// -----------------------
// Delete account 
//------------------------
if (isset($_GET['action']) && isset($_GET['id_membre']) && $_GET['action'] == 'delete'){
  $del = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
  $del->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
  $del->execute();
  $msg = '<div class = "alert alert-danger mb-3">Ce compte a bien été supprimé</div>';

}


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
    <?= $msg; // affichage de message utilisateur 
    ?>

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
          if ($ligne['civilite'] == 'm') {
            $civilite = 'Homme';
          } elseif ($ligne['civilite'] == 'f') {
            $civilite = 'Femme';
          }

          if ($ligne['statut'] == 1) {
            $statut = 'membre';
          } elseif ($ligne['statut'] == 2) {
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
          echo '<td>' . $ligne['date_enr'] . '</td>';
          echo '<td> <a href="?action=membre&statut=' . $ligne['statut'] . '" class="btn btn-danger btn-sm">Passer Membre <i class="fa-solid fa-square-minus"></i></a>  <a href="?action=admin&statut=' . $ligne['statut'] . '" class="btn btn-primary btn-sm">Passer Admin <i class="fa-solid fa-angle-up"></i></a> <a href="?action=delete&id_membre=' . $ligne['id_membre'] . '"class=" btn btn-outline-dark btn-sm" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer ce membre?\'))"> Supprimer <i class="fa-solid fa-ban"></i></a> </td>';
          echo '</tr>';
        }

        ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-12">
    <form action="" method="POST" class="row border p-3">
      <input type="hidden" name="id_membre" id="id_membre" value="<?= $id_membre ?>">

      <div class="col-sm-6">
        <div class="col-mb-3">
          <label for="Pseudo" class="form-label mt-2">Pseudo</label>
          <input type="text" class="form-control" name="pseudo" id="pseudo" placeholder="Pseudo">
        </div>
        <div class="col-mb-3">
          <label for="mdp" class="form-label mt-2">Mot de passe</label>
          <input type="text" class="form-control" name="mdp" id="mdp" placeholder="Mot de passe">
        </div>
        <div class="col-mb-3">
          <label for="prenom" class="form-label mt-2">Prénom</label>
          <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Prénom">
        </div>
      </div>

      <div class="col-sm-6">
        <div class="col-mb-3">
          <label for="email" class="form-label mt-2">Email</label>
          <input type="text" class="form-control" name="email" id="email" placeholder="Adresse Mail">
        </div>
        <div class="col-mb-3">
          <label for="civilite" class="form-label mt-2">Civilité</label>
          <select name="civilite" class="form-select" id="civilite">
            <option value="<?php $civilite = 'm' ?> ">Homme</option>
            <option value="<?php $civilite = 'f' ?> ">Femme</option>
          </select>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-outline-dark" id="enregistrer">Enregistrer</button>
        </div>


      </div>

    </form>



  </div>
</div>







<?php
include '../inc/footer.inc.php';
