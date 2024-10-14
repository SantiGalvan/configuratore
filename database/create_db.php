<?php

require_once __DIR__ . '/db_keys.php';

// Connessione/Connect e verifica della connessione
try {
    $conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD);
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

//? Creazione DB
try {
    // Controllo se esiste già un DB con questo nome
    $sql = "SHOW DATABASES LIKE '" . DB_NAME . "'";
    $db = $conn->query($sql);

    // Se il DB non esiste
    if ($db->num_rows === 0) {

        // Creo il DB
        $sql = "CREATE DATABASE " . DB_NAME;
        $conn->query($sql);
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

// Seleziono il DB da usare
$conn->select_db(DB_NAME);

// Funzione che controlla se una tabella è già presente nel DB
function tableExists($conn, $table)
{
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result && $result->num_rows > 0;
}

//? Creazione delle tabelle 
try {

    // Se non esiste la tabella prodotti (products)
    if (!tableExists($conn, 'products')) {

        // Creazione tabella dei prodotti (products)
        $sql =  "CREATE TABLE products 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL)";
        $conn->query($sql);

        // Inserimento del record
        $sql = "INSERT INTO products (name, description, price) VALUES 
        ('Licenza Software', 'Licenza per uso del software', 100.00)";
        $conn->query($sql);
    }

    // Se non esiste la tabella numero di sedi (number_of_locations)
    if (!tableExists($conn, 'number_of_locations')) {

        // Creazione tabella delle numero di sedi (number_of_locations)
        $sql = "CREATE TABLE number_of_locations 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        id_product INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        CONSTRAINT number_of_locations_product FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE)";
        $conn->query($sql);

        // Inserimento dei record
        $sql = "INSERT INTO number_of_locations (id_product, name, price) VALUES 
        (1,'1 sede', 0.00),
        (1,'2 sedi', 50.00),
        (1,'5 sedi', 100.00),
        (1,'10 sedi', 150.00)";
        $conn->query($sql);
    }

    // Se non esiste la tabella numero di utenti (number_of_users)
    if (!tableExists($conn, 'number_of_users')) {

        // Creazione tabella delle numero di utenti (number_of_users)
        $sql = "CREATE TABLE number_of_users 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        id_product INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        CONSTRAINT number_of_users_product FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE)";
        $conn->query($sql);

        // Inserimento dei record
        $sql = "INSERT INTO number_of_users (id_product, name, price) VALUES 
        (1,'1 utente', 0.00),
        (1,'5 utenti', 5.00),
        (1,'10 utenti', 10.00),
        (1,'50 utenti', 40.00),
        (1,'Illimitato', 100.00)";
        $conn->query($sql);
    }

    // Se non esiste la tabella numero di utenti (subscriptions)
    if (!tableExists($conn, 'subscriptions')) {

        // Creazione tabella delle numero di utenti (subscriptions)
        $sql = "CREATE TABLE subscriptions 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        id_product INT NOT NULL,
        type VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        CONSTRAINT subscriptions_product FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE)";
        $conn->query($sql);

        // Inserimento dei record
        $sql = "INSERT INTO subscriptions (id_product, type, price) VALUES 
        (1,'Mensile', 0.00),
        (1,'Semestrale', 500.00),
        (1,'Annuale', 800.00)";
        $conn->query($sql);
    }


    // Se non esiste la tabella utenti (users)
    if (!tableExists($conn, 'users')) {

        // Creazione tabella degli utenti (users)
        $sql = "CREATE TABLE users 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        cap VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        codice_fiscale VARCHAR(16) NOT NULL UNIQUE,
        piva VARCHAR(13) NOT NULL UNIQUE)";
        $conn->query($sql);
    }

    // Se non esiste la tabella degli ordini (orders)
    if (!tableExists($conn, 'orders')) {

        // Creazione tabella degli ordini (ordes)
        $sql = "CREATE TABLE orders 
        (id INT AUTO_INCREMENT PRIMARY KEY,
        id_user INT,
        id_product INT,
        total_price DECIMAL(10, 2) NOT NULL,
        date DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_user) REFERENCES users(id),
        FOREIGN KEY (id_product) REFERENCES products(id))";
        $conn->query($sql);
    }

    // Creazione tabella degli programmi (programs)
    if (!tableExists($conn, 'programs')) {
        $sql = "CREATE TABLE programs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            price DECIMAL(10, 2) NOT NULL)";
        $conn->query($sql);

        // Inserimento dati di esempio per 'programs'
        $sql = "INSERT INTO programs (name, price) VALUES 
        ('Programma A', 10.00), 
        ('Programma B', 5.00), 
        ('Programma C', 20.00)";
        $conn->query($sql);
    }

    // Creazione tabella ponte 'prodotto_programma' se non esiste già
    if (!tableExists($conn, 'product_programma')) {
        $sql = "CREATE TABLE product_programma (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_product INT NOT NULL,
            id_program INT NOT NULL,
            CONSTRAINT fk_product FOREIGN KEY (id_product) REFERENCES products(id) ON DELETE CASCADE,
            CONSTRAINT fk_program FOREIGN KEY (id_program) REFERENCES programs(id) ON DELETE CASCADE)";
        $conn->query($sql);

        // Inserimento dati di esempio per 'product_programma'
        $sql = "INSERT INTO product_programma (id_product, id_program) VALUES 
        (1, 1), 
        (1, 2), 
        (1, 3)";
        $conn->query($sql);
    }
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}

$conn->close();
