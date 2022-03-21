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

if(isset($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id_salle']) ){
  $del_photo = $pdo->prepare("SELECT * FROM salle where id_salle = :id_salle");
  $del_photo->bindParam('id_salle', $_GET['id_salle'], PDO::PARAM_STR);
  $del_photo->execute();

  if($del_photo->rowCount()>0){
    $infos = $del_photo->fetch();
    $chemin_photo = ROOT_PATH . ROOT_SITE . 'assets/img_salles' . $infos['photo'];
    $chemin_maps = ROOT_PATH . ROOT_SITE . 'assets/img_maps' . $infos['maps'];
    if(!empty($infos['photo']) && !empty($infos['maps']) && file_exists($chemin_maps) && file_exists($chemin_photo)){
      unlink($chemin_photo);
      unlink($chemin_maps);
    }
  }

  $supression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
  $supression->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
  $supression->execute();
  $msg = '<div class = "alert alert-danger mb-3">La salle a bien été supprimée</div>';
}





//----------------------------------------------------------
// ------------------------------- REGIST ROOM 
//----------------------------------------------------------
// controls 

if(isset($_POST['titre']) && isset($_POST['description']) && isset($_POST['pays']) && isset($_POST['ville']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['capacite']) && isset($_POST['categorie'])){


  $titre = trim($_POST['titre']);
  $description = trim($_POST['description']);
  $pays = trim($_POST['pays']);
  $ville = trim($_POST['ville']);
  $adresse = trim($_POST['adresse']);
  $cp = trim($_POST['cp']);
  $capacite = trim($_POST['capacite']);
  $categorie = trim($_POST['categorie']);


  $erreur = false;


  if(!empty($_POST['id_salle'])){
    $id_salle = $_POST['id_produit'];
  }
  if(!empty($_POST['photo_actuelle'])){
    $photo = $_POST['photo_actuelle'];
  }
  if(!empty($_POST['maps_actuelle'])){
    $maps = $_POST['maps_actuelle'];
  }

  // verif photo
  if(!empty($_FILES['photo']['name'])){
    $tab_formats = array('png','jpg','gif','webp');
    $extension = strrchr($_FILES['photo']['name'], '.'); 
    $extension = strtolower(substr($extension, 1));
    if(in_array($extension, $tab_formats)){

      $photo = $titre . '-' . $_FILES['photo']['name'];
      $photo = preg_replace('/[^a-zA-Z0-9._-]/', '', $photo);

      $dossier_cible = ROOT_PATH . ROOT_SITE .'assets/img_salles/' . $photo;
      copy($_FILES['photo']['tmp_name'], $dossier_cible );
    } 
    else {  
      $msg .= '<div class = "alert alert-danger mb-3"> Attention,<br>la photo n\'a pas un format valide pour le web.</div>';
      $erreur = true ;
    }

  }

  // verif maps
  if(!empty($_FILES['maps']['name'])){
    $tab_formats2 = array('png','jpg','gif','webp');
    $extension = strrchr($_FILES['maps']['name'], '.'); 
    $extension = strtolower(substr($extension, 1));
    if(in_array($extension, $tab_formats2)){

      $maps = $titre . '-' . $_FILES['maps']['name'];
      $maps = preg_replace('/[^a-zA-Z0-9._-]/', '', $maps);

      $dossier_cible2 = ROOT_PATH . ROOT_SITE .'assets/img_maps/' . $maps;
      copy($_FILES['maps']['tmp_name'], $dossier_cible2 );
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


echo '<pre>';
print_r($_POST);
echo '</pre>';

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
                      <option value="<?php if($categorie == 'reunion'){echo 'selected';} ?>">Réunion</option>
                      <option value="<?php if($categorie == 'bureau'){echo 'selected';} ?>">Bureau</option>
                      <option value="<?php if($categorie == 'formation'){echo 'selected';} ?>">Formation</option>
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

            <?php
                  if(!empty($maps_actuelle)){
                    echo '<div class="mb-3"';
                    echo '<label for"maps_actuelle">maps actuelle</label><hr>';
                    echo 'img src="' . URL . 'assets/img/img_maps' . $maps_actuelle . '" width="100">';
                    echo 'input type="hidden" name="maps_acutelle" value"' . $maps_actuelle .'">';
                    echo '</div>';
                  }
              ?>

            <div class="mb-3">
                    <label for="maps">Localisation</label>
                    <input type="file" id="maps" name="maps" class="form-control">
              </div>
            <div class="mb-3">
            <button type="submit" class="btn btn-outline-dark" id="enregistrer">Enregistrer</button>    
            </div>
          </div>
      </div>
                </form>

      <div class="row mt-4">
        <div class="col-12">
          <table class="table table-bordered">
            <thead class="bg-dark text-white text-center">
              <tr>
                <th>Id Salle</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Photo</th>
                <th>Capacité</th>
                <th>Catégorie</th>
                <th>Pays</th>
                <th>Ville</th>
                <th>Adresse</th>
                <th>Code Postal</th>
                <th>Localisation</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                while($ligne = $liste_salles->fetch(PDO::FETCH_ASSOC)){
                  echo '<tr>';
                  echo '<td>' . $ligne['id_salle'] . '</td>';
                  echo '<td>' . $ligne['titre'] . '</td>';
                  echo '<td>' . substr($ligne['description'], 0, 30) . '</td>';
                  echo '<td><img src="' . URL . 'assets/img_salles/'. $ligne['photo'] . '" width="100" </td>';
                  echo '<td>' . $ligne['capacite'] . '</td>';
                  echo '<td>' . $ligne['categorie'] . '</td>';
                  echo '<td>' . $ligne['pays'] . '</td>';
                  echo '<td>' . $ligne['ville'] . '</td>';
                  echo '<td>' . $ligne['adresse'] . '</td>';
                  echo '<td>' . $ligne['cp'] . '</td>';
                  echo '<td><img src="' . URL . 'assets/img_maps/' . $ligne['maps'] . '" width="100" </td>';

                  echo '<td class="mx-3"> <a href="?action=edit&id_salle=' . $ligne['id_salle'] . '"class="btn btn-outline-dark"> <i class="fa-solid fa-pen-to-square"></i></a> <a href="?action=delete&id_salle=' . $ligne['id_salle'] . '"class="btn btn-outline-dark" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette salle?\'))"> <i class="fa-solid fa-ban"></i></a> </td>';
                  
                  echo '</tr>';
                }
              
              
              
              ?>
            </tbody>
          </table>
        </div>
      </div>
  </div>







<?php
  include '../inc/footer.inc.php';