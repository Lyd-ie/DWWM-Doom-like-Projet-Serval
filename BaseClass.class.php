<?php
    
    class BaseClass {

        protected $_currentX ; // (int) Coordonnée X sur la carte
        protected $_currentY ; // (int) Coordonnée Y sur la carte
        protected $_currentAngle ; // (int) Angle de vue
        protected $_currentCompass; // (string) Classe spécifiant l'orientation de la boussole
        protected $_mapId ; // (int) l’identifiant de la position courante sur la carte
        protected $_mapStatus; // (int) Statut de la carte
        protected $dbh ; // (object) La connexion à la base de données

        public function __construct() { // constructeur établissant les valeurs de départ du personnage, et établissant l'accès à la bdd
            $this->dbh = new Database();
            $this->_currentX = 0;
            $this->_currentY = 1;
            $this->_currentAngle = 0;
            $this->_mapStatus = 0;
            $this->_currentCompass = 'east';
        }

        public function setCurrentX(int $_currentX) { // met à jour la position X
            $this->_currentX = $_currentX;
        }

        public function getCurrentX() { // permet d'afficher la position X
            return $this->_currentX;
        }

        public function setCurrentY(int $_currentY) { // met à jour la position Y
            $this->_currentY = $_currentY;
        }

        public function getCurrentY() { // permet d'afficher la position Y
            return $this->_currentY;
        }

        public function setCurrentAngle(int $_currentAngle) { // met à jour l'angle de vue
            $this->_currentAngle = $_currentAngle;
            return $_currentAngle;
        }

        public function getCurrentAngle() { // permet d'afficher l'angle de vue
            return $this->_currentAngle;
        }

        private function _checkMove(int $newX, int $newY, int $_currentAngle) { //vérifie la possibilité de déplacement vers une position cible
            $sql = "SELECT id FROM map WHERE coordx=:coordx AND coordy=:coordy AND direction=:direction";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':coordx', $newX, PDO::PARAM_INT);
            $query->bindParam(':coordy', $newY, PDO::PARAM_INT);
            $query->bindParam(':direction', $_currentAngle, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll();

            if(!empty($result)){
                return TRUE;
            } else {
                return FALSE;
            }
        }

        public function checkForward() { // Vérifie la possibilité de déplacement vers l’avant
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : 
                    $newX++;
                    break;

                case 90 :
                    $newY++;
                    break;

                case 180 :
                    $newX--;
                    break;

                case 270 :
                    $newY--;
                    break;

                default :
                    break;
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);

        }

        public function checkBack() { // Vérifie la possibilité de déplacement vers l'arrière
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 :
                    $newX--;
                    break;

                case 90 :
                    $newY--;
                    break;

                case 180 :
                    $newX++;
                    break;

                case 270 :
                    $newY++;
                    break;

                default :
                    break;
            }

            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }

        public function checkRight() { // Vérifie la possibilité de déplacement vers la droite
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : {
                    $newY--;
                    break;
                }
                case 90 : {
                    $newX++;
                    break;
                }
                case 180 : {
                    $newY++;
                    break;
                }
                case 270 : {
                    $newX--;
                    break;
                }
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }

        public function checkLeft() { // Vérifie la possibilité de déplacement vers la gauche
            $newX = $this->_currentX;
            $newY = $this->_currentY;
            switch($this->_currentAngle){
                case 0 : {
                    $newY++;
                    break;
                }
                case 90 : {
                    $newX--;
                    break;
                }
                case 180 : {
                    $newY--;
                    break;
                }
                case 270 : {
                    $newX++;
                    break;
                }
            }
            return $this->_checkMove($newX, $newY, $this->_currentAngle);
        }

        public function goForward() { // Effectue le déplacement vers l’avant
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentX++;
                    break;

                case 90 :
                    $this->_currentY++;
                    break;

                case 180 :
                    $this->_currentX--;
                    break;

                case 270 :
                    $this->_currentY--;
                    break;

                default :
                    break;
            }
        }

        public function goBack() { // Effectue le déplacement vers l’arrière
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentX--;
                    break;

                case 90 :
                    $this->_currentY--;
                    break;

                case 180 :
                    $this->_currentX++;
                    break;

                case 270 :
                    $this->_currentY++;
                    break;

                default :
                    break;
            }
        }

        public function goRight() { // Effectue le déplacement vers la droite
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentY--;
                    break;

                case 90 :
                    $this->_currentX++;
                    break;

                case 180 :
                    $this->_currentY++;
                    break;

                case 270 :
                    $this->_currentX--;
                    break;

                default :
                    break;
            }
        }

        public function goLeft() { // Effectue le déplacement vers la gauche
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentY++;
                    break;

                case 90 :
                    $this->_currentX--;
                    break;

                case 180 :
                    $this->_currentY--;
                    break;

                case 270 :
                    $this->_currentX++;
                    break;

                default :
                    break;
            }
        }

        public function turnRight() { // Tourne sur la droite
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentAngle = 270;
                    break;

                case 90 :
                    $this->_currentAngle = 0;
                    break;

                case 180 :
                    $this->_currentAngle = 90;
                    break;

                case 270 :
                    $this->_currentAngle = 180;
                    break;

                default :
                    break;
            }
        }

        public function turnLeft() { // Tourne sur la gauche
            switch ($this->_currentAngle) {
                case 0 :
                    $this->_currentAngle = 90;
                    break;

                case 90 :
                    $this->_currentAngle = 180;
                    break;

                case 180 :
                    $this->_currentAngle = 270;
                    break;

                case 270 :
                    $this->_currentAngle = 0;
                    break;

                default :
                    break;
            }
        }

        public function getMapId() { // Renvoie l'id de map correspondant aux coordonnées X, Y et Angle

            $_currentX = $this->_currentX;
            $_currentY = $this->_currentY;
            $_currentAngle = $this->_currentAngle;
            
            $sql = "SELECT id FROM map WHERE coordx=:coordx AND coordy=:coordy AND direction=:direction";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':coordx', $_currentX, PDO::PARAM_INT);
            $query->bindParam(':coordy', $_currentY, PDO::PARAM_INT);
            $query->bindParam(':direction', $_currentAngle, PDO::PARAM_INT);
            
            $query->execute();
            $result = $query->fetch();

            $_mapId = $result['id'];

            return $this->_mapId = $_mapId;
        }

        public function setMapStatus(int $_mapStatus) { // met à jour le statut de la map
            $this->_mapStatus = $_mapStatus;
            return $_mapStatus;
        }

        public function getMapStatus() { // Permet de renvoyer le statut de la map dans les recherches d'images et de texte en fonction du statut des actions possédant le même map_id
            $_mapId = $this->getMapId();

            // renvoie un résultat si un id de map est le même que le map_id d'une action
            $sql = "SELECT * FROM action WHERE map_id = :mapId";
            $query = $this->dbh->prepare($sql);
            $query->bindParam(':mapId', $_mapId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();

            // si un résultat est trouvé
            if ($result) {
                // le statut de la map correspond à présent au statut de l'action (zéro si l'action n'a pas été faite, 1 si l'action a été faite)
                $_mapStatus = $result['status'];
            } else {
                // si un résultat n'est pas trouvé (pas d'action possible), le statut de la map sera égal à zéro
                $_mapStatus = 0;
            }

            return $this->_mapStatus = $_mapStatus;
        }
    }
?>