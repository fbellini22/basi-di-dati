<?php
require_once('connect.php');
session_start();

$codiceblog = isset($_GET['id']) ? $_GET['id'] : null;
$selezionablog = "SELECT * FROM blog_ WHERE codice_blog = $codiceblog";

// Recupera i dettagli del blog di partenza
$blogDetails = null;
if ($result = $conn->query($selezionablog)) {
    $blogDetails = $result->fetch_assoc();
}

// Recupera i post associati al blog
$selezionapost = "SELECT * FROM post WHERE codice_blog = $codiceblog";
$posts = array(); // Array per memorizzare i post

if ($result = $conn->query($selezionapost)) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row; // Aggiungi il post all'array dei post
    }
}

// Verifica se l'utente attualmente loggato è un co-autore del blog
$coautore = false; // Imposta il valore predefinito a false

$queryCoautore = "SELECT * FROM `co-autore` WHERE codice_blog = $codiceblog AND Username = '{$_SESSION['uid']}'";
$resultCoautore = $conn->query($queryCoautore);

if ($resultCoautore && $resultCoautore->num_rows > 0) {
    $coautore = true;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css" />
    <!--<script src='gestoreEliminablog.js'> </script>-->
    <style>
        .dati-blog {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 17vh;
            color: white;
        }

        .dati-blog h5,
        .dati-blog p {
            text-align: center;
            margin-bottom: 10px;
            font-size: 25px;
        }

        .post-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .post {
            background-color: #f2f2f2;
            padding: 15px;
            border-radius: 15px;
            margin: 10px;
            max-width: 340px;
            box-sizing: border-box;
        }

        .post h3 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .post p {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .post img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            max-height: 200px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-top: -20px;
        }

        .button {
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
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        .like-button i {
            font-size: 24px;
            color: #800080;
            border: none;
        }

        .comment-form textarea {
            width: 100%;
            height: 60px;
            padding: 10px;
            resize: vertical;
            border: 4px solid purple;
            border-radius: 4px;
        }

        .comment-form button {
            background-color: purple;
            color: white;
            padding: 1px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }


        .buttons-container {
            display: flex;
            align-items: center;
        }

        .like-form,
        .edit-form {
            margin-right: 10px;
        }

        .edit-button {
            display: inline-block;
            background-color: purple;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .comment p {
            font-size: 12px;
        }

        .elimina-commento-button {
            display: inline;
            margin-left: 5px;
            vertical-align: middle;
            color: #800080;
            cursor: pointer;
        }

        .edit-popup {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        .edit-popup .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }

        .vedi-comments-button {
            background-color: purple;
            color: white;
            padding: 1px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
    <title>CrowdConnect</title>
</head>

<body>
    <header class="header">
        <div class="left-section">
            <a href="home.php" class="logo">CrowdConnect</a>
        </div>
        <nav class="navbar">
            <a href="profilo.php"><i class="fa-solid fa-user"></i> Profilo</a>
            <a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </header>
    <div id="post" class="dati-blog" name="post">
        <?php
        if ($blogDetails) {
            $titolo_blog = $blogDetails['titolo_blog'];
            $creatore = $blogDetails['username'];

            echo "
            <h5>Titolo del Blog: $titolo_blog</h5>
            <p>Creatore: $creatore</p>
            ";
        }
        ?>
    </div>
    <div class="button-container">
        <?php
        // Mostra i pulsanti per la modifica, l'eliminazione e la pubblicazione di un post solo se l'utente è il creatore del blog o un coautore
        if ($blogDetails && (strcasecmp($creatore, $_SESSION['uid']) == 0 || $coautore)) {
            echo '<button id="modifica" class="button" onclick="mostraPopup(' . $codiceblog . ')">Modifica il blog</button>';
            echo '<button id="elimina" class="button" onclick="eliminablog()">Elimina il blog</button>';
            echo '<button id="pubblica" class="button" onclick="location.href=\'creapost.php?blog_id=' . $codiceblog . '\'">Pubblica un post!</button>';
        }
        ?>
    </div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <h2>Modifica Blog</h2>
            <form id="modifica-blog-form" method="POST" action="">
                <label for="nuovo-titolo">Nuovo titolo:</label>
                <input type="text" id="nuovo-titolo" name="nuovo-titolo">

                <label for="nuova-descrizione">Nuova descrizione:</label>
                <input type="text" id="nuova-descrizione" name="nuova-descrizione">

                <label for="nuova-categoria">Nuova categoria:</label>
                <select id="nuova-categoria" name="nuova-categoria">
                    <?php
                    // Query per ottenere le categorie dal database
                    $queryCategorie = "SELECT * FROM categoria";
                    $resultCategorie = $conn->query($queryCategorie);

                    // Ciclo per visualizzare le opzioni delle categorie nel menu a tendina
                    while ($rowCategoria = $resultCategorie->fetch_assoc()) {
                        $nomeCategoria = $rowCategoria['nome_categoria'];
                        echo "<option value='$nomeCategoria'>$nomeCategoria</option>";
                    }
                    ?>
                </select>
                <button type="submit" onclick="salvaModifiche()">Salva modifiche</button>
                <button type="button" onclick="annullaModifiche()">Annulla</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Definizione delle funzioni JavaScript
        function mostraPopup(id) {
            $('#popup').show();
        }

        function chiudiPopup() {
            $('#popup').hide();
        }

        function annullaModifiche() {
            chiudiPopup();
            $('#modifica-blog-form')[0].reset();
        }

        function salvaModifiche() {
            var nuovoTitolo = $('#nuovo-titolo').val();
            var nuovaDescrizione = $('#nuova-descrizione').val();
            var nuovaCategoria = $('#nuova-categoria').val();

            var formData = new FormData();
            formData.append('nuovo-titolo', nuovoTitolo);
            formData.append('nuova-descrizione', nuovaDescrizione);
            formData.append('nuova-categoria', nuovaCategoria);

            var id = <?php echo $codiceblog; ?>;

            $.ajax({
                url: "modifica_blog.php?blog_id=" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    chiudiPopup();
                    $('#modifica-blog-form')[0].reset();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function eliminablog() {
            var urlParams = new URLSearchParams(window.location.search);
            var id_blog = urlParams.get('id'); // Ottieni l'ID del blog dal parametro 'id' nell'URL

            $.ajax({
                type: "POST",
                url: "eliminablog.php",
                data: {
                    id_blog: id_blog
                },
                success: function(response) {
                    alert(response); // Mostra la risposta dal server
                    // Reindirizza alla pagina home dopo l'eliminazione del blog
                    window.location.href = "home.php";
                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
        }
    </script>
    <br>
    <!-- Visualizza i post associati al blog -->
    <div class="post-list">
        <?php
        foreach ($posts as $post) {
            $postID = $post['codice_post'];
            $userID = $_SESSION['uid'];
            $autorePost = $post['username']; // Assumi che l'autore del post sia memorizzato nella colonna "username"

            // Verifica se l'utente ha già messo "Mi piace" al post corrente
            $queryVerifica = "SELECT * FROM reazione WHERE codice_post = $postID AND username = '$userID'";
            $resultVerifica = $conn->query($queryVerifica);

            if ($resultVerifica && $resultVerifica->num_rows > 0) {
                $isLiked = true;
            } else {
                $isLiked = false;
            }
        ?>
            <div class="post">
                <h3><?php echo $post['titolo_post']; ?></h3>
                <p><?php echo $post['testo_post']; ?></p>
                <img src="img/<?php echo $post['immagine_post']; ?>" alt="Immagine del post">

                <div class="buttons-container">
                    <!-- Form per il like del post -->
                    <form class="like-form" data-post-id="<?php echo $post['codice_post']; ?>">
                        <input type="hidden" class="like-status" value="<?php echo $isLiked ? '1' : '0'; ?>">
                        <button type="button" class="like-button" onclick="toggleLike(this)">
                            <?php if ($isLiked) : ?>
                                <i class="fa-solid fa-thumbs-up"></i>
                            <?php else : ?>
                                <i class="fa-regular fa-thumbs-up"></i>
                            <?php endif; ?>
                        </button>
                    </form>
                    <!-- Form per la modifica del post -->
                    <?php if ($autorePost === $userID || $coautore) : ?>
                        <form class="edit-form" action="modifica_post.php" method="post" onsubmit="submitEditForm(event, <?php echo $postID; ?>)">
                            <input type="hidden" name="post_id" value="<?php echo $postID; ?>">
                            <button type="button" class="edit-button" onclick="openEditPopup(<?php echo $postID; ?>)">Modifica post</button>
                        </form>
                    <?php endif; ?>
                </div>
                <br>
                <button class="vedi-comments-button" onclick="toggleComments(this)">
                    Mostra commenti
                </button>

                <div id="comment-section-<?php echo $postID; ?>" class="comment-section" style="display: none;">
                    <!-- Visualizza i commenti -->
                    <?php
                    // Recupera i commenti associati al post corrente
                    $queryCommenti = "SELECT * FROM commenta WHERE CodPost = $postID";
                    $resultCommenti = $conn->query($queryCommenti);

                    while ($commento = $resultCommenti->fetch_assoc()) {
                        $autore = $commento['Username'];
                        $commentoID = $commento['CodComm'];
                    ?>
                        <div class="comment">
                            <p>
                                <strong><?php echo $autore; ?>:</strong> <?php echo $commento['Testo']; ?>
                                <?php if ($autore === $userID) : ?>
                                    <button class="elimina-commento-button" onclick="eliminaCommento(this, <?php echo $commentoID; ?>)">
                                        <i class="fa-solid fa-trash elimi-icon"></i>
                                    </button>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <form id="comment-form-<?php echo $postID; ?>" class="comment-form" data-post-id="<?php echo $postID; ?>">
                    <input type="hidden" name="post_id" value="<?php echo $postID; ?>">
                    <textarea name="commento" placeholder="Scrivi un commento"></textarea>
                    <button type="submit">Invia</button>
                </form>

            </div>

            <!-- Codice per il popup di modifica post -->
            <div id="edit-popup-<?php echo $postID; ?>" class="edit-popup" style="display: none;">
                <h3>Modifica post</h3>
                <form id="edit-post-form-<?php echo $postID; ?>">
                    <input type="hidden" name="post_id" value="<?php echo $postID; ?>">
                    <input type="text" name="titolo_post" placeholder="Titolo">
                    <br><br>
                    <textarea name="testo_post" placeholder="Testo"></textarea>
                    <button type="button" onclick="submitEditForm(<?php echo $postID; ?>)">Salva modifiche</button>
                    <button type="button" onclick="deletePost(<?php echo $postID; ?>)">Elimina post</button>
                    <button type="button" onclick="closeEditPopup(<?php echo $postID; ?>)">Annulla</button>
                </form>
            </div>
        <?php
        }
        ?>
    </div>
    <script>
        function toggleLike(button) {
            var postID = $(button).parent().data('post-id');
            var isLiked = $(button).siblings('.like-status').val();
            var icon = $(button).find('i');

            $.ajax({
                url: 'like_post.php',
                type: 'POST',
                data: {
                    post_id: postID,
                    like_status: isLiked === '1' ? '0' : '1'
                },
                success: function(response) {
                    if (response === 'success') {
                        var newLikeStatus = isLiked === '1' ? '0' : '1';
                        $(button).siblings('.like-status').val(newLikeStatus);
                        icon.toggleClass('fa-regular fa-thumbs-up fa-solid fa-thumbs-up');
                    } else {
                        console.error(response);
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
        $(document).ready(function() {
            $('.comment-form').submit(function(event) {
                event.preventDefault(); // Blocca l'invio del form

                // Ottieni i dati del form
                var form = $(this);
                var postID = form.data('post-id');
                var commento = form.find('textarea[name="commento"]').val();

                // Effettua la richiesta Ajax per salvare il commento
                $.ajax({
                    type: 'POST',
                    url: 'salva_commento.php',
                    data: {
                        post_id: postID,
                        commento: commento
                    },
                    success: function(response) {
                        // Parsa la risposta JSON
                        var data = JSON.parse(response);

                        if (data.success) {
                            // Aggiorna dinamicamente la sezione dei commenti senza ricaricare la pagina
                            var commentSection = $('#comment-section-' + postID);
                            commentSection.append("<div class='comment'><p><strong>" + data.author + ":</strong> " + commento + "</p></div>");

                            // Resetta il campo di testo del commento
                            form.find('textarea[name="commento"]').val('');
                        } else {
                            // Mostra un messaggio di errore
                            alert(data.message);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });

        function toggleComments(button) {
            var postID = $(button).siblings('.comment-section').attr('id');
            $('#' + postID).toggle();
            $(button).text(function(_, text) {
                return text === 'Mostra commenti' ? 'Nascondi commenti' : 'Mostra commenti';
            });
        }

        function openEditPopup(postID) {
            $('#edit-popup-' + postID).show();
        }

        function closeEditPopup(postID) {
            $('#edit-popup-' + postID).hide();
        }

        function deletePost(postID) {
            var confirmation = confirm('Sei sicuro di voler eliminare il post?');

            if (confirmation) {
                $.ajax({
                    url: 'eliminapost.php',
                    type: 'POST',
                    data: {
                        post_id: postID
                    },
                    success: function(response) {
                        if (response === 'success') {
                            $('.post[data-post-id="' + postID + '"]').remove();
                            location.href = location.href; // Ricarica la pagina
                        } else {
                            console.error(response);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        }

        function submitEditForm(postID) {
            var form = $('#edit-post-form-' + postID);

            // Effettua la richiesta Ajax per salvare le modifiche al post
            $.ajax({
                type: 'POST',
                url: 'modifica_post.php',
                data: form.serialize(),
                success: function(response) {
                    console.log(response);
                    if (response === 'success') {
                        // Ricarica la pagina dopo il salvataggio delle modifiche al post
                        location.reload();
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function eliminaCommento(button, commentoID) {
            var confirmed = confirm('Sei sicuro di voler eliminare questo commento?');

            if (confirmed) {
                // Effettua la richiesta Ajax per eliminare il commento
                $.ajax({
                    type: 'POST',
                    url: 'elimina_commento.php',
                    data: {
                        commento_id: commentoID
                    },
                    success: function(response) {
                        if (response === 'success') {
                            // Rimuovi il commento dal DOM
                            $(button).parent().parent().remove();
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        }
    </script>
</body>

</html>