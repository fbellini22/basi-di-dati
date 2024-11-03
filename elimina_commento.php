<?php
require_once('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentID = $_POST['commento_id']; // Corretto: $_POST['commento_id']
    $userID = $_SESSION['uid'];

    // Controlla se l'utente è l'autore del commento
    $queryVerifica = "SELECT * FROM commenta WHERE CodComm = $commentID AND Username = '$userID'";
    $resultVerifica = $conn->query($queryVerifica);

    if ($resultVerifica->num_rows > 0) {
        // Elimina il commento dal database
        $eliminaCommento = "DELETE FROM commenta WHERE CodComm = $commentID";
        $result = $conn->query($eliminaCommento);

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
