<?php

require_once __DIR__ . '/connection_db.php';

// Prodotto
try {

    // Query
    $sql = "SELECT * FROM `products`";

    $result = $conn->query($sql);

    $product = $result->fetch_assoc();
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

// Numero di sedi
$number_of_locations = [];
try {

    // Query
    $sql = "SELECT * FROM `number_of_locations`";

    $result = $conn->query($sql);

    while ($row = $result->fetch_array()) {
        $number_of_locations[] = $row;
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

// Numero di utenti
$number_of_users = [];
try {

    // Query
    $sql = "SELECT * FROM `number_of_users`";

    $result = $conn->query($sql);

    while ($row = $result->fetch_array()) {
        $number_of_users[] = $row;
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

// Tipo di abbonamento
$subscriptions = [];
try {

    // Query
    $sql = "SELECT * FROM `subscriptions`";

    $result = $conn->query($sql);

    while ($row = $result->fetch_array()) {
        $subscriptions[] = $row;
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

// Programmi
$programs = [];
try {

    // Query
    $sql = "SELECT * FROM `programs`";

    $result = $conn->query($sql);

    while ($row = $result->fetch_array()) {
        $programs[] = $row;
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}
