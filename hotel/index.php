<?php
require_once 'config.php';

$hotels = [];
$localita = null;
$error_message = null;

if (isset($_GET["localita"]) && trim($_GET["localita"]) !== "") {
    $localita = trim($_GET["localita"]);

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM hotel WHERE Localita = ?");
    $stmt->bind_param("s", $localita);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM hotel");
}

if ($result && $result->num_rows > 0) {
    while ($hotel = $result->fetch_assoc()) {
        $hotels[] = $hotel;
    }
} elseif ($localita !== null) {
    $error_message = "Nessun hotel trovato per la località " . escape_output($localita);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peretti Giacomo - Hotel per Località</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .auth-info {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        form {
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #1e7e34;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .hotel-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            overflow: hidden;
        }

        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .hotel-info {
            padding: 15px;
        }

        .hotel-info h2 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #333;
        }

        .hotel-info p {
            margin: 5px 0;
            color: #666;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .stars {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Visualizza Hotel per Località</h1>
        <div class="auth-info">
            <?php if (checkAuth()): ?>
                <span>Benvenuto!</span>
                <button onclick="window.location.href='logout.php'" class="btn-secondary">Logout</button>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
            <?php endif; ?>
        </div>
    </div>

    <form method="GET" action="">
        <div class="form-group">
            <label for="localita">Inserisci la località:</label>
            <input type="text" id="localita" name="localita"
                value="<?= isset($_GET["localita"]) ? escape_output($_GET["localita"]) : '' ?>"
                placeholder="Es: Roma, Milano, Venezia">
            <button type="submit">Cerca</button>
            <?php if (isset($_GET["localita"])): ?>
                <button type="button" onclick="window.location.href='index.php'">Mostra Tutti</button>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($error_message): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="card-container">
        <?php if (!empty($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
                <div class="hotel-card">
                    <img src="immagini/<?= escape_output($hotel["Foto"]) ?>"
                        alt="Foto <?= escape_output($hotel["Denominazione"]) ?>"
                        onerror="this.src='immagini/default.jpg'">
                    <div class="hotel-info">
                        <h2><?= escape_output($hotel["Denominazione"]) ?></h2>
                        <p><strong>Indirizzo:</strong>
                            <?= escape_output($hotel["Indirizzo"]) . ", " . escape_output($hotel["Localita"]) ?></p>
                        <p><strong>Telefono:</strong> <?= escape_output($hotel["Telefono"]) ?></p>
                        <p><strong>Categoria:</strong>
                            <span class="stars"><?= str_repeat("★", (int) $hotel["Categoria"]) ?></span>
                        </p>
                        <p><strong>Camere:</strong> <?= (int) $hotel["NumeroCamere"] ?></p>
                        <?php if (checkAuth()): ?>
                            <button onclick="window.location.href='prenota.php?hotel_id=<?= (int)$hotel['ID'] ?>'"
                                class="btn-success">Prenota</button>
                        <?php else: ?>
                            <button onclick="alert('Devi effettuare il login per prenotare'); window.location.href='login.php'">
                                Prenota (Login richiesto)
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($localita === null): ?>
            <p>Utilizza il form sopra per cercare hotel per località o visualizzare tutti gli hotel disponibili.</p>
        <?php endif; ?>
    </div>
</body>

</html>