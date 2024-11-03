<?php
require_once('connect.php');
session_start();

// Check if the 'id' parameter is set and numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $codiceblog = $_GET['id'];
    $username = $_SESSION['uid'];
    // Query per ottenere i dati del blog con l'ID specificat
    $query_blog = $conn->prepare('SELECT * FROM blog_ WHERE codice_blog = ?');
    $query_blog->bind_param('i', $codiceblog);
    $query_blog->execute();
    $result_blog = $query_blog->get_result();

    if ($result_blog && $result_blog->num_rows > 0) {
        // Se è presente un blog con l'ID specificato, stampa i suoi dati
        $blog = $result_blog->fetch_assoc();
        $titolo_blog = $blog['titolo_blog'];
        $creatore = $blog['username'];

        echo("
            <div class='dati-blog'>
                <h5>Titolo del Blog: $titolo_blog</h5>
                <p>Creatore: $creatore</p>
            </div>
        ");
    } else {
        echo "No blog found with the given ID.";
    }

    $query_blog->close();
} else {
    echo "Invalid blog ID.";
}
?>
