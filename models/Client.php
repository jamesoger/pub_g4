<?php

namespace Model;

use Base\Model;

/**
 * class client
 */
class Client extends Model
{
    protected $table = "clients";
    /**
     * ajouter nouveau client
     *
     * @param string $nom
     * @param string $courriel
     * @return true|false
     */
    public function ajouter($nom, $courriel)
    {

        $sql = "INSERT INTO $this->table(nom, courriel)
                VALUES (:nom, :courriel)";

        $requete = $this->pdo()->prepare($sql);

        return $requete->execute([
            ":nom" => $nom,
            ":courriel" => $courriel
        ]);
    }
}
