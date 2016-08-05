<?php
/**
 * Created by PhpStorm.
 * User: crillin
 * Date: 06/08/16
 * Time: 00:02
 */

/* Parser of: https://www.rio2016.com/en/torch-relay-route */

require "simple_html_dom.php";

//vado a pescarmi la tabella
$html = file_get_html('https://www.rio2016.com/en/torch-relay-route');
$tabella = $html->find("table[class=schedule-torchbeares-list]", 0);

$json_dati = array();

if (!empty($tabella)){
    /*
     * codice di esempio
     *
     * <table class="schedule-torchbeares-list tablesorter" data-sort="cities">
                        <thead style="display: none;">
                            <tr>
                                <th class="schedule-torchbeares-list__th schedule-torchbeares-list__date header"></th>
                                <th class="schedule-torchbeares-list__th schedule-torchbeares-list__city header"></th>
                                <th class="schedule-torchbeares-list__th schedule-torchbeares-list__uf header"></th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="schedule-torchbeares-list__date">
                                        <a href="/en/torchbearers-schedule-brasilia-df-2016-05-03">
                                            <span class="schedule-torchbeares-list__date--bullet">•</span>
                                            <span class="schedule-torchbeares-list__date--icon"></span>
                                            <span class="schedule-torchbeares-list__date--days-invisible">05/03</span>
                                            <span class="schedule-torchbeares-list__date--days">May 03</span>

                                        </a>
                                    </td>
                                    <td class="schedule-torchbeares-list__city">
                                        <a href="/en/torchbearers-schedule-brasilia-df-2016-05-03">Brasília</a>
                                    </td>
                                    <td class="schedule-torchbeares-list__uf">
                                        <a href="/en/torchbearers-schedule-brasilia-df-2016-05-03">DF</a>
                                    </td>
                                </tr>
     *
     *
     */

    foreach($tabella->find("tr") as $tr){
        $json_riga = array();

        //print_r($tr);

        //controllo se sono nell'intestazione oppure no
        if($tr->childNodes(0)->tag == "td"){
            //ora prendo i valori dei td

            //per la data me la formatto stile db
            $data = trim($tr->childNodes(0)->childNodes(0)->childNodes(2)->plaintext);
            $data = explode("/", $data);
            $data = "2016-".$data[0]."-".$data[1];
            $json_riga["data_citta"] = trim($data);

            //il resto è facile
            $json_riga["nome_citta"] = trim($tr->childNodes(1)->plaintext);
            $json_riga["url_citta"] = $tr->childNodes(1)->find("a", 0)->href;

            //aggiungo all'array coi dati
            $json_dati[] = $json_riga;
        }
    }
}

echo json_encode($json_dati);