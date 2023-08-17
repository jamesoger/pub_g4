<?php

namespace Model;

use Base\Model;

class Menu extends Model
{
    protected $table = "plats";


    /**
     * recupere toutes les entrées
     *
     * @return array|false
     */
    public function Entree()
    {

        $sql = "SELECT 
        categories.nom AS categorie, plats.nom AS plat,plats.description, plats.prix,plats.image, mot_clef.nom AS mot_clef, regime.nom AS regime, plats.id
        FROM `plats`
        LEFT JOIN
        categories
        ON categories.id = plats.categorie_id
        LEFT JOIN 
        mot_clef
        ON mot_clef.id = plats.mot_clef_id
        LEFT JOIN
        regime
        ON 
        regime.id = plats.regime_id
         WHERE categories.nom = 'Entrée'
        ORDER BY 
        mot_clef.nom, regime.nom";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute();

        return $requete->fetchAll();
    }
    /**
     * recupere tous les repas
     *
     * @return array|false
     */
    public function Repas()
    {

        $sql = "SELECT 
        categories.nom AS categorie, plats.nom AS plat,plats.description, plats.prix,plats.image, mot_clef.nom AS mot_clef, regime.nom AS regime, plats.id
        FROM `plats`
        LEFT JOIN
        categories
        ON categories.id = plats.categorie_id
        LEFT JOIN 
        mot_clef
        ON mot_clef.id = plats.mot_clef_id
        LEFT JOIN
        regime
        ON 
        regime.id = plats.regime_id
         WHERE categories.nom = 'Repas'
        ORDER BY 
        mot_clef.nom, regime.nom";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute();

        return $requete->fetchAll();
    }
    /**
     * recupere tous les desserts
     *
     * @return array|false
     */
    public function Dessert()
    {

        $sql = "SELECT 
        categories.nom AS categorie, plats.nom AS plat,plats.description, plats.prix,plats.image, mot_clef.nom AS mot_clef, regime.nom AS regime, plats.id
        FROM `plats`
        LEFT JOIN
        categories
        ON categories.id = plats.categorie_id
        LEFT JOIN 
        mot_clef
        ON mot_clef.id = plats.mot_clef_id
        LEFT JOIN
        regime
        ON 
        regime.id = plats.regime_id
         WHERE categories.nom = 'Dessert'
        ORDER BY 
        mot_clef.nom, regime.nom";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute();

        return $requete->fetchAll();
    }



    /**
     * Undocumented function
     *
     * @param string $nom
     * @param string $description
     * @param float $prix
     * @param string $image
     * @param integer $categorie_id
     * @param integer $mot_clef_id
     * @param integer $regime_id
     * @return bool
     */

    public function ajouter($nom, $description, $prix, $image, $categorie_id, $mot_clef_id, $regime_id)
    {
        $sql = "INSERT INTO $this->table (nom, description , prix, image , categorie_id, mot_clef_id , regime_id ) VALUES (:nom, :description , :prix, :image , :categorie_id, :mot_clef_id , :regime_id )";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":nom" => $nom,
            ":description" => $description,
            ":prix" => $prix,
            ":image" => $image,
            ":categorie_id" => $categorie_id,
            ":mot_clef_id" => $mot_clef_id,
            ":regime_id" => $regime_id
        ]);
    }

    /**
     * Supprimer un plat
     *
     * @param int $id
     * @return object|false
     */
    public function supprimerPlat($id)
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
     * modifier un plat
     *
     * @param int $id
     * @param string $nom
     * @param string $description
     * @param float $prix
     * @param string $image
     * @param int $categorie_id
     * @param int $mot_clef_id
     * @param int $regime_id
     * @return void
     */
    public function modifierPlat($id, $nom, $description, $prix, $image, $categorie_id, $mot_clef_id, $regime_id)
    {

        $sql = "UPDATE $this->table
                SET
                  nom = :nom, 
                  description = :description,
                  prix = :prix,
                  image = :image,
                  categorie_id = :categorie_id,
                  mot_clef_id = :mot_clef_id,
                  regime_id = :regime_id
                  WHERE 
                  id = :id";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":id" => $id,
            ":nom" => $nom,
            ":description" => $description,
            ":prix" => $prix,
            ":image" => $image,
            ":categorie_id" => $categorie_id,
            ":mot_clef_id" => $mot_clef_id,
            ":regime_id" => $regime_id
        ]);
    }
    /**
     * recupere un plat selon son id
     *
     * @param int $id
     * @return true|false
     */
    public function recupererUnPlat($id)
    {

        $sql ="SELECT plats.*, categories.nom AS nom_categories, mot_clef.nom AS nom_mot_clef, regime.nom AS nom_regime
        FROM $this->table
        LEFT JOIN categories 
        ON plats.categorie_id = categories.id
        LEFT JOIN mot_clef
        ON plats.mot_clef_id = mot_clef.id
        LEFT JOIN regime
        ON plats.regime_id = regime.id
        WHERE plats.id = :id";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute([
            ":id" => $id,
        ]);

        return $requete->fetch();
    }

    
}
  