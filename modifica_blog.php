<?php
session_start();
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera l'id del blog dall'URL
    $codice_blog = $_GET['blog_id'];

    // Recupera i valori inviati tramite POST
    $nuovoTitolo = $_POST['nuovo-titolo'];
    $nuovaDescrizione = $_POST['nuova-descrizione'];
    $nuovaCategoria = $_POST['nuova-categoria'];

    // Esegue la query per modificare i dati nel database
    $queryModifica = "UPDATE blog_ SET titolo_blog = '$nuovoTitolo', descrizione_blog = '$nuovaDescrizione', nome_categoria = '$nuovaCategoria' WHERE codice_blog = $codice_blog";
    if ($conn->query($queryModifica) === TRUE) {
        echo "Modifiche salvate con successo!";
    } else {
        echo "Errore durante il salvataggio delle modifiche: " . $conn->error;
    }

    // Chiude la connessione al database
    $conn->close();
}
?>