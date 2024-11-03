<?php
require_once('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postID = $_POST['post_id'];
    $userID = $_SESSION['uid'];

    // Verifica se l'utente è l'autore del post
    $queryVerifica = "SELECT * FROM post WHERE codice_post = $postID AND username = '$userID'";
    $resultVerifica = $conn->query($queryVerifica);

    if ($resultVerifica->num_rows > 0) {
        // Elimina il post dalla tabella "post"
        $eliminaPost = "DELETE FROM post WHERE codice_post = $postID";
        $result = $conn->query($eliminaPost);

        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'unauthorized';
    }
}

$conn->close();
?>
