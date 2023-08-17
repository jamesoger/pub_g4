<?php

namespace Model;

use Base\Model;

/**
 * class MotClef
 */
class MotClef extends Model
{
    protected $table = "mot_clef";

    /**
     * ajout d'un mot-clef
     *
     * @param string $nom
     * @return true|false
     */
    public function ajouter($nom)
    {

        $sql = "INSERT INTO $this->table(nom)
                VALUES (:nom)";

        $requete = $this->pdo()->prepare($sql);

        $requete->execute([
            ":nom" => $nom
        ]);

        return $this->pdo()->lastInsertId();
    }
    /**
     * supprimer un mot-clef
     *
     * @param int $id
     * @return true|false
     */
    public function supprimerMotClef($id)
    {
        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
        $this->pdo()->exec($sql);

        $sql = "DELETE FROM $this->table WHERE id = :id;";
        $requete = $this->pdo()->prepare($sql);

        $succes = $requete->execute([
            ":id" => $id,
        ]);

        $sql = "SET FOREIGN_KEY_CHECKS = 1;";
        $this->pdo()->exec($sql);

        return $succes;
    }


}
