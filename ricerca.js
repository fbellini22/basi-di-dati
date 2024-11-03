$(document).ready(function() {
  // Gestisci l'invio del modulo di ricerca
  $('#search-form').submit(function(event) {
    event.preventDefault(); // Evita il comportamento predefinito del modulo

    // Ottieni il termine di ricerca
    var searchTerm = $('#search-input').val();

    // Effettua una richiesta AJAX per ottenere i risultati della ricerca
    $.post('ricerca.php', { search: searchTerm }, function(response) {
      if (response.trim() === '') {
        // Nessun risultato trovato
        alert('Nessun risultato trovato per il termine di ricerca: ' + searchTerm);
      } else {
        // Aggiorna il contenuto della sezione "search-result" con i risultati della ricerca
        $('#search-result').html(response);
      }
    }).fail(function() {
      // Gestisci l'errore della richiesta AJAX
      alert('Si è verificato un errore durante la ricerca.');
    });
  });
});
