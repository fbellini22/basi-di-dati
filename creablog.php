<?php
require_once('connect.php');
session_start();
$query = $conn->prepare('SELECT DISTINCT nome_categoria FROM sottocategoria');
$query->execute();
$resultset =  $query->get_result();

for ($i = 0; $i < $resultset->num_rows; $i++) {
    $A[$i] = $resultset->fetch_assoc();
}

$query->close();

?>

<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="sottocategorie.js"></script>
    <script src='gestroreAnnulla.js'></script>
    <!--<script src='gestoreCreaBlogForm.js'></script>-->

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
        <h2 id="creatuoblog"> Crea il tuo blog! </h2>
        <div id="creablog" name="creablog">
            <form id="formcreablog" name="formcreablog" method="POST" action="">
                <input type="text" placeholder="Inserisci un titolo" id="titoloblog" name="titoloblog" maxlength="50" required>
                <br />
                <br />
                <textarea placeholder="Descrivi il tuo blog!" id="descrizioneblog" name="descrizioneblog" rows="10" cols="80" maxlength="150" required style="width: 400px; height:180px"></textarea>
                <br />
                <br />
                <input id="blogimg" name="blogimg" type="file" required accept="image/*">
                <br />
                <br />
                <input type="text" placeholder="Inserisci il coautore (opzionale)" id="coautoreblog" name="coautoreblog">
                <br />
                <br />
                <div id="filtricreablog">
                    <select id="selezionacat" name="selezionacat">
                        <option id="opzionebase" name="opzionebase" value="none" selected>Categoria</option>

                        <?php
                        foreach ($A as $i) {
                            $value = $i['nome_categoria'];
                            echo "<option value='$value'> $value </option>";
                        }
                        ?>
                    </select>

                    <select id="selezionasottocat" name="selezionasottocat">
                        <option id="opzionebasesottocat" name="opzionebasesottocat" value="none" selected>Sottocategoria</option>
                    </select>
                </div>
                <br />
                <button type="submit" id="crea" name="crea" class="submitcrea">Crea</button>
                <button type="button" id="annulla" name="annulla" class="annullacreablog">Annulla</button>
                <p class="error"></p>
            </form>
        </div>
    </div>

    <script>
        $('document').ready(function() {
            // Codice JavaScript per la validazione del form e la gestione dell'invio del modulo
            $("#formcreablog").validate({
                rules: {
                    titoloblog: {
                        required: true,
                        maxlength: 50,
                    },
                    descrizioneblog: {
                        required: true,
                        maxlength: 150,
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
                    },
                    selezionacat: {
                        required: "Seleziona una sottocategoria.",
                    },
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
    </script>
</body>

</html>