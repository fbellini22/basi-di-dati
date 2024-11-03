<?php
require_once('connect.php');
session_start();

if (isset($_SESSION['uid']) && isset($_POST['abbonati'])) {
    $username = $_SESSION['uid'];

    // Verifica se l'utente è già abbonato
    $query = $conn->prepare("SELECT * FROM utente_premium WHERE Username = ?");
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Utente già abbonato, mostra un messaggio di errore
        $_SESSION['error_message'] = "Sei già abbonato!";
    } else {
        // Utente non abbonato, effettua l'operazione di inserimento
        $dataScadenza = date('Y-m-d', strtotime('+2 months'));

        $query = $conn->prepare("INSERT INTO utente_premium (Username, Datafine) VALUES (?, ?)");
        $query->bind_param('ss', $username, $dataScadenza);
        $query->execute();

        $_SESSION['success_message'] = "Abbonamento effettuato con successo! Corri a dare un voto.";
    }

    // Reindirizza l'utente alla stessa pagina
    header("Location: profilo.php");
    exit;
}

// Recupera il messaggio di successo dalla variabile di sessione (se presente)
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// Rimuovi il messaggio di successo dalla variabile di sessione
unset($_SESSION['success_message']);

// Recupera il messaggio di errore dalla variabile di sessione (se presente)
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
// Rimuovi il messaggio di errore dalla variabile di sessione
unset($_SESSION['error_message']);
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="home.css" />
    <title>CrowdConnect</title>
</head>

<body>
    <header class="header">
        <div class="left-section">
            <a href="home.php" class="logo">CrowdConnect</a>
        </div>
        <nav class="navbar">
            <a href="home.php"><i class="fa-solid fa-house"></i> Home</a>
            <a href="creablog.php"><i class="fa-solid fa-pen-fancy"></i>Nuovo</a>
            <a href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </header>
    <div class="container">
        <div class="container-wrapper">
            <div class="profile">
                <?php
                if (isset($_SESSION['uid'])) {
                    // Ottieni l'username dell'utente dalla sessione
                    $username = $_SESSION['uid'];

                    $query = $conn->prepare("SELECT Nome, Cognome, Mail, Username FROM utente WHERE Username = ?");
                    $query->bind_param('s', $username);
                    $query->execute();
                    $result = $query->get_result();
                    $row = $result->fetch_assoc();

                    if ($row) {
                        // Estrai i dettagli dell'utente dal risultato della query
                        $nome = $row['Nome'];
                        $cognome = $row['Cognome'];
                        $mail = $row['Mail'];
                        $username = $row['Username'];
                        // Mostra i dettagli del profilo utente
                        echo "<h2>Profilo Utente</h2><br>";
                        echo "<p class='profile-info'><strong>Nome:</strong> $nome</p><br>";
                        echo "<p class='profile-info'><strong>Cognome:</strong> $cognome</p><br>";
                        echo "<p class='profile-info'><strong>Mail:</strong> $mail</p><br>";
                        echo "<p class='profile-info'><strong>Username:</strong> $username</p><br>";
                    }
                }
                ?>
            </div>
            <div class="container1">
                <div class="content">
                    <?php
                    // Verifica se l'utente è abbonato
                    $query = $conn->prepare("SELECT * FROM utente_premium WHERE Username = ?");
                    $query->bind_param('s', $username);
                    $query->execute();
                    $result = $query->get_result();
                    $isPremium = $result->num_rows > 0;

                    if ($isPremium) {
                        echo "<form method='post' action=''>
                    <p>Sei abbonato!</p>
                </form>";
                    } else {
                        echo "<form method='post' action=''>
                    <p>Vuoi dare un voto anche tu ai blog?</p>
                    <button class='abbonati-btn' name='abbonati'>Abbonati subito!</button>
                </form>";
                    }
                    ?>

                    <form id='delete-profile-form' method='post' action='eliminaaccount.php'>
                        <input type="hidden" name="username" value="<?php echo $username; ?>">
                        <button class='elimina-account-btn' name='elimina'>Elimina account</button>
                    </form>

                    <?php if (!empty($successMessage)) : ?>
                        <div class="success-message">
                            <?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</body>

</html>