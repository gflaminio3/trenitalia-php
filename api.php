<?php

// Timezone per evitare problemi su alcune installazioni
date_default_timezone_set('Europe/Rome');

function getStationDatabase() {
    return file_get_contents('data/viaggiaTrenoStations.json');
}

function findStationCode($name) {
    return json_decode(file_get_contents('data/viaggiaTrenoStations.json'), TRUE)[strtoupper($name)];
}

function searchSolution($fromID, $toID, $date) {
    // Faccio questo a causa delle incongruenze delle API di ViaggiaTreno
    $fromID = str_replace('S', '', $fromID);
    $toID = str_replace('S', '', $toID);

    // Se non si imposta il parametro, si prenderà la data di oggi come riferimento
    if($date == NULL)
        $date = date('Y-m-d\TH:i:s', time());    

    $data = json_decode(file_get_contents("http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/soluzioniViaggioNew/$fromID/$toID/$date"), TRUE)['soluzioni'];
    foreach ($data as $solutions) {
        $trains[] = [
            'duration' => $solutions['durata'],
            'from' => $solutions['vehicles']
        ];
    }
    return json_encode($trains);
}

function getStationBoard($id) {
    $today = rawurlencode(date('D M d Y H:i:s', time()));

    $fromStation = json_decode(file_get_contents('http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/partenze/' . $id . '/' . $today), TRUE);
    foreach ($fromStation as $train) {
        $board['departures'][] = [
            'name' => $train['compNumeroTreno'],
            'type' => $train['compTipologiaTreno'],
            'location' => $train['destinazione'],
            'time' => $train['orarioPartenza']
        ];
    }

    $fromStation = json_decode(file_get_contents('http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/arrivi/' . $id . '/' . $today), TRUE);
    foreach ($fromStation as $train) {
        $board['arrivals'][] = [
            'name' => $train['compNumeroTreno'],
            'type' => $train['compTipologiaTreno'],
            'location' => $train['origine'],
            'time' => $train['orarioArrivo']
        ];
    }

    return json_encode($board);
}