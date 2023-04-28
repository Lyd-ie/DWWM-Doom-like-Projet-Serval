<?php
    class FirstPersonText extends BaseClass {

        public function getText(BaseClass $perso) { // Renvoie le texte à afficher
            
            $_currentX = $perso->_currentX;
            $_currentY = $perso->_currentY;
            $_currentAngle = $perso->_currentAngle;
            $_mapStatus = $perso->getMapStatus();
            
            $sql2 = "SELECT *
                     FROM text
                     JOIN map
                     ON text.map_id = map.id
                     WHERE coordx=:coordx AND coordy=:coordy AND direction=:direction AND text.status_action=:status";
            $stmt2 = $this->dbh->prepare($sql2);
            $stmt2->bindParam(':coordx', $_currentX, PDO::PARAM_INT);
            $stmt2->bindParam(':coordy', $_currentY, PDO::PARAM_INT);
            $stmt2->bindParam(':direction', $_currentAngle, PDO::PARAM_INT);
            $stmt2->bindParam(':status', $_mapStatus, PDO::PARAM_INT);
            $stmt2->execute();
            $text = $stmt2->fetch();
            
            if ($text) {
                $result = $text['text'];
            } else {
                $result = "Voyons-voir par ici...";
            }
            
            return $result;
        }

    }
?>