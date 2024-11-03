$(document).ready(function() {
  $('#login').submit(function(e) {
    e.preventDefault(); 

    var username = $('#logusername').val(); 
    var password = $('#logpw').val();

    // invio richiesta di login
    $.ajax({
      url: 'login.php', 
      method: 'POST',
      data: {
        Username: username, 
        Pw: password 
      },
      success: function(response) {
        if (response == 1) {
          // Login funziona, vado a pagina home
          window.location.href = 'home.php'; 
        } else {
          // Login fallito
          alert('Login fallito, riprova cambiando username e/o password');
        }
      },
      error: function() {
        alert('Error occurred while processing your request.');
      }
    });
  });
});
