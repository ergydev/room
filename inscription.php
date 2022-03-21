<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

//CODE .... 
if(user_is_connected()){
    header('location: profil.php');
  }

$pseudo = "";
$nom = "";
$prenom = "";
$email = "";
$civilite = "";

//----------------------------
// Controles 
// ----------------------------

if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['civilite'])){
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $civilite = trim($_POST['civilite']);



    $erreur= 'non';

    // controle pseudo
    if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14 ){
        $msg .= '<div class = "alert alert-danger mb-3"> Le Pseudo doit contenir entre 4 et 14 caractères. </div>';
        $erreur = 'oui';
    }
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
    if($verif_caractere === false){
        $msg .= '<div class = "alert alert-danger mb-3"> Attention, Les caratères autorisés pour le pseudo sont : a-z 0-9 _ . - </div>';
        $erreur = 'oui';
    }
    // dispo du pseudo
    $verif_pseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verif_pseudo->bindParam('pseudo', $pseudo , PDO::PARAM_STR);
    $verif_pseudo->execute();
    if($verif_pseudo->rowCount()> 0){
        $msg .= '<div class = "alert alert-danger mb-3">Ce pseudo est déjà pris.</div>';
        $erreur = 'oui';
    }

    // controle mdp
    if(empty($mdp)){
        $msg .= '<div class = "alert alert-danger mb-3"> Le mot de passe est obligatoire. </div>';
        $erreur = 'oui';
    }

    // controle mail 
    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false ){
        $msg .= '<div class = "alert alert-danger mb-3"> Le format du mail n\'est pas correct. </div>';
        $erreur = 'oui';
    }


    // Save in bdd ----------------------------------------------
    if ($erreur == 'non') {
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);

        $req = $pdo->prepare("INSERT INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (NULL, :pseudo, :mdp, :nom, :prenom, :email, :civilite,1, NOW() ) ");
        $req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':email', $email, PDO::PARAM_STR);
        $req->bindParam(':civilite', $civilite, PDO::PARAM_STR);
        $req->execute();

        header('location: connexion.php');


        
    }
}



//-------------------- DEBUT DES AFFICHAGES
include 'inc/header.inc.php';
include 'inc/nav.inc.php';




?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Inscription </h1>
    <p class="lead text-white">Créer votre compte en quelques minutes et réserver la salle qu'il vous faut.</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>

      </div>
  </div>

<form action="" method="post" class="border p-5" >
    <div class="row">
        <div class="col-sm-6">
                <div class="mb-4">
                    <label for="pseudo" class="label-control">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" class="form-control" value="<?= $pseudo;?>" > 
                </div>
                <div class="mb-4">
                    <label for="mdp" class="label-control">Mot de Passe</label>
                    <input type="text" name="mdp" id="mdp" class="form-control" value="" > 
                </div>
                <div class="mb-4">
                    <label for="nom" class="label-control">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= $nom;?>" > 
        </div>

            </div>
        <div class="col-sm-6">
                <div class="mb-4">
                    <label for="prenom" class="label-control">Prenom</label>
                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= $prenom;?>" > 
                </div>
                <div class="mb-4">
                    <label for="email" class="label-control">Votre adresse email</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?= $email;?>" > 
                </div>
                <div class="mb-4">
                            <label for="civilite" class="form-label">Civilité</label>
                            <select name="civilite" class="form-select" id="civilite">
                                <option value="m">Monsieur</option>
                                <option value="f" <?php if($civilite == "f") { echo 'selected';} ?> >Madame</option>
                            </select>
                </div>  
        </div> 
        <button type="submit" class="btn btn-outline-dark" id="inscription">S'inscrire</button>    
    </div>
</form>






<?php
  include 'inc/footer.inc.php';