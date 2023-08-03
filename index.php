<?php

    // Empêche les erreurs de s'afficher à l'écran, mais toujours dans error_log
    ini_set('display_errors','Off');
    ini_set('error_reporting', E_ALL );
    define('WP_DEBUG', false);
    define('WP_DEBUG_DISPLAY', false);

    session_start(); // variable $_SESSION

    // autoload des différentes classes
    function chargerClasses($class) {
        include $class . '.class.php';
    }
    spl_autoload_register('chargerClasses');
    
    // initialisation des différents objets
    $toto = new BaseClass();
    $view = new FirstPersonView();
    $text = new FirstPersonText();
    $action = new FirstPersonAction();
    
    // setters récupérant les positions de $toto et le statut de la map où il se trouve
    if (isset($_POST['currentAngle'])) {
        $toto->setCurrentX($_POST['currentX']);
        $toto->setCurrentY($_POST['currentY']);
        $toto->setCurrentAngle($_POST['currentAngle']);
        $toto->setMapStatus($_POST['mapStatus']);
    }

    // les différents $_POST activant les mouvements et actions de $toto
    if (isset($_POST['turnLeft'])) {
        $toto->turnLeft();
    }

    if (isset($_POST['goForward'])) {
        $toto->goForward();
    }

    if (isset($_POST['turnRight'])) {
        $toto->turnRight();
    }

    if (isset($_POST['goLeft'])) {
        $toto->goLeft();
    }

    if (isset($_POST['goRight'])) {
        $toto->goRight();
    }

    if (isset($_POST['goBack'])) {
        $toto->goBack();
    }

    if (isset($_POST['action'])) {
        $action->doAction($toto);
    }

    if (isset($_POST['reset'])) {
        $action->reset($toto);
    }
?>
    
<!DOCTYPE html>
<html lang="FR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <link rel="preload" as="image" href="assets/compass.png">
        <title>SERVAL: DOOM-LIKE</title>
    </head>

    <body>
        <div class="absolute overlay">
            <div class="game">
                <div class="row">
                    <!-- Affichage de l'image que renvoie la base de donnée selon les coordonnées de $toto et le statut de la map -->
                    <img class="view" src="images/<?php echo $view->getView($toto) ?>" alt="view">
                </div>
                <div class="row">
                    <div class="left">
                        <form class="directions overlay" method="post" action="index.php">
                            <!-- Les getters cachés permettant de conserver les informations de $toto après chaque submit -->
                            <input type="hidden" name="currentX" value="<?php echo $toto->getCurrentX(); ?>">
                            <input type="hidden" name="currentY" value="<?php echo $toto->getCurrentY(); ?>">
                            <input type="hidden" name="currentAngle" value="<?php echo $toto->getCurrentAngle(); ?>">
                            <input type="hidden" name="mapStatus" value="<?php echo $toto->getMapStatus(); ?>">
                            <table>
                                <!-- Les différents boutons submit permettant de faire bouger $toto. -->
                                <!-- Les fonctions check***() activent ou désactivent les boutons selon les possibilités de mouvements -->
                                <tr>
                                    <td><input type="submit" class="icon fa" value="&#xf0e2;" name="turnLeft"></td>
                                    <td><input type="submit" class="icon fa" value="&#xf062;" name="goForward" <?php echo $toto->checkForward() == TRUE ? "enabled" : "disabled"; ?>></td>
                                    <td><input type="submit" class="icon fa" value="&#xf01e;" name="turnRight"></td>
                                </tr>
                                <tr>
                                    <td><input type="submit" class="icon fa" value="&#xf060;" name="goLeft" <?php echo $toto->checkLeft() == TRUE ? "enabled" : "disabled"; ?>></td>
                                    <td><input type="submit" class="icon fa" value="&#xf05b;" name="action" <?php echo $action->checkAction($toto) == TRUE ? "enabled" : "disabled"; ?>></td>
                                    <td><input type="submit" class="icon fa" value="&#xf061;" name="goRight" <?php echo $toto->checkRight() == TRUE ? "enabled" : "disabled"; ?>></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="submit" class="icon fa" value="&#xf063;" name="goBack" <?php echo $toto->checkBack() == TRUE ? "enabled" : "disabled"; ?>></td>
                                    <td></td>
                                </tr>
                            </table>
                        </form>
                        <!-- Affichage de l'orientation de la boussole selon l'angle de vue de $toto -->
                        <img class="compass <?php echo $view->getAnimCompass($toto) ?>" src="assets/compass.png" alt="compass">
                    </div>
                    <div class="right">
                        <!-- Affichage du texte selon la position et les actions de $toto -->
                        <p class="text overlay"><?php echo $text->getText($toto); ?></p>
                        <div class="inventory">
                            <!-- Affichage de l'inventaire de $toto (enregistré dans la variable $_SESSION) -->
                            <p><span>INVENTAIRE</span> : <?php echo isset($_SESSION['description']) ? $_SESSION['description'] : "vide"; ?></p>
                            
                            <!-- Bouton reset pour remettre à zéro la partie ! -->
                            <form method="post" action="index.php">
                                <input type="submit" value="Reset" name="reset"> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Script permettant de contrôler les mouvements du personnage via le clavier
            // Les différents boutons submit sont activés via les touches AZEQSDF
            turnLeft = document.querySelector("input[name='turnLeft']");
            goForward = document.querySelector("input[name='goForward']");
            turnRight = document.querySelector("input[name='turnRight']");
            goLeft = document.querySelector("input[name='goLeft']");
            action = document.querySelector("input[name='action']");
            goRight = document.querySelector("input[name='goRight']");
            goBack = document.querySelector("input[name='goBack']");

            document.addEventListener("keydown", (event) => {
                
                switch (event.code) {
                    case 'KeyQ':
                        turnLeft.click();
                        break;
                    case 'KeyW':
                        goForward.click();
                        break;
                    case 'KeyE':
                        turnRight.click();
                        break;
                    case 'KeyA':
                        goLeft.click();
                        break;
                    case 'KeyF':
                        action.click();
                        break;
                    case 'KeyD':
                        goRight.click();
                        break;
                    case 'KeyS':
                        goBack.click();
                        break;
                    default:
                        break;
                }
            })

            // Fonction préloadant les images pour améliorer la fluidité des déplacements du personnage
            function p(im_url) {
                let img = new Image();
                img.src = "images/"+im_url+".jpg";
            }
            p("01-0"); p("01-90"); p("01-180"); p("01-180-1"); p("01-270");
            p("10-0"); p("10-90"); p("10-180"); p("10-270");
            p("11-0"); p("11-90"); p("11-180"); p("11-270");
            p("12-0"); p("12-90"); p("12-90-1"); p("12-180"); p("12-270");        
        </script>
    </body>
</html>