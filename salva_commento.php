<?php
require_once('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica la presenza della variabile di sessione
    if (!isset($_SESSION['uid'])) {
        $response = array(
            'success' => false,
            'message' => 'Utente non autenticato.'
        );
        echo json_encode($response);
        exit();
    }

    // Validazione dei dati in ingresso
    $postID = isset($_POST['post_id']) ? $_POST['post_id'] : null;
    $commento = isset($_POST['commento']) ? $_POST['commento'] : null;

    if (empty($postID) || empty($commento)) {
        $response = array(
            'success' => false,
            'message' => 'Dati del commento mancanti.'
        );
        echo json_encode($response);
        exit();
    }

    $username = $_SESSION['uid'];

    // Esegui l'inserimento del commento nel database utilizzando prepared statements
    $inserisciCommento = "INSERT INTO commenta (Username, CodPost, Testo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($inserisciCommento);
    $stmt->bind_param("sis", $username, $postID, $commento);
    $result = $stmt->execute();
    
    if ($result) {
        // Recupera l'autore del commento
        $commentoID = $stmt->insert_id;
        $stmt->close();
        
        $queryAutore = "SELECT Username FROM commenta WHERE CodComm = ?";
        $stmt = $conn->prepare($queryAutore);
        $stmt->bind_param("i", $commentoID);
        $stmt->execute();
        $resultAutore = $stmt->get_result();
        $stmt->close();
        
        if ($resultAutore && $resultAutore->num_rows > 0) {
            $autore = $resultAutore->fetch_assoc()['Username'];

            // Restituisci la risposta JSON con l'indicazione di successo e l'autore del commento
            $response = array(
                'success' => true,
                'author' => $autore
            );
            echo json_encode($response);
            exit();
        }
    }

    // In caso di errore, restituisci la risposta JSON con l'indicazione dell'errore
    $response = array(
        'success' => false,
        'message' => 'Si è verificato un errore durante l\'inserimento del commento.',
        'error' => $conn->error
    );
    echo json_encode($response);
}

$conn->close();
?>
