$(document).ready(function() {
  function Registrati() {
    var data = {
      Username: $('#Usernamereg').val(),
      Mail: $('#Mail').val(),
      Nome: $('#Nome').val(),
      Cognome: $('#Cognome').val(),
      Pw: $('#Pw').val(),
      Pwc: $('#Pwc') .val()
    };

    $.ajax({
      type: 'POST',
      url: 'register.php',
      data: data,

      success: function(response) {
        if (response === "Registrazione avvenuta con successo.") {
          alert('Registrazione avvenuta con successo.');
          window.location.href = 'home.php';
        } else {
          alert('Errore durante la registrazione: ' + response);
          $('.error').text(response);
          window.location.href = 'index.php';
        }
      }      
    });
  }

  $('#register').submit(function(event) {
    event.preventDefault();
    Registrati();
  });
});