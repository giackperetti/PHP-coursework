<?php
function trovaContemporanei($persona1, $persona2)
{
    $data_nascita_persona1 = DateTime::createFromFormat("Y-m-d", $persona1['data_nascita']);
    $data_morte_persona1 = DateTime::createFromFormat("Y-m-d", $persona1['data_morte']);
    $data_nascita_persona2 = DateTime::createFromFormat("Y-m-d", $persona2['data_nascita']);
    $data_morte_persona2 = DateTime::createFromFormat("Y-m-d", $persona2['data_morte']);

    $data_inizio = ($data_nascita_persona1 > $data_nascita_persona2) ? $data_nascita_persona1 : $data_nascita_persona2;
    $data_fine = ($data_morte_persona1 < $data_morte_persona2) ? $data_morte_persona1 : $data_morte_persona2;

    if ($data_inizio <= $data_fine) {
        $differenza = $data_inizio->diff($data_fine);
        return [
            'conviventi' => true,
            'anni' => $differenza->y,
            'mesi' => $differenza->m,
            'giorni' => $differenza->d,
        ];
    }

    return ['conviventi' => false];
}

$serverName = "localhost";
$userName = "root";
$password = "root";
$dbName = "5f_personaggi";

$conn = new mysqli($serverName, $userName, $password, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT nome, data_nascita, data_morte
        FROM personaggi
        WHERE data_nascita IS NOT NULL AND data_morte IS NOT NULL";
$result = $conn->query($sql);

$personaggi = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $personaggi[] = $row;
    }
}

$personaggiContemporanei = [];
for ($i = 0; $i < count($personaggi); $i++) {
    for ($j = $i + 1; $j < count($personaggi); $j++) {
        $sonoContemporanei = trovaContemporanei($personaggi[$i], $personaggi[$j]);
        if ($sonoContemporanei['conviventi']) {
            $personaggiContemporanei[] = [
                'persona1' => $personaggi[$i]['nome'],
                'persona2' => $personaggi[$j]['nome'],
                'anni' => $sonoContemporanei['anni'],
                'mesi' => $sonoContemporanei['mesi'],
                'giorni' => $sonoContemporanei['giorni'],
            ];
        }
    }
}

$conn->close();
?>

<head>
    <title>Personaggi Storici</title>
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
    <h1>Personaggi Storici</h1>
    <h2>Personaggi validi:</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data di Nascita</th>
                <th>Data di Morte</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($personaggi)): ?>
                <?php foreach ($personaggi as $personaggio): ?>
                    <tr>
                        <td><?= htmlspecialchars($personaggio['nome']); ?></td>
                        <td><?= htmlspecialchars($personaggio['data_nascita']); ?></td>
                        <td><?= htmlspecialchars($personaggio['data_morte']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Non ci sono personaggi!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Personaggi contemporanei:</h2>
    <table>
        <thead>
            <tr>
                <th>Persona 1</th>
                <th>Persona 2</th>
                <th>Anni</th>
                <th>Mesi</th>
                <th>Giorni</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($personaggiContemporanei)): ?>
                <?php foreach ($personaggiContemporanei as $contemporanei): ?>
                    <tr>
                        <td><?= htmlspecialchars($contemporanei['persona1']); ?></td>
                        <td><?= htmlspecialchars($contemporanei['persona2']); ?></td>
                        <td><?= $contemporanei['anni']; ?></td>
                        <td><?= $contemporanei['mesi']; ?></td>
                        <td><?= $contemporanei['giorni']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Non ci sono personaggi contemporanei!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>