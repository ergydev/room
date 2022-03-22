<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


//CODE .... 

if (!user_is_admin()) {
    header('location: ../connexion.php');
    exit();
}

$id_produit = "";
$id_salle = "";
$date_arrive = "";
$date_depart = "";
$prix = "";



$date = date('Y-m-d');
// var_dump($date);


//------------------------
// ----- DELETE PRODUCT 
//------------------------

if(isset($_GET['action']) && isset($_GET['action']) == 'delete' && !empty($_GET['id_produit'])){
    $del = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $del->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $del->execute();

    if($del->rowCount()>0){
        $infos = $del->fetch();
        $chemin_photo = ROOT_PATH . ROOT_SITE . 'assets/img_salles' . $infos['photo'];
        if(!empty($chemin_photo) && file_exists($chemin_photo)){
            unlink($chemin_photo);
        }
    }

    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
    $suppression->execute();
    $msg = '<div class = "alert alert-danger mb-3">Le produit a bien été supprimé</div>';
}


//------------------------
// ----- EDIT PRODUCT 
//------------------------

if (isset($_GET['action']) && isset($_GET['action']) == 'edit' && !empty($_GET['id_produit'])){
    $edit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $edit->bindParam(':id_produit' , $_GET['id_produit'], PDO::PARAM_STR);
    $edit->execute();

    if($edit->rowCount()>0){
        $edit_info = $edit->fetch(PDO::FETCH_ASSOC);
        $id_produit = $edit_info['id_produit'];
        $id_salle = $edit_info['id_salle'];
        $date_arrive = $edit_info['date_arrive'];
        $date_depart = $edit_info['date_depart'];
        $prix = $prix['id_produit'];
    }
}


// Récupération de la liste des salles
$liste_salle = $pdo->query("SELECT * FROM salle");

//--------------------------
// ----- SAVE PRODUCT ------
//--------------------------

if (isset($_POST['date_arrive']) && isset($_POST['date_depart']) && isset($_POST['id_salle']) && isset($_POST['prix'])) {
    $date_arrive = trim($_POST['date_arrive']);
    $date_depart = trim($_POST['date_depart']);
    $id_salle = trim($_POST['id_salle']);
    $prix = trim($_POST['prix']);

    $erreur = false;

    if (!empty($prix) && !is_numeric($prix)) {
        $erreur = true;
        $msg .= '<div class = "alert alert-danger mb-3">Merci de mettre un prix en chiffres.</div>';
    } else {
        $erreur = false;
    }

    if (empty($date_arrive) && (empty($date_depart))) {
        $erreur = true;
        $msg .= '<div class = "alert alert-danger mb-3">Merci de choisir des dates valides.</div>';
    } elseif (!empty($date_arrive) && !empty($date_depart) && $date_depart < $date_arrive) {
        $erreur = true;
        $msg .= '<div class = "alert alert-danger mb-3">Les dates ne sont pas valides</div>';
    } elseif ($date_arrive < $date) {
        $erreur = true;
        $msg .= '<div class = "alert alert-danger mb-3">Merci de choisir des dates valides.</div>';
    } else {
        $erreur = false;
    }

    // verif dispo 
    // $verif = $pdo->prepare("SELECT * FROM produit WHERE date_arrive = :date_arrive");
    // $verif->bindParam(':date_arrive', $date_arrive, PDO::PARAM_STR);
    // $verif->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
    // $verif->execute();
    // if($verif->rowCount()> 0 ){
    //     $erreur = false;
    //     $msg .= '<div class = "alert alert-danger mb-3">La date choisie est indisponnible.</div>';
    //     $etat = 'libre';
    // }



    if ($erreur == false) {
        $register = $pdo->prepare("INSERT INTO produit (id_produit, id_salle, date_arrive, date_depart, prix, etat ) VALUES (NULL, :id_salle, :date_arrive, :date_depart, :prix, 'libre' )");
        $register->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
        $register->bindParam(':date_arrive', $date_arrive, PDO::PARAM_STR);
        $register->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
        $register->bindParam(':prix', $prix, PDO::PARAM_STR);
        $register->execute();
    }
}


// RECUP DES INFOS EN BDD 
$infos_produit = $pdo->query("SELECT id_produit, id_salle, date_format(date_arrive, '%d/%m/%Y %H:%i') AS date_ar , date_format(date_depart, '%d/%m/%Y %H:%i') AS date_dp, prix, etat FROM produit ORDER BY id_produit");

// echo '<pre>';
// print_r($_GET);
// echo '</pre>';

//-------------------- DEBUT DES AFFICHAGES
include '../inc/header.inc.php';
include '../inc/nav.inc.php';


?>


<div class="bg-dark p-5 rounded text-center">
    <h1 class="text-white"> Liste des Salles </h1>
    <p class="lead text-white">Salles et Réservations</p>
</div>


<div class="row mt-4">
    <div class="col-sm-12">
        <?= $msg; // affichage de message utilisateur 
        ?>


        <form action="gestion_des_produits.php" method="post" class="row border p-3" enctype="multipart/form-data">
            <input type="hidden" name="id_produit" id="id_produit" value="<?= $id_produit ?>">

            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="date_arrive" class="form-label">Date d'arrivée</label>
                    <input type="datetime-local" id="date_arrive" name="date_arrive" value="<?= date("Y-m-d H:i") ?>" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="date_depart" class="form-label">Date de départ</label>
                    <input type="datetime-local" id="date_depart" name="date_depart" value="<?= date("Y-m-d H:i") ?>" class="form-control">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="id_salle" class="form-label">Salle</label>
                    <select name="id_salle" id="id_salle" class="form-select">
                        <?php
                        while ($info_salle = $liste_salle->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $info_salle['id_salle'] . '">' . $info_salle['titre'] . '</option>';
                        }


                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Tarif</label>
                    <input type="text" id="prix" name="prix" class="form-control" placeholder="Prix en euros €">
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-outline-dark" id="enregistrer">Enregistrer</button>
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID produit</th>
                                    <th>Date d'arrivée</th>
                                    <th>Date de départ</th>
                                    <th>ID Salle</th>
                                    <th>Prix</th>
                                    <th>Etat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                    while ($ligne = $infos_produit->fetch(PDO::FETCH_ASSOC)){
                                        echo '<tr>';
                                        echo '<td>' . $ligne['id_produit'] . '</td>';
                                        echo '<td>' . $ligne['date_ar'] . '</td>';
                                        echo '<td>' . $ligne['date_dp'] . '</td>';
                                        echo '<td>' . $ligne['id_salle'] . '</td>';
                                        echo '<td>' . $ligne['prix'] . ' €' . '</td>';
                                        echo '<td>' . 'libre' . '</td>';

                                        echo '<td class="mx-3"> <a href="?action=edit&id_produit=' . $ligne['id_produit'] . '"class="btn btn-outline-dark"> <i class="fa-solid fa-pen-to-square"></i></a> <a href="?action=delete&id_produit=' . $ligne['id_produit'] . '"class="btn btn-outline-dark" onclick="return(confirm(\'Êtes-vous sûr de vouloir supprimer cette salle?\'))"> <i class="fa-solid fa-ban"></i></a> </td>';
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
