<?php

// Connexion BDD
$host = 'mysql:host=localhost;dbname=room';
$login = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // gestion des erreurs
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' // pour forcer l'utf-8
);

$pdo = new PDO($host, $login, $password, $options);

// Variable vide pour message utilisateurs
$msg = "";

// Ouverture d'une session 
session_start();


// Constante : 

// Constante URL chemin absolue racine du projet 

define('URL', 'http://localhost/room/');

// Constante ROOT_PATH (chemin rfacine du serveur)
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);


// Constante ROOT_SITE (chemin racine du projet depuis racine du serveur (use pour enregistrement photo ))
define('ROOT_SITE', '/room/');



//
if (isset($_SESSION['message_utilisateur'])){
    $_SESSION['message_utilisateur'] = '';
}