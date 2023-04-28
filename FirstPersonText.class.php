<?php
    class FirstPersonText extends BaseClass {

        public function getText(BaseClass $perso) { // Renvoie le texte à afficher selon l'id de map et son statut
            $_mapId = $perso->getMapId();
            $_mapStatus = $perso->getMapStatus();

            $sql = "SELECT * FROM text WHERE map_id = :mapId AND status_action=:status";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':mapId', $_mapId, PDO::PARAM_INT);
            $query->bindParam(':status', $_mapStatus, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
            
            // si un résultat est trouvé, le texte correspond à celui de la table texte qui ressort de la recherche
            if ($result) {
                $text = $result['text'];
            } else {
                // sinon le texte affiché sera :
                $text = "Voyons-voir par ici...";
            }
            
            return $text;
        }

    }
?>