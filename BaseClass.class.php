<?php
    
    class BaseClass {

        protected $_currentX ; // (int) Coordonnée X sur la carte
        protected $_currentY ; // (int) Coordonnée Y sur la carte
        protected $_currentAngle ; // (int) Angle de vue
        protected $_currentCompass; // (string) Classe spécifiant l'orientation de la boussole
        protected $_mapStatus; // (int) Statut de la carte
        protected $dbh ; // (object) La connexion à la base de données

        public function __construct() {
            $this->dbh = new Database();
            $this->_currentX = 0;
            $this->_currentY = 1;
            $this->_currentAngle = 0;
            $this->_mapStatus = 0;
            $this->_currentCompass = 'east';
        }

        public function setCurrentX(int $_currentX) { 
            $this->_currentX = $_currentX;
        }

        public function getCurrentX() {
            return $this->_currentX;
        }

        public function setCurrentY(int $_currentY) {
            $this->_currentY = $_currentY;
        }

        public function getCurrentY() {
            return $this->_currentY;
        }

        public function setCurrentAngle(int $_currentAngle) {
            $this->_currentAngle = $_currentAngle;
            return $_currentAngle;
        }

        public function getCurrentAngle() {
            return $this->_currentAngle;
        }

        public function setMapStatus(int $_mapStatus) {
            $this->_mapStatus = $_mapStatus;
            return $_mapStatus;
        }

        private function _checkMove(int $newX, int $newY, int $_currentAngle) { //vérifie la possibilité de déplacement vers une position cible
                       
            $stmt = $this->dbh->prepare("SELECT id FROM map WHERE coordx = $newX AND coordy = $newY AND direction = $_currentAngle");
            $stmt->execute();
            $result = $stmt->fetchAll();
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

        public function getMapId() { // Renvoie l'id de map correspondant aux coordonnées

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

        public function getMapStatus() { // Renvoie le statut de la map correspondant à son id
            $_mapId = $this->getMapId();
            $sql5 = "SELECT * FROM action WHERE map_id = :mapId";
            $stmt5 = $this->dbh->prepare($sql5);
            $stmt5->bindParam(':mapId', $_mapId, PDO::PARAM_INT);
            $stmt5->execute();
            $status = $stmt5->fetch();

            if ($status) {
                $_mapStatus = $status['status'];
            } else {
                $_mapStatus = 0;
            }

            return $this->_mapStatus = $_mapStatus;
        }
    }
?>