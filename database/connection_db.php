<?php

require_once __DIR__ . '/db_keys.php';

// Connessione/Connect e verifica della connessione
try {
    $conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}
