<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 

$pseudo = "";

// log out user 
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){
  session_destroy();
}

//redirection
if(user_is_connected()){
  header('location: profil.php');
}

if(isset($_POST['pseudo']) && isset($_POST['mdp'])){
  $pseudo = trim($_POST['pseudo']);
  $mdp = trim($_POST['mdp']);

  $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $connexion->bindParam('pseudo', $pseudo, PDO::PARAM_STR);
  $connexion->execute();

  if($connexion->rowCount()<1 ){
    $msg .= '<div class = "alert alert-danger mb-3">Le pseudo et/ou le mode passe est incorrect. </div>';

  } else {
    $infos = $connexion->fetch(PDO::FETCH_ASSOC);
    if(password_verify($mdp, $infos['mdp'])){
      $_SESSION['membre'] = array();
      $_SESSION['membre']['id_membre'] = $infos ['id_membre'];
      $_SESSION['membre']['pseudo'] = $infos ['pseudo'];
      $_SESSION['membre']['nom'] = $infos ['nom'];
      $_SESSION['membre']['prenom'] = $infos ['prenom'];
      $_SESSION['membre']['email'] = $infos ['email'];
      $_SESSION['membre']['civilite'] = $infos ['civilite'];
      $_SESSION['membre']['statut'] = $infos ['statut'];

      header('location: profil.php');
    } else{
      $msg .= '<div class = "alert alert-danger mb-3">Le pseudo et/ou le mode passe est incorrect. </div>';

    }
  }
}


//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
  <h1 class="text-white"> Connexion </h1>
  <p class="lead">Connectez-vous pour pouvoir réserver votre salle.</p>
</div>


<div class="row mt-4">
  <div class="col-sm-12">
    <?= $msg; // affichage de message utilisateur 
    ?>

  </div>
</div>

<div class="row">

  <div class="col-sm-4 mx-auto">
    <form action="" method="post" class="border  p-5">
      <div class="mb-4">
        <label for="pseudo" class="label-control">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $pseudo; ?>">
      </div>
      <div class="mb-4">
        <label for="mdp" class="label-control">Mot de Passe</label>
        <input type="text" name="mdp" id="mdp" class="form-control" value="">
      </div>
      <div class="mb-4">
        <button class="btn btn-outline-dark" id="connexion">Connexion</button>
      </div>
    </form>
  </div>
</div>






<?php
include 'inc/footer.inc.php';
