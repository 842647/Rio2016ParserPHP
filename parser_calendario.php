<?php
/**
 * Created by PhpStorm.
 * User: crillin
 * Date: 02/08/16
 * Time: 21:51
 */

require "simple_html_dom.php";

//classe del calendario
$classe_calendario = "schedule-general-table__sports";

//vado a pescarmi la tabella
$html = file_get_html('https://www.rio2016.com/en/schedule-and-results');
$table = $html->find('table[class='.$classe_calendario.']', 0);

$json_dati = array();

foreach($table->find('tr') as $row) {
    //json della riga
    $json_riga = array();

    //prendo la prima colonna, dove troverò url ed icone
    $riga_icona = str_get_html($row->firstchild()->innertext);
    $json_riga["url_evento"] = "https://www.rio2016.com". $riga_icona->find('a', 0)->href;
    $json_riga["icona_evento"] = $riga_icona->find('span', 0)->attr["class"];

    //prendo la seconda colonna, dove troverò il nome dell'evento
    $riga_nome_evento = str_get_html($row->children(1)->innertext);
    $json_riga["nome_evento"] = trim($riga_nome_evento->find('span', 0)->plaintext);

    //ciclo le varie righe per avere giorno, icona, link
    $json_array_giorni = array();
    for($i = 2; $i < sizeof($row->childNodes()); $i++){
        if (!empty(trim($row->children($i)->innertext))) {
            $riga_giorno = str_get_html($row->children($i)->innertext);

            //numero del giorno
            $json_giorno["giorno"] = $i+1;

            //icona dell'evento
            $json_giorno["icona_evento"] = $riga_giorno->find('span', 0)->attr["class"];

            //url dell'evento
            $json_giorno["url_evento"] = "https://www.rio2016.com". $riga_giorno->find('a', 0)->href;

            $json_array_giorni[] = $json_giorno;
        }
    }

    //aggiungo l'array di json all'array della riga
    $json_riga["calendario_evento"] = $json_array_giorni;

    //aggiungo all'array coi dati
    $json_dati[] = $json_riga;
}

echo json_encode($json_dati);