<?php

$routes = [
    // route => [controller, méthode]
    
    //page accueil
    "index" => ["ClientController", "index"],

    //page menu
    "menu" =>["ClientController", "menu"],

    //ajout d'un client pour infolettre
    "ajout-client"=>["ClientController", "ajouterClient"],

    //traitement des informations du client
    "enregistrer-client"=>["ClientController", "enregistrerClient"],

    //connexion admin
    "admin" => ["AdministrateurController", "admin"],

    // Traitement de la connexion
    "connecter" => ["AdministrateurController", "connecter"],
    
    // Formulaire de création de compte
    "compte-creer" => ["AdministrateurController", "creer"],

    // Traitement de la création d'un compte
    "compte-enregistrer" => ["AdministrateurController", "enregistrer"],

    //formulaire de modification d'employés
    "modifier-compte" => ["AdministrateurController", "modifier"],

    //suppression d'un employé
    "compte-supprimer"=>["AdministrateurController", "suppression"],

    //traitement de la modification du compte
    "compte-modifier" => ["AdministrateurController", "modifierCompteSubmit"],

    //traitement de la modification du mot de passe de l'employé
    "mdp-modifier" =>["AdministrateurController" ,"ModifierMdpSubmit"],
    
    // Traitement de la déconnexion
    "deconnecter" => ["AdministrateurController", "deconnecter"],

    //affichage de la page d'admin du menu
    "admin_menu" =>["MenuController", "admin_menu"],

    //ajout d'un plat
    "admin_menu" =>["MenuController" , "ajouter"],

    //traitement de la création d'un plat
    "plat-enregistrer"=>["MenuController", "enregistrer"],

    //suppression d'un plat
    "supprimer-plat"=>["MenuController", "suppression"],

    //page de modification d'un plat
    "modifier-plat"=>["MenuController", "modifier"],

    //traitement de la modification du plat
    "plat-modifier"=>["MenuController", "modifierPlatSubmit"],

    //suppression d'un mot-clef
    "supprimer-mot-clef"=>["MenuController" , "supprimerMotClef"],

    //ajout d'un mot-clef
    "ajout-mot-clef"=>["MenuController" , "ajouterMotClef"],

    //ajout d'un mot-clef
    "enregistrer-mot-clef"=>["MenuController" , "enregistrerMotClef"]


];