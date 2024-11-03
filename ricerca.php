<?php
require_once('connect.php');
session_start();

if (isset($_POST['search'])) {
  $searchTerm = $_POST['search'];

  $query = "SELECT * FROM blog_
            WHERE titolo_blog LIKE '%$searchTerm%'
            OR nome_categoria LIKE '%$searchTerm%'
            OR username LIKE '%$searchTerm%'";
  $result = mysqli_query($conn, $query);
  $blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
  $blogs = []; // Inizializza l'array vuoto se la ricerca non è stata effettuata
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .blog-item {
      border: 1px solid #ccc;
      padding: 10px;
      margin-bottom: 10px;
    }

    .blog-info h2 {
      font-size: 18px;
      margin-bottom: 5px;
    }

    .blog-info p {
      margin: 0;
    }

    .blog-info img {
      max-width: 100px;
      max-height: 100px;
    }
    .bottone_errore{
      background-color: purple;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
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
      <a href="creablog.php"><i class="fa-solid fa-plus"></i>Nuovo</a>
      <a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </nav>
  </header>
  <div class="container-blog">
    <h1>Risultati della ricerca</h1>
    <div class="blog-container">
      <?php if (isset($blogs) && count($blogs) > 0) : ?>
        <?php foreach ($blogs as $blog) : ?>
          <div class="blog-item">
            <a href="post.php?id=<?php echo $blog['codice_blog']; ?>" class="blog-link">
              <div class="blog-info">
                <h2><?php echo $blog['titolo_blog']; ?></h2>
                <p><?php echo $blog['descrizione_blog']; ?></p>
                <p>Autore: <?php echo $blog['username']; ?></p>
                <p>Categoria: <?php echo $blog['nome_categoria']; ?></p>
              </div>
              <?php if (!empty($blog['icona'])) : ?>
                <img src="img/<?php echo $blog['icona']; ?>" alt="Immagine del blog">
              <?php endif; ?>
            </a>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p>Nessun risultato trovato per il termine di ricerca.</p>
        <br>
        <p>
          <a href="home.php" class="bottone_errore">Riprova</a>
        </p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>