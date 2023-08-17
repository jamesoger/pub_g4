<?php

namespace Model;

use Base\Model;

class Administrateur extends Model
{
    protected $table = "administrateurs";
    /**
     * ajout d'employés
     *
     * @param string $prenom
     * @param string $nom
     * @param string $courriel
     * @param string $mdp
     * @return true|false
     */
    public function ajouter(string $prenom, string $nom, string $courriel, string $mdp)
    {
        $sql = "INSERT INTO $this->table 
                    (prenom, nom, courriel, mot_de_passe) 
                VALUES 
                    (:prenom, :nom, :courriel, :mot_de_passe)";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":prenom" => $prenom,
            ":nom" => $nom,
            ":courriel" => $courriel,
            // Encryption du mot de passe
            ":mot_de_passe" => password_hash($mdp, PASSWORD_DEFAULT),
        ]);
    }

    /**
     * modification d'employés
     *
     * @param int $id
     * @param string $prenom
     * @param string $nom
     * @param string $courriel
     * @param string $mot_de_passe
     * @return true|false
     */
    public function modifierEmploye($id, $prenom, $nom, $courriel)
    {
        $sql = "UPDATE 
                $this->table
                SET
                prenom = :prenom, 
                nom = :nom,
                courriel = :courriel
                WHERE 
                id = :id";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":id" => $id,
            ":prenom" => $prenom,
            ":nom" => $nom,
            ":courriel" => $courriel
        ]);
    }

    public function modifierEmployeMdp($id,$mot_de_passe)
    {
        $sql = "UPDATE 
                $this->table
                SET 
                mot_de_passe = :mot_de_passe
                WHERE
                id = :id";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":id"=>$id,
            ":mot_de_passe" => password_hash($mot_de_passe, PASSWORD_DEFAULT)
        ]);
    }


    /**
     * supprimer un compte d'employé
     *
     * @param int $id
     * @return true|false
     */
    public function supprimerCompte($id)
    {

        $sql = "DELETE FROM $this->table
                WHERE id= :id
                ";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":id" => $id,
        ]);
    }
    /**
     * recupere un administrateur en fonction de son courriel
     *
     * @param string $courriel
     * @return object
     */
    public function parCourriel($courriel)
    {

        $sql = "SELECT id, prenom, nom, courriel, mot_de_passe, droit_acces
                FROM $this->table
                WHERE courriel = :courriel";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute([
            ":courriel" => $courriel
        ]);

        return $requete->fetch();
    }
    /**
     * récupere tout d'un employé qui a l'acces 0 donc pas gaston!
     *
     * @return true|false
     */
    public function toutEmploye()
    {
        $sql = "SELECT *
        FROM administrateurs WHERE
        droit_acces = 0";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute();

        return $requete->fetchAll();
    }

    /**
     * recupere un employé selon son id
     *
     * @param [type] $id
     * @return object
     */
    public function recupererUnEmploye($id)
    {
        $sql = "SELECT *
        FROM administrateurs 
        WHERE id = :id";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute([
            ":id" => $id,

        ]);

        return $requete->fetch();
    }
}
