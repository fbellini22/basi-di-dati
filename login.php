<?php
require_once('connect.php');
session_start();

if (isset($_POST['Username']) && isset($_POST['Pw'])) {
    $Username = $_POST['Username'];
    $Pw = $_POST['Pw'];
// Esegui una query per verificare se esiste un utente con lo stesso username nel database
    $query = $conn->prepare("SELECT Username, Pw FROM utente WHERE Username = ?");
    $query->bind_param('s', $Username);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    // Verifica se l'utente esiste nel database e se la password corrisponde
    if ($row && password_verify($Pw, $row['Pw'])) {
        $_SESSION['uid'] = $Username;
        echo 1; // Login successful
    } else {
        echo 0; // Login failed
    }

    $query->close();
}
?>
