$('document').ready(function() {
  // Codice JavaScript per la validazione del form e la gestione dell'invio del modulo
  $("#nuovopost").validate({
      rules: {
          titolopost: {
              required: true,
              maxlength: 50,
          },
          testopost: {
              required: true,
              maxlength: 500,
          },
          postimg: {
              required: true,
          }
      },
      messages: {
          titolopost: {
              required: "Dai un titolo al tuo post!",
              maxlength: "Questo titolo è troppo lungo!",
          },
          testopost: {
              required: "Scrivi il testo del tuo post!",
              maxlength: "Questo testo è troppo lungo!",
          },
          postimg: {
              required: "Scegli un'immagine!",
          }
      },
      submitHandler: creaPost
  });

  function creaPost() {
      // Codice per l'invio del form tramite AJAX
      var form = $('#nuovopost')[0];
      var data = new FormData(form);
      var id = window.location.search.substring(1);

      $.ajax({
          type: 'POST',
          url: "creazionepost.php?" + id,
          data: data,
          enctype: 'multipart/form-data',
          processData: false,
          contentType: false,
          cache: false,

          success: function(response) {
              if (response === "success") {
                  window.location.assign("post.php");
              } else {
                  $('.error').text('Si è verificato un errore durante la creazione del post.');
              }
          },
          error: function() {
              $('.error').text('Si è verificato un errore durante la richiesta.');
          }
      });
  }
});

