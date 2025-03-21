<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT targa, modello, colore FROM auto";
$result = $conn->query($sql);
?>

<head>
    <title>Elenco Automobili</title>
    <style>
        body {
            background-color: #1e1e2e;
            color: #cdd6f4;
        }

        table {
            border-collapse: collapse;
            width: 50%;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #fab387;
        }

        th,
        td {
            border: 1px solid #585b70;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #313244;
            color: #f9e2af;
        }
    </style>
</head>

<body>
    <h1>Elenco Automobili</h1>
    <table>
        <thead>
            <tr>
                <th>Targa</th>
                <th>Modello</th>
                <th>Colore</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["targa"]); ?></td>
                        <td><?= htmlspecialchars($row["modello"]); ?></td>
                        <td><?= htmlspecialchars($row["colore"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No results!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php $conn->close(); ?>
</body>