<?php 
    class DataBase extends PDO {
        // Configuration de la connexion
        private $_DB_HOST = 'localhost';
        private $_DB_USER= 'root';
        private $_DB_PASS ='root';
        private $_DB_NAME= 'fpview';

        public function __construct() {
            try {
                parent::__construct("mysql:host=".$this->_DB_HOST .";dbname=".$this->_DB_NAME, $this->_DB_USER, $this->_DB_PASS);
                // Connexion à la base
            }
            catch (PDOException $e) {
                // En cas d'échec : message d'échec de la connexion
                exit("Error: " . $e->getMessage());
            }
        }
    }
?>