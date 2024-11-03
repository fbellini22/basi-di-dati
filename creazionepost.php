<?php
require_once('connect.php');
session_start();

if (isset($_POST['titolopost']) && isset($_POST['testopost']) && isset($_FILES['postimg'])) {
    $img = $_FILES['postimg'];
    $titolo = $_POST['titolopost'];
    $testo = $_POST['testopost'];
    $creatore = $_SESSION['uid'];
    $codice_blog = isset($_GET['blog_id']) ? $_GET['blog_id'] : null; 

    // Effettua qui eventuali controlli aggiuntivi sui dati del form

    $date = date("Y-m-d");

    // Esegui l'inserimento del post nel database
    $query = $conn->prepare("INSERT INTO post (titolo_post, testo_post, username, immagine_post, data_post, codice_blog) VALUES (?, ?, ?, ?, ?, ?)"); 
    $query->bind_param('sssssi', $titolo, $testo, $creatore, $img['name'], $date, $codice_blog); 

    $query->execute();
    $query->close();

    // Sposta l'immagine nella cartella "img"
    $filename = $img['name'];
    $tempname = $img['tmp_name'];
    $folder = "img/" . $filename;
    move_uploaded_file($tempname, $folder);

    echo "success";
} else {
    echo "Invalid form data.";
}
?>