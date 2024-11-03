<?php
require_once('connect.php');
session_start();

// Gestione dell'upload dell'immagine
if (isset($_POST['upload'])) {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "image/" . $filename;
    $db = mysqli_connect("localhost", "root", "", "photos");
    $sql = "INSERT INTO image (filename) VALUES ('$filename')";
    mysqli_query($db, $sql);
    if (move_uploaded_file($tempname, $folder)) {
        $msg = "Caricamento dell'immagine riuscito";
    } else {
        $msg = "Caricamento dell'immagine fallito";
    }
}
// Query per ottenere tutti i blog
$query = "SELECT * FROM blog_";
$result = mysqli_query($conn, $query);
$blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css" />
    <script src="ricerca.js"></script>
    <title>CrowdConnect</title>
</head>

<body>
    <header class="header">
        <div class="left-section">
            <a href="home.php" class="logo">CrowdConnect</a>
        </div>
        <nav class="navbar">
            <a href="profilo.php"><i class="fa-solid fa-user"></i> Profilo</a>
            <a href="creablog.php"><i class="fa-solid fa-plus"></i>Nuovo</a>
            <a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
        <div class="right-section">
            <form action="ricerca.php" method="POST" class="search-bar" id="search-form">
                <input type="text" name="search" id="search-input" placeholder="Cerca il blog">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </header>
    <div class="container-blog">
        <h1>Elenco Blog</h1><br>
        <div class="blog-container" id="search-result">
            <?php foreach ($blogs as $blog) : ?>
                <div class="blog-item">
                    <div class="blog-info" onclick="openBlog('<?php echo $blog['codice_blog']; ?>')">
                        <h2><?php echo $blog['titolo_blog']; ?></h2>
                        <p><?php echo $blog['descrizione_blog']; ?></p>
                        <p>Autore: <?php echo $blog['username']; ?></p>
                        <p>Coautore: <?php echo getCoautore($conn, $blog['codice_blog']); ?></p>
                        <p>Categoria: <?php echo $blog['nome_categoria']; ?></p>
                    </div>
                    <?php if (!empty($blog['icona'])) : ?>
                        <img src="img/<?php echo $blog['icona']; ?>" alt="Immagine del blog">
                    <?php endif; ?>

                    <div class="like-container">
                        <?php
                        // Verifica se l'utente è premium
                        $userId = $_SESSION['uid'];
                        $queryPremium = "SELECT * FROM utente_premium WHERE Username = '$userId'";
                        $resultPremium = mysqli_query($conn, $queryPremium);
                        $isPremium = mysqli_num_rows($resultPremium) > 0;

                        // Verifica se l'utente ha già messo like a questo blog
                        $blogId = $blog['codice_blog'];
                        $likeIcon = '<i class="fa-regular fa-star" style="color: #6ea5ca;"></i>';
                        $likeStatus = 0;

                        if (isset($_SESSION['uid'])) {
                            $userId = $_SESSION['uid'];
                            $query = "SELECT * FROM valutazione WHERE codice_blog = '$blogId' AND Username = '$userId'";
                            $result = mysqli_query($conn, $query);
                            if (mysqli_num_rows($result) > 0) {
                                $likeIcon = '<i class="fa-solid fa-star" style="color: #6ea5ca;"></i>';
                                $likeStatus = 1;
                            }
                        }

                        if ($isPremium || $likeStatus === 1) {
                            echo '<button type="button" class="like-button" onclick="toggleLike(' . $blogId . ', ' . $likeStatus . ', this)">' . $likeIcon . '</button>';
                        }
                        ?>

                        <input type="hidden" class="like-status" value="<?php echo $likeStatus; ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        function toggleLike(blogId, likeStatus, button) {
            var icon = $(button);
            var blogInfo = icon.closest('.blog-info'); // Ottieni l'elemento .blog-info genitore del pulsante

            if (likeStatus === 1) {
                // Rimuovi il like dal blog
                $.ajax({
                    url: 'like_blog.php',
                    type: 'POST',
                    data: {
                        blogId: blogId,
                        likeStatus: 0
                    },
                    success: function(response) {
                        if (response === 'success') {
                            icon.html('<i class="fa-regular fa-star" style="color: #6ea5ca;"></i>');
                            icon.attr('onclick', 'toggleLike(' + blogId + ', 0, this)');
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            } else {
                // Aggiungi il like al blog
                $.ajax({
                    url: 'like_blog.php',
                    type: 'POST',
                    data: {
                        blogId: blogId,
                        likeStatus: 1
                    },
                    success: function(response) {
                        if (response === 'success') {
                            icon.html('<i class="fa-solid fa-star" style="color: #6ea5ca;"></i>');
                            icon.attr('onclick', 'toggleLike(' + blogId + ', 1, this)');
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        }


        function openBlog(blogId) {
            window.location.href = 'post.php?id=' + blogId;
        }
    </script>

    <?php
    // Funzione per ottenere il coautore di un blog
    function getCoautore($conn, $codice_blog)
    {
        $query = $conn->prepare('SELECT Username FROM `co-autore` WHERE codice_blog = ?');
        $query->bind_param('i', $codice_blog);
        $query->execute();
        $result = $query->get_result();
        $coautore = $result->fetch_assoc();
        $query->close();
        return $coautore ? $coautore['Username'] : "Nessun coautore";
    }
    ?>
</body>

</html>
