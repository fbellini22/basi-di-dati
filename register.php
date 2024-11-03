<?php
require_once('connect.php');
session_start();
// Recupera i valori inviati tramite il metodo POST
if (isset($_POST)) {
    $Nome = $_POST['Nome'];
    $Cognome = $_POST['Cognome'];
    $Mail = $_POST['Mail'];
    $Pw = $_POST['Pw'];
    $Username = $_POST['Username'];
    $Pwc = $_POST['Pwc'];

    // Verifica se le password non coincidono
    if ($Pw !== $Pwc) {
        echo "Le password non coincidono, controlla bene!";
        exit;
    }

    // Verifica il formato dell'email
    if (!filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
        echo "Il formato dell'email non è valido.";
        exit;
    }

    // Verifica la lunghezza minima della password
    if (strlen($Pw) < 6) {
        echo "La password deve essere di almeno 6 caratteri.";
        exit;
    }

    $conferma_pw = password_hash($Pw, PASSWORD_DEFAULT);
    // Verifica se l'utente o la mail esistono già nel database
    $query = $conn->prepare("SELECT Username, Mail FROM utente WHERE Username = ? OR Mail = ?");
    $query->bind_param('ss', $Username, $Mail);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    $query->close();
    // Se non esiste un utente con lo stesso username o mail, esegui l'inserimento
    if (!$row) {
        $query = $conn->prepare("INSERT INTO utente (Nome, Cognome, Mail, Pw, Username) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param('sssss', $Nome, $Cognome, $Mail, $conferma_pw, $Username);

        // Esegui la query di inserimento
        if ($query->execute()) {
            $_SESSION['uid'] = $Username;
            echo "Registrazione avvenuta con successo.";
        } else {
            echo "Errore durante l'inserimento dell'utente.";
        }

        $query->close();
    } else {
        echo "Utente già esistente";
    }
}
?>