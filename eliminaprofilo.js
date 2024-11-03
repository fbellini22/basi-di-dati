document.getElementById("delete-profile-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Blocca l'invio del modulo

    // Mostra un messaggio di conferma e chiedi all'utente se desidera eliminare il profilo
    var confirmDelete = confirm("Sei sicuro di voler eliminare il tuo profilo? Questa azione è irreversibile.");

    if (confirmDelete) {
        // Se l'utente conferma, reindirizza alla pagina "index.php"
        window.location.href = "index.php";
    }
});
