<?php
    class FirstPersonView extends BaseClass {

        protected $_mapId ; // (int) l’identifiant de la position courante sur la carte

        public function getView(BaseClass $perso) { // Renvoie le chemin vers le fichier .jpg à afficher
            
            $_currentX = $perso->_currentX;
            $_currentY = $perso->_currentY;
            $_currentAngle = $perso->_currentAngle;
            $_mapId = $perso->getMapId();
            $_mapStatus = $perso->getMapStatus();

            $sql = "SELECT path FROM images WHERE map_id = :mapId AND status_action=:status";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':mapId', $_mapId, PDO::PARAM_INT);
            $query->bindParam(':status', $_mapStatus, PDO::PARAM_INT);
            $query->execute();
            $view = $query->fetch();
            
            return $view['path'];
        }

        public function getAnimCompass(BaseClass $perso) { // Renvoie la direction vers laquelle pointe la boussole (via une classe CSS)
            switch ($perso->_currentAngle) {
                case 0 :
                    $perso->_currentCompass = 'east';
                    break;

                case 90 :
                    $perso->_currentCompass = 'north';
                    break;

                case 180 :
                    $perso->_currentCompass = 'west';
                    break;

                case 270 :
                    $perso->_currentCompass = 'south';
                    break;

                default :
                    break;
            }

            return $perso->_currentCompass;
        }
    }
?>