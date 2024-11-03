$('document').ready(function () {
    // Codice JavaScript per la validazione del form e la gestione dell'invio del modulo
    $("#formcreablog").validate({
        rules: {
            titoloblog: {
                required: true,
                maxlength: 50,
            },
            descrizioneblog: {
                required: true,
                maxlength: 100,
            },
            blogimg: {
                required: true,
            }
        },
        messages: {
            titoloblog: {
                required: "Dai un titolo al tuo blog!",
                maxlength: "Questo titolo è troppo lungo!",
            },
            descrizioneblog: {
                required: "Inserisci una descrizione!",
                maxlength: "Questa descrizione è troppo lunga!",
            },
            blogimg: {
                required: "Scegli un'icona!",
            }
        },
        submitHandler: creaBlog
    });

    function creaBlog() {
        // Codice per l'invio del form tramite AJAX
        var form = $('#formcreablog')[0];
        var data = new FormData(form);

        $.ajax({
            type: 'POST',
            url: "creazioneblog.php",
            data: data,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,

            success: function(response) {
                if (response === "success") {
                    window.location.assign("profilo.php");
                } else if (response === "limit_exceeded") {
                    $('.error').text('Puoi creare al massimo 15 blog.');
                } else {
                    $('.error').text('Si è verificato un errore durante la creazione del blog.');
                }
            }
        });
    }
});