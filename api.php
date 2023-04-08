<?php

// Setting timezone
date_default_timezone_set('Europe/Rome');

/**
 * Returns the contents of the 'viaggiaTrenoStations.json' file.
 *
 * @return string The contents of the file as a string.
 */
function getStationDatabase()
{
    return file_get_contents('data/viaggiaTrenoStations.json');
}

/**
 * Returns the station code for a given station name, as found in the 'viaggiaTrenoStations.json' file.
 *
 * @param string $name The name of the station to find the code for.
 * @return string The station code.
 */
function findStationCode(string $name): string
{
    // Decode the contents of the 'viaggiaTrenoStations.json' file into an associative array.
    $stations = json_decode(getStationDatabase(), TRUE);

    // Use strtoupper() to ensure that the provided station name is in all caps, then return the corresponding station code.
    return $stations[strtoupper($name)];
}

/**
 * Searches for train departures or arrivals at a given station, as found on the ViaggiaTreno website.
 *
 * @param string $id The ID of the station to search for.
 * @param bool $isDeparture Whether to search for departures (true) or arrivals (false).
 * @return array An array of trains that match the search criteria.
 */
function searchStation(string $id, bool $isDeparture): array
{
    // Determine whether to search for departures or arrivals based on the $isDeparture parameter.
    $endpoint = $isDeparture ? 'partenze' : 'arrivi';

    // Construct the URL for the API request using the provided $id and current timestamp values.
    $url = "http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/$endpoint/$id/" . time();

    // Make an API request to the URL and decode the resulting JSON data into an associative array.
    $data = json_decode(file_get_contents($url), true);

    // Initialize an empty array to hold the results of the search.
    $result = [];

    // Loop through each train returned by the API and extract relevant information into a new array.
    foreach ($data as $train) {
        $result[] = [
            'name' => $train['compNumeroTreno'],
            'type' => $train['compTipologiaTreno'],
            'location' => $isDeparture ? $train['destinazione'] : $train['origine'],
            'time' => $isDeparture ? $train['orarioPartenza'] : $train['orarioArrivo']
        ];
    }

    // Return the array of search results.
    return $result;
}

/**
 * Searches for train solutions between two stations, as found on the ViaggiaTreno website.
 *
 * @param string $fromID The ID of the starting station.
 * @param string $toID The ID of the destination station.
 * @param string|null $date The date and time to search for solutions (in ISO 8601 format).
 * @return json Returns a JSON-encoded string of solutions that match the search criteria.
 */
function searchSolution(string $fromID, string $toID, ?string $date = null)
{
    // Remove any 'S' characters from the station IDs due to inconsistencies in the ViaggiaTreno API.
    $fromID = str_replace('S', '', $fromID);
    $toID = str_replace('S', '', $toID);

    // If no date is provided, use the current date and time as a reference point.
    $date = $date ?? date('Y-m-d\TH:i:s');

    // Construct the URL for the API request using the provided $fromID, $toID, and $date values.
    $url = "http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/soluzioniViaggioNew/$fromID/$toID/$date";

    // Make an API request to the URL and decode the resulting JSON data into an associative array.
    $data = json_decode(file_get_contents($url), true)['soluzioni'];

    // Initialize an empty array to hold the results of the search.
    $result = [];

    // Loop through each solution returned by the API and extract relevant information into a new array.
    foreach ($data as $solution) {
        $result[] = [
            'duration' => $solution['durata'],
            'from' => $solution['vehicles']
        ];
    }

    // Return the array of search results.
    return json_encode($result);
}

/**
 * Fetches train schedule information for a given station ID.
 *
 * @param string $id The ID of the station to fetch data for.
 * @return string Returns a JSON-encoded string containing train departure and arrival information.
 */
function getStationBoard($id)
{
    // Encode the current date and time for use in the API URLs.
    $today = rawurlencode(date('D M d Y H:i:s', time()));

    // Fetch departure information from the API and add it to the response array.
    $fromStation = json_decode(file_get_contents('http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/partenze/' . $id . '/' . $today), TRUE);
    foreach ($fromStation as $train) {
        $board['departures'][] = [
            'name' => $train['compNumeroTreno'],
            'type' => $train['compTipologiaTreno'],
            'location' => $train['destinazione'],
            'time' => $train['orarioPartenza']
        ];
    }

    // Fetch arrival information from the API and add it to the response array.
    $toStation = json_decode(file_get_contents('http://www.viaggiatreno.it/infomobilita/resteasy/viaggiatreno/arrivi/' . $id . '/' . $today), TRUE);
    foreach ($toStation as $train) {
        $board['arrivals'][] = [
            'name' => $train['compNumeroTreno'],
            'type' => $train['compTipologiaTreno'],
            'location' => $train['origine'],
            'time' => $train['orarioArrivo']
        ];
    }

    // Return the response array as a JSON-encoded string.
    return json_encode($board);
}
