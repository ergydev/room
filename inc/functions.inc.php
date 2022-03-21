<?php

// fonction renvoyant true si l'user est connecté sinon false 

function user_is_connected(){
    if(!empty($_SESSION['membre'])){
        return true;
    } else{
        return false;
    }
}

// fction permettant de savoir si le user est admin

function user_is_admin(){
    if(user_is_connected() && $_SESSION['membre']['statut'] == 2){
        return true;
    }
    return false;
}
