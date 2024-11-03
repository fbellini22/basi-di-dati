<?php
require_once('connect.php');
session_start();
$blog_id = $_GET['blog_id'];

?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src='gestoreAnnullapost.js'></script>

    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="home.css" />


    <title>CrowdConnect</title>
</head>

<body>
    <header class="header">
        <div class="left-section">
            <a href="home.php" class="logo">CrowdConnect</a>
        </div>
        <nav class="navbar">
            <a href="home.php"><i class="fa-solid fa-house"></i> Home</a>
            <a href="profilo.php"><i class="fa-solid fa-pen-fancy"></i>Profilo</a>
            <a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </header>
    <div class="container">
        <h2 id="creaunpost">Crea un post</h2>
        <div id="creapost" name="creapost">
            <form id="formcreapost" name="formcreapost" method="POST" action="">
                <input type="text" placeholder="Inserisci un titolo" id="titolopost" name="titolopost" maxlength="30" required>
                <br />
                <br />
                <textarea placeholder="Scrivi il testo del post" id="testopost" name="testopost" rows="10" cols="80" maxlength="500" required style='height:185px; width: 450px;'></textarea>
                <br />
                <br />
                <input id="postimg" name="postimg" type="file" required accept="image/*">
                <br />
                <br />
                <button type="submit" id="creaPost" name="crea" class="submitcrea" style='     
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            background-color: purple;
            border: none;
            border-radius: 5px;
            cursor: pointer;'>Crea</button>
                <button type="button" id="annulla" name="annulla" class="annullacreapost" style='
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: #ffffff;
            background-color: purple;
            border: none;
            border-radius: 5px;
            cursor: pointer;'>Annulla</button>
                <p class="error"></p>
            </form>
        </div>
    </div>
    <script>
        $('document').ready(function() {
            // Codice JavaScript per la validazione del form e la gestione dell'invio del modulo
            $("#formcreapost").validate({
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
                var form = $('#formcreapost')[0];
                var data = new FormData(form);

                // Utilizza la variabile $blog_id invece di ottenere l'id dall'URL corrente
                var id = "<?php echo $blog_id; ?>";

                $.ajax({
                    type: 'POST',
                    url: "creazionepost.php?blog_id=" + id, // Aggiungi il parametro blog_id nell'URL
                    data: data,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    cache: false,

                    success: function(response) {
                        if (response === "success") {
                            window.location.assign("post.php?id=" + id); // Reindirizza con l'id corretto
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
    </script>
</body>

</html>