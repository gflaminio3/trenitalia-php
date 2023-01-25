# trenitalia-php
Progetto completamente scritto in PHP per il parsing dei dati di ViaggiaTreno

# Requisiti
1. PHP 7.x,8.x
2. php-curl
3. php-json

# Funzioni
| Nome | Input | Descrizione | Output |
| --- | --- | --- | --- |
| findStationCode() | string | Trova il codice viaggiatreno della stazione richiesta | string
| getStationDatabase() | / | Stampa il Database delle stazioni | json
| searchSolution() | string,string | Dati in input i valori di due stazioni, stampa le soluzioni di viaggio per il giorno corrente | json
| getStationBoard() | string | Dato in input il codice di una stazione, stampa il tabellone partenze/arrivi più recente | json


Script di base
---
<?php
require 'trenitalia-php/api.php';

// Funzioni...
---
