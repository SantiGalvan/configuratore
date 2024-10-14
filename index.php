<?php
// Includi la creazione del DB
require_once __DIR__ . '/database/create_db.php';

// Includi la connessione al DB
require_once __DIR__ . '/database/connection_db.php';

// Includi l caricamento dei dati
require_once __DIR__ . '/database/fetch_data.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuratore</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <!-- JavaScript -->
    <script defer src="js/script.js"></script>
</head>

<body>

    <main>

        <div class="container">

            <h1 class="text-center mt-4">Configuratore</h1>

            <div class="row my-4">

                <!-- Prodotto -->
                <div class="col-8">

                    <div class="card p-4 text-center">
                        <h2 class="mt-2 mb-4"><?= $product['name'] ?></h2>
                        <p><strong>Descrizione prodotto: </strong><?= $product['description'] ?></p>
                        <p><strong>Preso base: </strong><span id="base-price"><?= $product['price'] ?></span>€</p>

                        <form id="product-form">
                            <div class="row g-4 text-start">

                                <!-- Numero di utenti -->
                                <div class="col">
                                    <label class="form-label label mt-3 fs-4" for="number-users">Numero di utenti<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                    <select name="number-users" id="number-users" class="form-select">
                                        <!-- <option selected>Scegli un'opzione</option> -->

                                        <?php if (isset($number_of_users)) : ?>

                                            <?php foreach ($number_of_users as $user) :  ?>

                                                <option value="<?= $user['price'] ?>"><?= $user['name'] ?> - <?= $user['price'] ?>€</option>

                                            <?php endforeach ?>

                                        <?php endif ?>

                                    </select>
                                </div>

                                <!-- Numero di sedi -->
                                <div class="col">
                                    <label class="form-label label mt-3 fs-4" for="number-locations">Numero di sedi<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                    <select name="number-locations" id="number-locations" class="form-select">
                                        <!-- <option selected>Scegli un'opzione</option> -->

                                        <?php if (isset($number_of_locations)) : ?>

                                            <?php foreach ($number_of_locations as $location) :  ?>

                                                <option value="<?= $location['price'] ?>"><?= $location['name'] ?> - <?= $location['price'] ?>€</option>

                                            <?php endforeach ?>

                                        <?php endif ?>

                                    </select>
                                </div>

                                <!-- Programmi -->
                                <div class="col-12">

                                    <p class="text-center fs-4">Scegli i programmi<span class="text-danger"><strong><sup>*</sup></strong></span></p>

                                    <div class="d-flex align-items-center justify-content-center p-4 pt-2">


                                        <?php if (isset($programs)) : ?>

                                            <?php foreach ($programs as $program) :  ?>

                                                <input class="form-check-input form-inputs" name="programs[]" type="checkbox" value="<?= $program['price'] ?>" id="program-<?= $program['id'] ?>">
                                                <label class="form-check-label ms-2 me-4" for="program-<?= $program['id'] ?>">
                                                    <?= $program['name'] ?> - <?= $program['price'] ?>€
                                                </label>

                                            <?php endforeach ?>

                                        <?php endif ?>

                                    </div>
                                </div>

                                <!-- Durata abbonamento -->
                                <div class="col-12">

                                    <p class="text-center fs-4">Scegli la durata dell'abbonamento<span class="text-danger"><strong><sup>*</sup></strong></span></p>

                                    <div class="d-flex gap-4 align-items-center justify-content-center p-4 pt-2">

                                        <?php if (isset($subscriptions)) : ?>

                                            <?php foreach ($subscriptions as $subscription) :  ?>

                                                <div class="form-check">
                                                    <input class="form-check-input form-inputs" name="subscriptions[]" type="radio" name="subscription" value="<?= $subscription['price'] ?>" id="subscription-<?= $subscription['id'] ?>">
                                                    <label class="form-check-label" for="subscription-<?= $subscription['id'] ?>">
                                                        <?= $subscription['type'] ?> - <?= $subscription['price'] ?>€
                                                    </label>
                                                </div>

                                            <?php endforeach ?>

                                        <?php endif ?>

                                    </div>
                                </div>

                            </div>

                            <p class="my-2 text-center">I campi contrassegnati con <span class="text-danger"><strong>*</strong></span> sono obbligatori</p>

                            <button id="order-btn" type="button" class="btn btn-success my-4">Procedi all'ordine</button>

                        </form>
                    </div>

                </div>

                <!-- Riepilogo prodotto -->
                <div class="col-4 h-max">

                    <div class="card p-4 text-center h-100">

                        <h2 class="mt-2 mb-4">Riepilogo Prodotto</h2>


                        <p class="fs-5 mt-4"><strong>Numero di utenti: </strong><br><span id="user"></span></p>
                        <p class="fs-5 mt-4"><strong>Numero di sedi: </strong><br><span id="location"></span></p>
                        <p class="fs-5 mt-4"><strong>Programmi scelti: </strong><br><span id="programs"></span></p>
                        <p class="fs-5 mt-4"><strong>Durata abbonamento: </strong><br><span id="subscription"></span></p>

                        <p class="fs-3 mt-4">Prezzo totale: <span id="total-price"><?= $product['price'] ?></span>€</p>

                    </div>

                </div>

            </div>

        </div>

        <!-- Modale -->
        <div id="modal" class="position-fixed top-0 bottom-0 end-0 star-0 data-modal h-100 w-100 d-flex align-items-center justify-content-center d-none">

            <div class="card p-4 w-50">

                <form action="php/script.php" method="POST">
                    <div class="row g-4">
                        <h3 class="text-center my-3">Inserisci i tuoi dati di acquisto</h3>

                        <!-- Nome -->
                        <div class="col-5">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="name" type="text" class="form-control" id="name">
                            </div>
                        </div>

                        <!-- Cognome -->
                        <div class="col-5">
                            <div class="mb-3">
                                <label for="lastname" class="form-label">Cognome<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="lastname" type="text" class="form-control" id="lastname">
                            </div>
                        </div>

                        <!-- CAP -->
                        <div class="col-2">
                            <div class="mb-3">
                                <label for="cap" class="form-label">CAP<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="cap" type="text" class="form-control" id="cap">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="email" type="email" class="form-control" id="email">
                            </div>
                        </div>

                        <!-- Codice fiscale -->
                        <div class="col">
                            <div class="mb-3">
                                <label for="codice_fiscale" class="form-label">Codice fiscale<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="codice_fiscale" type="text" class="form-control" id="codice_fiscale">
                            </div>
                        </div>

                        <!-- P.IVA -->
                        <div class="col">
                            <div class="mb-3">
                                <label for="piva" class="form-label">P.IVA<span class="text-danger"><strong><sup>*</sup></strong></span></label>
                                <input name="piva" type="text" class="form-control" id="piva">
                            </div>
                        </div>

                        <!-- Campo nascosto del prezzo totale -->
                        <input type="hidden" id="hidden-price" name="hidden-price" value="">

                        <p class="mb-4 text-center">I campi contrassegnati con <span class="text-danger"><strong>*</strong></span> sono obbligatori</p>

                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <button class="btn btn-success">Invia</button>
                            <button id="modal-close" type="reset" class="btn btn-danger">Chiudi</button>
                        </div>


                    </div>

                </form>

            </div>
        </div>

    </main>

</body>

</html>