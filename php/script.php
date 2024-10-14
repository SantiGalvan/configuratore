<?php

require_once '../database/connection_db.php';
require_once '../database/fetch_data.php';
require_once '../database/db_keys.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Includi il file autoload di Composer
require '../vendor/autoload.php';

$name = $_POST['name'] ?? null;
$lastname = $_POST['lastname'] ?? null;
$postal_address = $_POST['cap'] ?? null;
$email = $_POST['email'] ?? null;
$tax_code = $_POST['codice_fiscale'] ?? null;
$vat_number = $_POST['piva'] ?? null;
$total_price = $_POST['hidden-price'] ?? null;

// Inizio la transazione
$conn->begin_transaction();

try {
    // Inserisci i dati dell'utente nel DB
    $sql = "INSERT INTO `users` (name, lastname, email, cap, codice_fiscale, piva) VALUES (?, ?, ?, ?, ?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("ssssss", $name, $lastname, $email, $postal_address, $tax_code, $vat_number);
    $statement->execute();

    // Cerco l'utente appena creato
    $sql = "SELECT * FROM `users` WHERE `email`= ? ";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        throw new Exception("Utente non trovato dopo l'inserimento.");
    }

    // Inserisci i dati dell'ordine
    $sql = "INSERT INTO `orders` (id_user, id_product, total_price) VALUES (?, ?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param("iid", $user['id'], $product['id'], $total_price);
    $statement->execute();

    // Se tutto va bene, inserisco tutto nel DB
    $conn->commit();
} catch (Exception $err) {

    // Se c'è un errore, rollback
    $conn->rollback();
    echo $err->getMessage();
    die();
}

$mail = new PHPMailer(true);

// Invio email di conferma
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'santiagogalvancolorado@gmail.com';
    $mail->Password = PASSWORD_EMAIL;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('santiagogalvancolorado@gmail.com', 'Santiago Galvan');
    $mail->addAddress($user['email']);

    $mail->isHTML(true);
    $mail->Subject = 'Conferma acquisto';
    $mail->Body    = "
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .container {
                    width: 80%;
                    margin: auto;
                    margin: 20px auto;
                }

                .email-content {
                    background-color: #ced4da;
                    border-radius: 20px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 40px;
                }

                h1 {
                    color: #333;
                    text-align: center;
                }

                p {
                    font-size: 16px;
                    line-height: 1.5;
                    color: #555;
                }
            </style>
        </head>

        <body>
            <div class='container'>
                <div class='email-content'>
                    <h1>Conferma Acquisto</h1>
                    <p>Salve {$user['name']} {$user['lastname']} .</p>
                    <p>Le inviamo questa email per confermarle l'acquisto della <strong>{$product['name']}</strong> al prezzo di: <strong>{$total_price}€</strong>.</p>
                </div>
            </div>
        </body>

    </html>
    ";

    $mail->send();
    header('Location: ../completed.php');
} catch (Exception $err) {
    echo $err->getMessage();
    die();
}
