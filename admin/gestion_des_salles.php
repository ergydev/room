<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

//CODE .... 

if(!user_is_admin()){
  header('location: ../connexion.php');
  exit();
}


// var 

$id_salle = "";
$titre = "";
$description = "";
$photo = "";
$pays = "";
$ville = "";
$adresse = "";
$cp = "";
$capacite = "";
$categorie = "";
$maps = "";


//----------------------------------------------------------
// ------------------------------- DELETE ROOM 
//----------------------------------------------------------





//----------------------------------------------------------
// ------------------------------- REGIST ROOM 
//----------------------------------------------------------
// controls 

if(isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['capacite']) && isset($_POST['categorie'])  && isset($_POST['maps'])){


  $titre = trim($_POST['titre']);
  $description = trim($_POST['description']);
  $pays = trim($_POST['pays']);
  $ville = trim($_POST['ville']);
  $adresse = trim($_POST['adresse']);
  $cp = trim($_POST['cp']);
  $capacite = trim($_POST['capacite']);
  $categorie = trim($_POST['categorie']);
  $maps = trim($_POST['maps']);

  $erreur = false;


  if(!empty($_POST['id_salle'])){
    $id_salle = $_POST['id_produit'];
  }
  if(!empty($_POST['photo_actuelle'])){
    $photo = $_POST['photo_actuelle'];
  }

  // verif photo
  if(!empty($_FILES['photo']['name'])){
    $tab_formats = array('png','jpg','gif','webp');
    $extension = strrchr($_FILES['photo']['name'], '.'); 
    $extension = strtolower(substr($extension, 1));
    if(in_array($extension, $tab_formats)){

      $photo = $titre . '-' . $_FILES['photo']['name'];
      $photo = preg_replace('/[^a-zA-Z0-9._-]/', '', $photo);

      $dossier_cible = ROOT_PATH . ROOT_SITE .'assets/img_produit/' . $photo;
      copy($_FILES['photo']['tmp_name'], $dossier_cible );
    } 
    else {  
      $msg .= '<div class = "alert alert-danger mb-3"> Attention,<br>la photo n\'a pas un format valide pour le web.</div>';
      $erreur = true ;
    }

  }

  if(!$erreur){
    // modif d'un produit
    if(!empty($id_salle)){
      $enregistrement = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description , photo = :photo , pays = :pays , ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie, maps = :maps WHERE id_salle = :id_salle ");
      $enregistrement->bindParam('id_salle', $id_salle, PDO::PARAM_STR);
      $_SESSION['message_utilisateur'] .= '<div class="alert alert-success mb-3">Le salle n°' . $id_salle . ' a bien été modifié.</div>';

    } else {
      $enregistrement = $pdo->prepare("INSERT INTO salle (id_salle, titre, description, photo, pays, ville, adresse, cp, capacite, categorie, maps) VALUES (NULL, :titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie, :maps)");
      $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
      $enregistrement->bindParam(':description', $description, PDO::PARAM_STR);
      $enregistrement->bindParam(':photo', $photo, PDO::PARAM_STR);
      $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
      $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
      $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
      $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
      $enregistrement->bindParam(':capacite', $capacite, PDO::PARAM_STR);
      $enregistrement->bindParam(':categorie', $categorie, PDO::PARAM_STR);
      $enregistrement->bindParam(':maps', $maps, PDO::PARAM_STR);
      $enregistrement->execute();

      // header('location: gestion_des_salles.php');
      // exit();
    }
        // Message si modification 
        if(!empty($_SESSION['message_utlisateur'])){
          $msg .= $_SESSION['message_utlisateur']; //on affiche le message
          $_SESSION['message_utlisateur'] =''; //on vide le message
        }
  }
  
}

// RECUPERATION DES PRODUITS EN BDD 
$liste_salles = $pdo->query("SELECT * FROM salle ORDER BY categorie, titre ");

//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';


// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Gestion des Salles  </h1>
    <p class="lead text-white">Ajouter ou modifier des salles</p>
  </div>


  <div class="row mt-4">
      <div class="col-sm-12">
      <?= $msg; // affichage de message utilisateur ?>

      <form action="gestion_des_salles.php" method="post" class="row border p-3" enctype="multipart/form-data">
          <input type="hidden" name="id_salle" id="id_salle" value="<?= $id_salle?>" >

          <div class="col-sm-6">
              <div class="mb-3">
                    <label for="titre">Titre</label>
                    <input type="text" class="form-control" name="titre" id="titre" placeholder="Titre de la salle" <?php if(!empty($id_salle)){echo 'readonly';} ?> value="<?= $titre ?>">
              </div>
              <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description" id="description" placeholder="Description de la salle..."  ></textarea>
              </div>


              <?php
                  if(!empty($photo_actuelle)){
                    echo '<div class="mb-3"';
                    echo '<label for"photo_actuelle">Photo actuelle</label><hr>';
                    echo 'img src="' . URL . 'assets/img/img_salles' . $photo_actuelle . '" width="100">';
                    echo 'input type="hidden" name="photo_acutelle" value"' . $photo_actuelle .'">';
                    echo '</div>';
                  }
              ?>

              <div class="mb-3">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" name="photo" id="photo">
              </div>



              <div class="mb-3">
                    <label for="capacite">Capacité</label>
                    <select name="capacite" id="capacite" class="form-control">
                      <option value="1">1 personne</option>
                      <option value="5">5 personne</option>
                      <option value="8">8 personnes</option>
                      <option value="15">15 personnes</option>
                      <option value="30">30 personnes</option>
                      <option value="100">100 personnes</option>
                      <option value="200">200 personnes</option>
                    </select>
              </div>
              <div class="mb-3">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-control">
                      <option value="<?php if($categorie == "reunion"){echo 'selected';} ?>">Réunion</option>
                      <option value="<?php if($categorie == "bureau"){echo 'selected';} ?>">Bureau</option>
                      <option value="<?php if($categorie == "formaion"){echo 'selected';} ?>">Formation</option>
                    </select>
              </div>
          </div>

          <div class="col-sm-6">
            <div class="mb-3">
              <label for="pays">Pays</label>
              <input type="text" class="form-control" name="pays" id="pays" placeholder="Indiquer un pays"  >
            </div>
            <div class="mb-3">
              <label for="ville">Ville</label>
              <input type="text" class="form-control" name="ville" id="ville" placeholder="Indiquer une ville"  >
            </div>
            <div class="mb-3">
                    <label for="adresse">Adresse</label>
                    <textarea class="form-control" name="adresse" id="adresse" placeholder="Veuilez indiquer l'adresse de la salle..."  ></textarea>
            </div>
            <div class="mb-3">
              <label for="cp">Code Postal</label>
              <input type="text" class="form-control" name="cp" id="cp" placeholder="Indiquer un Code Postal..."  >
            </div>
            <div class="mb-3">
                    <label for="maps">Localisation</label>
                    <textarea class="form-control" name="maps" id="maps"></textarea>
              </div>
            <div class="mb-3">
            <button type="submit" class="btn btn-outline-dark" id="enregistrer">Enregistrer</button>    
            </div>
          </div>
      </div>
                </form>
  </div>







<?php
  include '../inc/footer.inc.php';