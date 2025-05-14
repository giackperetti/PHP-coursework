<?php
$hotels = [];
$localita = null;

$conn = new mysqli("localhost", "root", "root", "5f_hotel");
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_GET["localita"]) && trim($_GET["localita"]) !== "") {
    $localita = htmlspecialchars($_GET["localita"]);
    $query = "SELECT * FROM hotel WHERE Localita = '$localita'";
    $result = $conn->query($query);
} else {
    $result = $conn->query("SELECT * FROM hotel");
}

if ($result && $result->num_rows > 0) {
    while ($hotel = $result->fetch_assoc()) {
        $hotels[] = $hotel;
    }
}

$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <title>Peretti Giacomo - Hotel per Località</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        form {
            margin-bottom: 30px;
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
        }

        .hotel-info p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <h1>Visualizza Hotel per Località</h1>
    <form method="GET" action="">
        <label for="localita">Inserisci la località:</label>
        <input type="text" id="localita" name="localita" value="<?= isset($_GET["localita"]) ? htmlspecialchars($_GET["localita"]) : '' ?>">
        <button type="submit">Cerca</button>
    </form>

    <div class="card-container">
        <?php if (!empty($hotels)): ?>
            <?php foreach ($hotels as $hotel): ?>
                <div class="hotel-card">
                    <img src="immagini/<?= htmlspecialchars($hotel["Foto"]) ?>" alt="Foto Hotel">
                    <div class="hotel-info">
                        <h2><?= htmlspecialchars($hotel["Denominazione"]) ?></h2>
                        <p><strong>Indirizzo:</strong> <?= htmlspecialchars($hotel["Indirizzo"]) . ", " . htmlspecialchars($hotel["Localita"]) ?></p>
                        <p><strong>Telefono:</strong> <?= htmlspecialchars($hotel["Telefono"]) ?></p>
                        <p><strong>Categoria:</strong> <?= str_repeat("★", (int) $hotel["Categoria"]) ?></p>
                        <p><strong>Camere:</strong> <?= (int) $hotel["NumeroCamere"] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif ($localita !== null): ?>
            <p>Nessun hotel trovato per la località <strong><?= $localita ?></strong>.</p>
        <?php endif; ?>
    </div>
</body>