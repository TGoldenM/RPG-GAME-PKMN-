<?php
require_once 'db_connection.php';
require_once 'checkLogin.php';
require_once './data_access/region.php';
require_once './data_access/map.php';
require_once './ui_support/dropdown.php';
require_once './ui_support/alertbox.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pokemon CosmosRPG - Members Area</title>
        <link href="css/style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
        <link href="images/layout/favicon.png" rel="shortcut icon">
        <script type="text/javascript" src="jquery/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="jquery/jquery.validate.min.js"></script>
        <script type="text/javascript" src="javascript/sprintf/sprintf.min.js"></script>
        <script type="text/javascript" src="javascript/include.js"></script>
        <script type="text/javascript" src="javascript/pokemon.js"></script>
        <script type="text/javascript" src="javascript/mapView.js"></script>
    </head>
    <body>
        <div class="wrap">
            <!-- Header -->
            <?php include './header.php' ?>

            <div id="content">
                <?php include './leftNavBar.php' ?>
                <?php include './rightNavBar.php' ?>

                <div class="container">
                    <div class="header">Viewing <?php echo $map->name ?></div>
                    <div id="alertBox">
                  <div id='output'></div>

      <canvas id='canvas' width='500' height='400'></canvas>


      <script src='js/engine.js'>         </script>
      <script src='js/engine.screen.js'>  </script>
      <script src='js/engine.viewport.js'></script>
      <script src='js/engine.map.js'>     </script>
      <script src='js/engine.tile.js'>    </script>
      <script src='js/engine.keyboard.js'></script>
      <script src='js/engine.player.js'>  </script>
      <script src='js/engine.script.js'>  </script>
      <script src='js/engine.model.js'>   </script>


      <script>

         var mapone =
         [
            [{ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1, item:3, onenter:0}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1}, {ground:1, item:2, solid:1}],
            [{ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}, {ground:1, item:2, solid:1}]
         ];

         var maptwo =
         [
            [{ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5,item:6, onactivate:2, solid:1}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5, item:4, onenter:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5}, {ground:5, item:2, solid:1}],
            [{ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}, {ground:5, item:2, solid:1}]
         ];


         window.addEventListener('load', function()
         {
            engine.start(mapone, -4, -2);
         }, false);


         window.addEventListener('keydown', engine.keyboard.parseInput, false);

      </script>
                    </div>
                    
                    <div style="padding: 5px;">
                        <div class="pkmn_ctrl">
                       
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- footer -->
        <?php include './footer.php' ?>
    </body>
</html>
