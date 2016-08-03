<?php

require "simple_html_dom.php";

//vado a pescarmi la pagina
$html = file_get_html('https://www.rio2016.com/en/olympic-torch-relay');

$json_dati = array();

//città corrente
$h2_current_city = $html->find('h2[class=city-info--current-city]', 0);	
$json_dati["current_city"] = trim($h2_current_city->plaintext);

//città di oggi
$ul_cities_list = $html->find('ul[class=cities-list]', 0);	

$json_elenco_citta = array();
foreach($ul_cities_list->find("li[class=cities-list--item]") as $li){
	$json_elenco_citta[] = $li->plaintext;
}

$json_dati["today_cities"] = $json_elenco_citta;

echo json_encode($json_dati);