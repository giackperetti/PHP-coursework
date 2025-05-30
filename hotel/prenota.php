<?php
require_once 'config.php';

if (!checkAuth()) {
    header("Location: login.php");
    exit();
}

$user_id = getUserId();
$hotel_id = isset($_GET['hotel_id']) ? (int)$_GET['hotel_id'] : 0;
$hotel = null;
$stanze = [];
$error_message = null;
$success_message = null;

if ($hotel_id > 0) {
    $result = $conn->query("SELECT * FROM hotel WHERE ID = $hotel_id");
    if ($result && $result->num_rows > 0) {
        $hotel = $result->fetch_assoc();
    }
    $result?->free();
}

if (!$hotel) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stanza_id = isset($_POST['stanza_id']) ? (int)$_POST['stanza_id'] : 0;
    $data_inizio = $conn->real_escape_string(trim($_POST['data_inizio'] ?? ''));
    $data_fine = $conn->real_escape_string(trim($_POST['data_fine'] ?? ''));

    if ($stanza_id <= 0) {
        $error_message = "Seleziona una stanza valida";
    } elseif (empty($data_inizio) || empty($data_fine)) {
        $error_message = "Inserisci le date di inizio e fine soggiorno";
    } elseif (!validateDates($data_inizio, $data_fine)) {
        $error_message = "Le date inserite non sono valide. La data di inizio deve essere oggi o successiva e la data di fine deve essere successiva alla data di inizio";
    } else {
        $conn->autocommit(false);
        try {
            $query = "
                SELECT s.*, h.Denominazione AS HotelNome
                FROM stanze s
                JOIN hotel h ON s.HotelID = h.ID
                WHERE s.ID = $stanza_id AND s.HotelID = $hotel_id
                FOR UPDATE";
            $result = $conn->query($query);
            if (!$result || $result->num_rows === 0) {
                throw new Exception("Stanza non trovata");
            }

            $stanza = $result->fetch_assoc();
            $result->free();

            $query = "
                SELECT COUNT(*) AS conflitti
                FROM prenotazioni
                WHERE StanzaID = $stanza_id
                AND Stato = 'confermata'
                AND NOT (DataFine <= '$data_inizio' OR DataInizio >= '$data_fine')";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $result->free();

            if ($row['conflitti'] > 0) {
                throw new Exception("La stanza non è disponibile per il periodo selezionato");
            }

            $query = "
                INSERT INTO prenotazioni (UtenteID, StanzaID, DataInizio, DataFine)
                VALUES ($user_id, $stanza_id, '$data_inizio', '$data_fine')";
            if (!$conn->query($query)) {
                throw new Exception("Errore durante la creazione della prenotazione");
            }

            $prenotazione_id = $conn->insert_id;
            $conn->commit();

            $start_date = new DateTime($data_inizio);
            $end_date = new DateTime($data_fine);
            $giorni = $start_date->diff($end_date)->days;
            $prezzo_totale = $giorni * $stanza['Prezzo'];

            $success_message = "Prenotazione confermata! ID: {$prenotazione_id}<br>" .
                "Stanza: {$stanza['NumeroStanza']} ({$stanza['TipoStanza']})<br>" .
                "Periodo: {$data_inizio} - {$data_fine} ({$giorni} giorni)<br>" .
                "Prezzo totale: €" . number_format($prezzo_totale, 2);
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = $e->getMessage();
        }

        $conn->autocommit(true);
    }
}

$result = $conn->query("SELECT * FROM stanze WHERE HotelID = $hotel_id ORDER BY TipoStanza, NumeroStanza");
while ($row = $result->fetch_assoc()) {
    $stanze[] = $row;
}
$result?->free();
$conn->close();
?>

<head>
    <meta charset="UTF-8">
    <title>Prenota - <?= escape_output($hotel['Denominazione']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .hotel-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .room-card {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .room-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
        }

        .room-card.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }

        .room-type {
            font-weight: bold;
            color: #007bff;
            text-transform: capitalize;
        }

        .room-price {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            margin-top: 10px;
        }

        .btn {
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .error {
            color: #dc3545;
            background: #f8d7da;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success {
            color: #155724;
            background: #d4edda;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .date-info {
            background: #e9f7ff;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 14px;
            color: #0066cc;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Prenota presso <?= escape_output($hotel['Denominazione']) ?></h1>

        <?php if ($error_message): ?>
            <div class="error"><?= $error_message ?></div>
        <?php elseif ($success_message): ?>
            <div class="success"><?= $success_message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="stanza_id">Scegli una stanza:</label>
                <select name="stanza_id" id="stanza_id" required>
                    <option value="">-- Seleziona --</option>
                    <?php foreach ($stanze as $s): ?>
                        <option value="<?= $s['ID'] ?>" <?= $s['ID'] == ($_POST['stanza_id'] ?? '') ? 'selected' : '' ?>>
                            <?= escape_output($s['TipoStanza']) ?> - N° <?= escape_output($s['NumeroStanza']) ?> (€<?= number_format($s['Prezzo'], 2) ?>/notte)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="data_inizio">Data Inizio:</label>
                <input type="date" name="data_inizio" id="data_inizio" value="<?= htmlspecialchars($_POST['data_inizio'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="data_fine">Data Fine:</label>
                <input type="date" name="data_fine" id="data_fine" value="<?= htmlspecialchars($_POST['data_fine'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn">Prenota</button>
            <a href="index.php" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
</body>