<?php

namespace Controller;

use Base\Controller;
use Model\Administrateur;


class AdministrateurController extends Controller
{

    /**
     * affiche la page admin
     *
     */
    public function admin()
    {
        include("views/admin.view.php");
    }

    /**
     * enregistrer le nouvel employé
     *
     * @return bool
     */
    public function enregistrer()
    {
        //validation des infos
        if (
            empty($_POST["prenom"]) ||
            empty($_POST["nom"]) ||
            empty($_POST["courriel"]) ||
            empty($_POST["mdp"]) ||
            empty($_POST["confirmer_mdp"])
        ) {
            header("location:compte-creer?infos_absentes=1");
            exit();
        }
        //verifier que les 2 mots de passe concordent(premiere entrée et 2e entrée)
        if ($_POST["mdp"] != $_POST["confirmer_mdp"]) {
            header("location: compte-creer?mdp_incorrect=1");
            exit();
        }

        // Envoyer les infos au modèle
        $modele = new Administrateur;

        $succes = $modele->ajouter(
            $_POST["prenom"],
            $_POST["nom"],
            $_POST["courriel"],
            $_POST["mdp"]
        );
        // Rediriger si succès
        if ($succes) {
            header("location:compte-creer?succes_creation_compte=1");
            exit();
        }
        // Redirection si échec
        header("location:compte-creer?echec_creation_compte=1");
        exit();
    }

    /**
     * Connexion de l'administrateur
     */
    public function connecter()
    {

        //validation parametres POST
        if (
            empty($_POST["courriel"]) ||
            empty($_POST["mdp"])
        ) {
            header("location: admin?infos_requises=1");
            exit();
        }

        //récupere l'administrateur
        $modele = new Administrateur;
        $administrateur = $modele->parCourriel($_POST["courriel"]);

        //Valider que l'administrateur existe et que son mot de passe est valide
        if (!$administrateur || !password_verify($_POST["mdp"], $administrateur->mot_de_passe)) {
            header("location: admin?infos_invalides=1");
            exit();
        }


        // Créer la session et celle pour le droit d'acces aussi

        $_SESSION["administrateur_id"] = $administrateur->id;
        $_SESSION["administrateur_acces"] = $administrateur->droit_acces;
        $_SESSION["est_connecte"] = true;

        // Rediriger
        header("location: admin_menu?succes_connexion=1");
        exit();
    }

    /**
     * Déconnecte l'administrateur
     */
    public function deconnecter()
    {
        // Protection de la route /deconnecter
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }
        session_destroy();
        header("location: index?succes_deconnexion=1");
        exit();
    }
    /**
     * fonction pour creer un employé
     *
     * @return true|false
     */
    public function creer()
    {
        //valide si l'administrateur est gaston  
        if ($_SESSION["administrateur_acces"] != 1) {
            header("location:admin_menu?aucun_droit");
            exit();
        }

        $modele = new Administrateur;
        $employes = $modele->toutEmploye();


        include("views/ajout_compte.view.php");
    }
    /**
     * fonction pour modifier un employé
     *
     * @return true|false
     */
    public function modifier()
    {
        //validation
        // Protection de la route 
        if (empty($_GET["id"])) {
            header("location:compte-creer");
            exit();
        }


        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: compte-creer");
            exit();
        }


        $modele = new Administrateur;

        //recuperation de l'employé
        $un_employe = $modele->recupererUnEmploye($_GET["id"]);


        //protection pour eviter de changer l'id et d'afficher un autre employé
        if ($_GET["id"] != $un_employe->id) {
            header("location:compte-creer");
            exit();
        }

        include("views/modification_employe.view.php");
    }

    /**
     * traitement de la modification d'un employé
     *
     * @return true|false
     */
    public function modifierCompteSubmit()
    {

        if (
            empty($_POST["prenom"]) ||
            empty($_POST["nom"]) ||
            empty($_POST["courriel"])
        ) {
            header("location: modifier-compte?id=" . $_POST["id"] . "&infos_absentes=1");
            exit();
        }

        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        $modele = new Administrateur;

        $succes = $modele->modifierEmploye(
            $_POST["id"],
            $_POST["prenom"],
            $_POST["nom"],
            $_POST["courriel"]
        );

        // Redirection si échec
        if (!$succes) {
            header("location:modifier-compte?echec_modification=1");
            exit();
        }

        // Redirection si succès
        header("location:modifier-compte?id=" . $_POST["id"] . "&succes_modification=1");
        exit();
    }

    /**
     * traitement de la modification du mot de passe
     *
     * @return true|false
     */
    public function ModifierMdpSubmit()
    {
        if (
            empty($_POST["mot_de_passe"]) ||
            empty($_POST["confirmer_mdp"])
        ) {
            header("location: modifier-compte?id=" . $_POST["id"] . "&infos_absentes=1");
            exit();
        }


        if ($_POST["mot_de_passe"] != $_POST["confirmer_mdp"]) {
            header("location:modifier-compte?id=" . $_POST["id"] . "&mdp_incorrect=1");
            exit();
        }

        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        $modele = new Administrateur;

        $succes = $modele->modifierEmployeMdp(
            $_POST["id"],
            $_POST["mot_de_passe"]
        );

        // Redirection si échec
        if (!$succes) {
            header("location:modifier-compte?echec_modification=1");
            exit();
        }

        // Redirection si succès
        header("location:modifier-compte?id=" . $_POST["id"] . "&succes_modification=1");
        exit();
    }


    /**
     * suppression d'un employé
     *
     * @return true|false
     */
    public function suppression()
    {


        //validation
        if (empty($_GET["id"])) {
            header("location:compte-creer");
            exit();
        }

        // Protection de la route /supprimer-compte
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location:index");
            exit();
        }

        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        $modele = new Administrateur;
        $succes = $modele->supprimerCompte($_GET["id"]);

        // Redirection si échec
        if (!$succes) {
            header("location:compte-creer?echec_suppression=1");
            exit();
        }

        // Redirection si succès
        header("location:compte-creer?succes_suppression=1");
        exit();
    }
}
