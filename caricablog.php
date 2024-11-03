<?php
require_once('connect.php');

// Recupera i blog dal database
$query = "SELECT * FROM blog_";
$result = mysqli_query($conn, $query);
$blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<ul>
    <?php foreach ($blogs as $blog): ?>
        <li>
            <h2><?php echo $blog['titolo_blog']; ?></h2>
            <p><?php echo $blog['descrizione_blog']; ?></p>
            <p>Autore: <?php echo $blog['username']; ?></p>
            <p>Categoria: <?php echo $blog['nome_categoria']; ?></p>
            <?php if (!empty($blog['icona'])): ?>
                <img src="img/<?php echo $blog['icona']; ?>" alt="Immagine del blog">
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
