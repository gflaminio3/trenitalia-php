# trenitalia-php
Progetto completamente scritto in PHP per il parsing dei dati di ViaggiaTreno

# Requisiti
1. PHP 7.x oppure 8.x
2. php-curl
3. php-json

# Funzioni
| Nome | Input | Descrizione | Output |
| --- | --- | --- | --- |
| findStationCode() | string | Trova il codice viaggiatreno della stazione richiesta | string
| getStationDatabase() | / | Stampa il Database delle stazioni | json
| searchSolution() | string, string, timestamp or NULL | Dati in input i valori di due stazioni, stampa le soluzioni di viaggio per il giorno corrente | json
| getStationBoard() | string | Dato in input il codice di una stazione, stampa il tabellone partenze/arrivi più recente | json


Script di base
```
<?php
require 'trenitalia-php/api.php';

// Esempio per la stazione di Caserta
$name = 'caserta';
$code = findStationCode($name);
echo 'Code is: ' . $code;

// Recupera il tabellone aggiornato
$board = getStationBoard($code);

// stampa un json con partenze/arrivi
echo $board; 
```
