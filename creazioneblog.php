<?php
require_once('connect.php');
session_start();

$creatore = $_SESSION['uid'];
$query_veronumerob = $conn->prepare('SELECT numero_blog FROM utente WHERE username = ?');
$query_veronumerob->bind_param('s', $creatore);
$query_veronumerob->execute();
$result_veronumerob = $query_veronumerob->get_result();

// Verifica se la query ha restituito un risultato
if ($result_veronumerob->num_rows > 0) {
    // Recupera il numero di blog dalla riga restituita
    $row_veronumerob = $result_veronumerob->fetch_assoc();
    $veronumerob = $row_veronumerob['numero_blog'];

    if ($veronumerob <= 15) {
        if (
            isset($_POST['titoloblog'])
            && isset($_POST['descrizioneblog'])
            && isset($_POST['selezionacat']) && $_POST['selezionacat'] !== 'none' // Verifica che sia stata selezionata una sottocategoria valida
            && isset($_FILES['blogimg'])
        ) {
            // Il codice continuerà solo se è stata fornita una sottocategoria valida
            $img = $_FILES['blogimg'];
            $titolo = $_POST['titoloblog'];
            $descrizione = $_POST['descrizioneblog'];
            $cat = $_POST['selezionacat'];
            $coautore = isset($_POST['coautoreblog']) ? $_POST['coautoreblog'] : null;

            if ($veronumerob < 15) {
                $filename = $img['name'];
                $tempname = $img['tmp_name'];
                $folder = "img/" . $filename;

                // Sposta l'immagine nella cartella "img"
                move_uploaded_file($tempname, $folder);

                $query = $conn->prepare('INSERT INTO blog_ (username, nome_categoria, icona, descrizione_blog, titolo_blog) VALUES (?, ?, ?, ?, ?)');
                $query->bind_param('sssss', $creatore, $cat, $filename, $descrizione, $titolo);
                $query->execute();

                $lastInsertedId = $query->insert_id; // Ottieni l'ID dell'ultimo blog inserito

                $query->close();

                $veronumerob += 1;

                $query_utente = $conn->prepare('UPDATE utente SET numero_blog = ? WHERE username = ?');
                $query_utente->bind_param('is', $veronumerob, $creatore);
                $query_utente->execute();
                $query_utente->close();

                // Se è stato specificato un coautore, verifica l'esistenza dell'username nella tabella "utente" e poi salvalo nella tabella "co-autore"
                if ($coautore !== null && $coautore !== '') {
                    $query_check_coautore = $conn->prepare('SELECT * FROM utente WHERE username = ?');
                    $query_check_coautore->bind_param('s', $coautore);
                    $query_check_coautore->execute();
                    $result_check_coautore = $query_check_coautore->get_result();

                    if ($result_check_coautore->num_rows > 0) {
                        $query_coautore = $conn->prepare('INSERT INTO `co-autore` (Username, codice_blog) VALUES (?, ?)');
                        $query_coautore->bind_param('si', $coautore, $lastInsertedId); // Utilizza l'ID dell'ultimo blog inserito
                        $query_coautore->execute();
                        $query_coautore->close();
                    } else {
                        echo "L'username del coautore non esiste.";
                        exit;
                    }

                    $query_check_coautore->close();
                }

                echo "success";
            } else {
                echo "limit_exceeded";
            }
        } else {
            echo "Invalid form data.";
        }
    } else {
        echo "Hai creato il numero massimo di blog.";
    }
}

$query_veronumerob->close();
