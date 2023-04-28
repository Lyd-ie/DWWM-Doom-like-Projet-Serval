<?php
    class FirstPersonAction extends BaseClass {

        public function checkAction(BaseClass $perso) { // Détermine en fonction de la position sur la map si les conditions sont requises pour réaliser une action. Si l’action n’est pas possible, le bouton d’action est désactivé.
            $_newX = $perso->_currentX;
            $_newY = $perso->_currentY;
            $_newAngle = $perso->_currentAngle;
            
            $sql3 = "SELECT *
                     FROM action
                     JOIN map
                     ON action.map_id = map.id
                     WHERE coordx=:coordx AND coordy=:coordy AND direction=:direction";
            $stmt3 = $this->dbh->prepare($sql3);
            $stmt3->bindParam(':coordx', $_newX, PDO::PARAM_INT);
            $stmt3->bindParam(':coordy', $_newY, PDO::PARAM_INT);
            $stmt3->bindParam(':direction', $_newAngle, PDO::PARAM_INT);
            $stmt3->execute();
            $action = $stmt3->fetch();

            if (!$_SESSION['item']) {
                $_SESSION['item'] = 0;
            }

            if ($action) {
                if ($action['requis'] == $_SESSION['item'] ) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
       }

        public function doAction(BaseClass $perso) { // Réalise l’action et modifie la base de données en conséquence
            $_currentX = $perso->_currentX;
            $_currentY = $perso->_currentY;
            $_currentAngle = $perso->_currentAngle;
            $_mapStatus = $perso->_mapStatus;
            
            $sql2 = "SELECT *
                     FROM action
                     JOIN map
                     ON action.map_id = map.id
                     WHERE coordx=:coordx AND coordy=:coordy AND direction=:direction";
            $stmt2 = $this->dbh->prepare($sql2);
            $stmt2->bindParam(':coordx', $_currentX, PDO::PARAM_INT);
            $stmt2->bindParam(':coordy', $_currentY, PDO::PARAM_INT);
            $stmt2->bindParam(':direction', $_currentAngle, PDO::PARAM_INT);
            $stmt2->execute();
            $action = $stmt2->fetch();
            
            if ($action) {

                if (!isset($_SESSION['item']) || $_SESSION['item'] == 0) {

                $itemSearch = "SELECT * FROM items";
                    $query = $this->dbh->prepare($itemSearch);
                    $query->execute();
                    $item = $query->fetch();
                    $_SESSION['item'] = (int)$item['id'];
                    $_SESSION['description'] = $item['description'];
                } else {
                    $_SESSION['item'] = 0;
                    $_SESSION['description'] = "vide";
                }

                $status = 1;

                $doAction = "UPDATE action SET status=:status WHERE map_id=:mapid";
                $query2 = $this->dbh->prepare($doAction);
                $query2->bindParam(':status', $status, PDO::PARAM_INT);
                $query2->bindParam(':mapid', $action['id'], PDO::PARAM_INT);
                $query2->execute();
            }
        }

        public function reset($perso) { // remet les valeurs initiales dans les setters de positions, la table action et la variable $_SESSION
        $statusReset = 0;

        $reset = "UPDATE action SET status=:status";
        $query = $this->dbh->prepare($reset);
        $query->bindParam(':status', $statusReset, PDO::PARAM_INT);
        $query->execute();

        $_SESSION['item'] = 0;
        $_SESSION['description'] = "vide";
        $perso->setCurrentX(0);
        $perso->setCurrentY(1);
        $perso->setCurrentAngle(0);
        }
    }
?>