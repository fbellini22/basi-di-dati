<?php
session_start();
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera l'id del post dal formulario
    $postID = $_POST['post_id'];
    $nuovoTitolo = $_POST['titolo_post'];
    $nuovoTesto = $_POST['testo_post'];

    // Esegui l'aggiornamento del post nel database
    $updateQuery = "UPDATE post SET titolo_post = '$nuovoTitolo', testo_post = '$nuovoTesto' WHERE codice_post = $postID";

    if ($conn->query($updateQuery) === TRUE) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
