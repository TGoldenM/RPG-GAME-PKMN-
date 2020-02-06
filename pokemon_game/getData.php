<?php
require '/classes/simple_html_dom.php';
$html = file_get_html("http://pokemondb.net/pokedex/clefairy");
echo $html->find(".cell-barchart", 0);







?>