// Bottone del form
const orderBtn = document.getElementById('order-btn');
// Prezzo base del prodotto
const basePrice = parseFloat(document.getElementById('base-price').textContent);
// Select degli utenti
const inputNumberOfUsers = document.getElementById('number-users');
// Select delle sedi
const inputNumberOfLocations = document.getElementById('number-locations');
// Input dei programmi
const inputPrograms = document.querySelectorAll('input[name="programs[]"]');
// Input degli abbonamenti
const inputSubscriptions = document.querySelectorAll('input[name="subscriptions[]"]');
// Span del prezzo totale
const totalPrice = document.getElementById('total-price');
// Modale
const modal = document.getElementById('modal');
// Bottone chiusura modale
const modalClose = document.getElementById('modal-close');
// Input del prezzo nascosta
const hiddenPrice = document.getElementById('hidden-price');
// Span degli utenti
const totUser = document.getElementById('user');
// Span delle sedi
const totLocation = document.getElementById('location');
// Span degi programmi
const totPrograms = document.getElementById('programs');
// Span della durata dell'abbonamento
const totSubscription = document.getElementById('subscription');
// Variabile prezzo dei programmi
let programPrice = 0;
// Variabile prezzo dell'abbonamento
let subscriptionPrice = 0;

//? Funzioni
const updatePrice = () => {

    // Valore numero di utenti
    const numberOfUsers = parseFloat(inputNumberOfUsers.value);

    // Valore numero di sedi
    const numberOfLocations = parseFloat(inputNumberOfLocations.value);

    // Totale del prezzo
    let total = basePrice + numberOfUsers + numberOfLocations + programPrice + subscriptionPrice;

    // Inserimento del prezzo nel DOM
    totalPrice.innerText = total;

}

// Apertura modale
orderBtn.addEventListener('click', e => {
    e.preventDefault();
    modal.classList.remove('d-none');
    hiddenPrice.value = parseFloat(totalPrice.textContent).toFixed(2);
});

// Chiusura modale
modalClose.addEventListener('click', () => {
    modal.classList.add('d-none');
    hiddenPrice.value = "";
});

// Ciclo su tutti gli input dei programmi
let selectedPrograms = [];
inputPrograms.forEach(input => {

    input.addEventListener('click', e => {

        const labelProgram = document.querySelector(`label[for="${input.id}"]`).textContent;

        if (e.target.checked === true) {

            programPrice += parseFloat(e.target.value);
            selectedPrograms.push(labelProgram);
            updatePrice();

        } else if (e.target.checked === false) {

            programPrice -= parseFloat(e.target.value);
            selectedPrograms = selectedPrograms.filter(list => list !== labelProgram);
            updatePrice();

        }

        let programList = `</ul>`;

        selectedPrograms.forEach(program => {
            programList += `<li>${program}</li>`;
        });

        programList += `</ul>`;

        totPrograms.innerHTML = programList;
    })

});

// Ciclo su tutti gli input degli abbonamenti
inputSubscriptions.forEach(input => {
    input.addEventListener('change', e => {

        subscriptionPrice = parseFloat(e.target.value);

        const labelSubscription = document.querySelector(`label[for="${input.id}"]`).textContent;

        totSubscription.innerText = labelSubscription;

        updatePrice();
    });

});

// Evento sulla select degli utenti
inputNumberOfUsers.addEventListener('change', () => {

    const userSelected = inputNumberOfUsers.selectedOptions[0].text;

    totUser.innerText = userSelected;

    updatePrice();
});

// Evento sulla select delle sedi
inputNumberOfLocations.addEventListener('change', () => {

    const locationSelected = inputNumberOfLocations.selectedOptions[0].text;

    totLocation.innerText = locationSelected;

    updatePrice();
});
