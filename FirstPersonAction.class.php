<?php
    class FirstPersonAction extends BaseClass {

        public function checkAction(BaseClass $perso) { // Détermine en fonction de la position sur la map si les conditions sont requises pour réaliser une action. Si l’action n’est pas possible, le bouton d’action est désactivé.
            $_mapId = $perso->getMapId();
            
            // recherche dans la table action les lignes correspondantes à l'id de map
            $sql = "SELECT * FROM action WHERE map_id=:mapid";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':mapid', $_mapId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();

            // initialise $_SESSION['item'] à zéro (pas d'objet en inventaire) en début de partie
            if (!$_SESSION['item']) {
                $_SESSION['item'] = 0;
            }

            // si un résultat est trouvé, il est comparé à la valeur de $_SESSION['item'] (égale à 0 ou 1) pour vérifier si un objet est requis, et si l'objet requis est en inventaire ou non
            if ($result) {
                if ($result['requis'] == $_SESSION['item'] ) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
       }

        public function doAction(BaseClass $perso) { // Réalise l’action et modifie la base de données en conséquence
            $_mapId = $perso->getMapId();
            $status = 1;

            // passe le statut de l'action correspondante de zéro à 1
            $sql = "UPDATE action SET status=:status WHERE map_id=:mapid";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':mapid', $_mapId, PDO::PARAM_INT);
            $query->execute();
            
            // si au moment de l'action l'inventaire est vide
            if (!isset($_SESSION['item']) || $_SESSION['item'] == 0) {

                // on récupère les informations de l'objet et les stocke dans $_SESSION
                $sql = "SELECT * FROM items";
                $query = $this->dbh->prepare($sql);
                $query->execute();
                $item = $query->fetch();

                $_SESSION['item'] = (int)$item['id'];
                $_SESSION['description'] = $item['description'];

            // en revanche si l'objet est dans l'inventaire, l'action utilise l'objet et l'inventaire devient donc vide
            } else {
                $_SESSION['item'] = 0;
                $_SESSION['description'] = "vide";
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