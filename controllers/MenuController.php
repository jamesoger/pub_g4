<?php

namespace Controller;

use Base\Controller;
use Model\Menu;
use Model\Categorie;
use Model\MotClef;
use Model\Regime;
use Util\Upload;

class MenuController extends Controller
{
    /**
     * page admin menu
     *
     * @return object
     */
    public function admin_menu()
    {
        //protection de la route pour la page admin du menu
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }

        $modele = new Menu;
        //recuperation des catégories, mots clefs et regimes
        $categories = (new Categorie)->tout();
        $mots_clefs = (new MotClef)->tout();
        $regimes = (new Regime)->tout();


        include("views/admin_menu.view.php");
    }
    /**
     * ajout de nouveaux plats
     *
     * @return true|false
     */
    public function ajouter()
    {
        // Protection de la route ajouter
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }
        $modele = new Menu;
        //recuperation des catégories, mots clefs et regimes
        $categories = (new Categorie)->tout();
        $mots_clefs = (new MotClef)->tout();
        $regimes = (new Regime)->tout();
        $entrees1 = $modele->Entree();
        $repas1 = $modele->Repas();
        $desserts = $modele->Dessert();
        //inclusion de la vue ajout d'activité
        include("views/admin_menu.view.php");
    }

    /**
     * enregistrer le nouveau plat
     *
     * @return true|false
     */
    public function enregistrer()
    {
        // Protection de la route /publications
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }



        // Validation des paramètres
        if (
            empty($_POST["nom"]) || empty($_POST["description"])
            || empty($_POST["prix"]) || ($_POST["noms_categorie"] == "choisissez une catégorie") ||
            empty($_POST["noms_categorie"])
        ) {
            header("location: admin_menu?infos_requises=1");
            exit();
        }

        if (!is_numeric($_POST["prix"])) {
            header("location: admin_menu?pas_chiffre=1");
            exit();
        }

        $regime_id = null;
        $mot_clef_id = null;

        $regime_id = ($_POST["regime"] == "vide") ? null : $_POST["regime"];
        $mot_clef_id = ($_POST["mot_clef"] == "vide") ? null : $_POST["mot_clef"];

        if ((($_POST["regime"] != "vide") && ($_POST["mot_clef"]) != "vide")  && (($_POST["regime"] != "vide") || ($_POST["mot_clef"]) != "vide")) {
            header("location: admin_menu?un_seul_champ=1");
            exit();
        }


        // Traitement de l'image s'il y a lieu
        $image = null;
        $upload = new Upload("image", ["jpeg", "jpg", "png", "webp", "gif"]);
        if ($upload->estValide()) {
            $image = $upload->placerDans("uploads");
        }
        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];


        // Ajouter du plat
        $modele = new Menu;
        $succes = $modele->ajouter(
            $_POST["nom"],
            $_POST["description"],
            $_POST["prix"],
            $image,
            $_POST["noms_categorie"],
            $mot_clef_id,
            $regime_id
        );


        // Redirection si échec
        if (!$succes) {
            header("location:admin_menu?echec_ajout=1");
            exit();
        }

        // Redirection si succès
        header("location:admin_menu?succes_ajout=1");
        exit();
    }
    /**
     * suprression d'un plat
     *
     * @return true|false
     */
    public function suppression()
    {
        //validation
        if (empty($_GET["id"])) {
            header("location:index");
            exit();
        }

        // Protection de la route /supprimer-plat
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }

        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        $modele = new Menu;
        $succes = $modele->supprimerPlat($_GET["id"]);

        // Redirection si échec
        if (!$succes) {
            header("location: admin_menu?echec_suppression=1");
            exit();
        }

        // Redirection si succès
        header("location: admin_menu?succes_suppression=1");
        exit();
    }


    /**
     * modification d'un plat
     *
     * @return true|false
     */
    public function modifier()
    {
        //validation
        // Protection de la route modifier-plat
        if (empty($_GET["id"])) {
            header("location:admin_menu");
            exit();
        }

        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }



        $modele = new Menu;
        //recuperation du plat
        $plat = $modele->recupererUnPlat($_GET["id"]);

        //recuperation des catégories, mots clefs et regimes
        $categories = (new Categorie)->tout();
        $mots_clefs = (new MotClef)->tout();
        $regimes = (new Regime)->tout();


        //inclusion de la vue de modification


        include("views/modification_plat.view.php");
    }

    /**
     * enregistrer la modification
     *
     * @return true|false
     */
    public function modifierPlatSubmit()
    {

        if (isset($_POST["prix"]) && !empty($_POST["prix"]) && !is_numeric($_POST["prix"])) {
            header("location: modifier-plat?id=" . $_POST["id"] . "&pas_chiffre=1");
            exit();
        }

        // Protection de la route /modifier-plat
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location:index");
            exit();
        }

        // Validation des paramètres

        if (
            empty($_POST["nom"]) || empty($_POST["prix"])
            || empty($_POST["description"]) || ($_POST["noms_categorie"] == "choisissez une catégorie")
        ) {
            header("location:modifier-plat?id=" . $_POST["id"] . "&infos_requises=1");
            exit();
        }

        $regime_id = null;
        $mot_clef_id = null;

        if ((($_POST["regime"] != "vide") && ($_POST["mot_clef"]) != "vide") || (!empty($_POST["nouveau_mot_clef"]) && (($_POST["regime"] != "vide") || ($_POST["mot_clef"]) != "vide"))) {
            header("location: modifier-plat?id=" . $_POST["id"] . "&un_seul_champ=1");
            exit();
        }


        $regime_id = ($_POST["regime"] == "vide") ? null : $_POST["regime"];
        $mot_clef_id = ($_POST["mot_clef"] == "vide") ? null : $_POST["mot_clef"];

        if (!empty($_POST["nouveau_mot_clef"])) {
            $modeleMotClef = new MotClef;
            $nouveauMotClefId = $modeleMotClef->ajouter($_POST["nouveau_mot_clef"]);
            $mot_clef_id = $nouveauMotClefId;
        }
        // Traitement de l'image s'il y a lieu
        $image = null;
        $upload = new Upload("image", ["jpeg", "jpg", "png", "webp"]);
        if ($upload->estValide()) {
            $image = $upload->placerDans("uploads");
        }


        $modele = new Menu;
        $succes = $modele->modifierPlat(
            $_POST["id"],
            $_POST["nom"],
            $_POST["description"],
            $_POST["prix"],
            $image,
            $_POST["noms_categorie"],
            $mot_clef_id,
            $regime_id
        );


        // Redirection si echec
        if (!$succes) {
            header("location:modifier-plat?echec_modification=1");
            exit();
        }

        // Redirection si succès
        header("location:admin_menu?id=" . $_POST["id"] . "&succes_modification=1");
        exit();
    }

    /**
     * ajout d'un nouveau mot-clef
     *
     * @return object
     */
    public function ajouterMotClef()
    {
        // Protection de la route /modifier-plat
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location:index");
            exit();

            $modele = new MotClef;
        }
    }

    /**
     * enregistrer le nouveau mot-clef
     */
    public function enregistrerMotClef()
    {
        // Protection de la route /publications
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location: index");
            exit();
        }
        if (empty($_POST["nom"])) {
            header("location:admin_menu?infos_requises=1");
            exit();
        }

        $modele = new MotClef;
        $succes = $modele->ajouter($_POST["nom"]);



        // Redirection si echec
        if (!$succes) {
            header("location:admin_menu?echec_ajout_mot_clef=1");
            exit();
        }

        // Redirection si succès
        header("location:admin_menu?id=" . $_POST["id"] . "&succes_ajout_mot_clef=1");
        exit();
    }

    /**
     * suppression d'un mot-clef
     *
     * @return true|false
     */
    public function supprimerMotClef()
    {

        //validation
        if (empty($_GET["id"])) {
            header("location:admin_menu");
            exit();
        }

        // Protection de la route /supprimer-compte
        if (empty($_SESSION["administrateur_id"]) == true) {
            header("location:index");
            exit();
        }


        // Récupération de l'id de l'administrateur
        $administrateur_id = $_SESSION["administrateur_id"];

        $modele = new MotClef;
        $succes = $modele->supprimerMotClef($_GET["id"]);


        // Redirection si échec
        if (!$succes) {
            header("location:admin_menu?echec_suppression_mot_clef=1");
            exit();
        }

        // Redirection si succès
        header("location:admin_menu?succes_suppression_mot_clef=1");
        exit();
    }
}
