<?php
require_once 'config.php';

checkAuth();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM persona";
$result = $conn->query($sql);
?>

<?php include 'header.php'; ?>
<h1>Lista Persone</h1>
<table class="table">
    <thead>
        <tr>
            <th>Operazioni</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Città</th>
            <th>Provincia</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <button class="btn" onclick="window.location.href='create_update.php?id=<?= $row['IDPersona'] ?>'">Modifica</button>
                        <br />
                        <button class="btn" onclick="window.location.href='delete.php?id=<?= $row['IDPersona'] ?>'">Cancella</button>
                    </td>
                    <td><?= htmlspecialchars($row['Nome']) ?></td>
                    <td><?= htmlspecialchars($row['Cognome']) ?></td>
                    <td><?= htmlspecialchars($row['Telefono']) ?></td>
                    <td><?= htmlspecialchars($row['Email']) ?></td>
                    <td><?= htmlspecialchars($row['Citta']) ?></td>
                    <td><?= htmlspecialchars($row['Prov']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nessuna persona trovata</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>