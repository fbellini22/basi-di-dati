<?php
require_once('connect.php');
session_start();

// Codice per eliminare il blog
if (isset($_POST['id_blog'])) {
    $id_blog = $_POST['id_blog'];

    // Query per eliminare il blog dalla tabella
    $sql_delete_blog = "DELETE FROM blog_ WHERE codice_blog = $id_blog";

    if ($conn->query($sql_delete_blog) === TRUE) {
        // Se l'eliminazione del blog è avvenuta con successo, aggiorna il numero di blog dell'utente
        $creatore = $_SESSION['uid'];
        $sql_update_numero_blog = "UPDATE utente SET numero_blog = numero_blog - 1 WHERE username = '$creatore'";
        $conn->query($sql_update_numero_blog);

        echo "Il blog è stato eliminato con successo.";
    } else {
        echo "Errore durante l'eliminazione del blog: " . $conn->error;
    }
}

// Chiusura della connessione al database
$conn->close();

?>