<?php
session_start();
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postID = $_POST['post_id'];
    $userID = $_SESSION['uid'];
    $likeStatus = $_POST['like_status'];

    if ($likeStatus === '1') {
        // Aggiungi "Mi piace" al post
        $queryInserisci = "INSERT INTO reazione (username, codice_post) VALUES ('$userID', $postID)";
        if ($conn->query($queryInserisci) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        // Rimuovi "Mi piace" dal post
        $queryRimuovi = "DELETE FROM reazione WHERE codice_post = $postID AND username = '$userID'";
        if ($conn->query($queryRimuovi) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
    }

    // Chiudi la connessione al database
    $conn->close();
}
?>
