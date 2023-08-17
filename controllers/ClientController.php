<?php

namespace Controller;

use Base\Controller;
use Model\Client;
use Model\Menu;

class ClientController extends Controller
{
    /**
     * affichage de la page d'accueil
     *
     * @return true|false
     */
    public function index()
    {
        include("views/index.view.php");
    }

    /**
     * affichage du menu
     *
     * @return object
     */
    public function menu()
    {
        $modele = new Menu;
        $entrees1 = $modele->Entree();
        $repas1 = $modele->Repas();
        $dessert1 = $modele->Dessert();

        include("views/menu.view.php");
    }


    /**
     * ajouter un client pour l'infolettre
     *
     * @return true|false
     */
    public function ajouterClient()
    {

        $modele = new Client;
        include("views/index.view.php");
    }

    /**
     * enregistrer les infos du client
     *
     * @return true|false
     */
    public function enregistrerClient()
    {

        if (empty($_POST["nom"]) || empty($_POST["courriel"])) {
            $_SESSION["message"] = "Veuillez remplir tous les champs";
            header("location:index?infos_requises=1");
            exit();
        }

        $modele = new Client;
        $succes = $modele->ajouter($_POST["nom"], $_POST["courriel"]);


        if (!$succes) {
            header("location: index?echec_ajout=1#infolettre");
            exit();
        }

        if ($_POST['afficher_carte'] == 1) {
            $_SESSION['afficher_carte'] = true;
            $_SESSION['nom_client'] = $_POST['nom'];
        } else {
            $_SESSION['afficher_carte'] = false;
            $_SESSION['nom_client'] = '';
        }

        header("Location: index.php?succes_ajout=1#infolettre");
        exit();
    }
}
