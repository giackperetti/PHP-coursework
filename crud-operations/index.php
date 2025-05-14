<?php
require_once 'config.php';

checkAuth();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$showingAllRecords = false;
$displaySearch = "";
$lastSearch = isset($_COOKIE['last_search']) ? $_COOKIE['last_search'] : "";

if (isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $displaySearch = $search; // For the form
    setcookie('last_search', $search, time() + (86400 * 30), "/");
} elseif (isset($_GET['reset']) && $_GET['reset'] == 1) {
    $search = "";
    $displaySearch = $lastSearch;
    $showingAllRecords = true;
} elseif (!empty($lastSearch)) {
    $search = $lastSearch;
    $displaySearch = $lastSearch;
} else {
    $search = "";
    $displaySearch = "";
}

if (!empty($search)) {
    $searchTerms = explode(' ', $search);
    $searchConditions = [];

    foreach ($searchTerms as $term) {
        if (empty(trim($term))) continue;

        $term = $conn->real_escape_string(trim($term));
        $searchConditions[] = "(Nome LIKE '%$term%' OR
                              Cognome LIKE '%$term%' OR
                              Telefono LIKE '%$term%' OR
                              Email LIKE '%$term%' OR
                              Citta LIKE '%$term%' OR
                              Prov LIKE '%$term%')";
    }

    if (!empty($searchConditions)) {
        $whereClause = implode(' AND ', $searchConditions);
        $sql = "SELECT * FROM persona WHERE $whereClause";
    } else {
        $sql = "SELECT * FROM persona";
    }
} else {
    $sql = "SELECT * FROM persona";
}

$result = $conn->query($sql);
?>

<?php include 'header.php'; ?>
<h1>Lista Persone</h1>

<div class="search-container" style="margin-bottom: 20px;">
    <form method="POST" action="" style="display: flex; gap: 10px; align-items: center;">
        <div class="form-group" style="flex-grow: 1;">
            <input type="text" name="search" class="btn" placeholder="Cerca per nome, cognome, email, città..." value="<?= htmlspecialchars($displaySearch) ?>">
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Cerca</button>
        </div>
        <?php if (!empty($displaySearch)): ?>
        <div class="form-group">
            <a href="<?= $_SERVER['PHP_SELF'] ?>?reset=1" class="btn">Reset</a>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php if (!empty($search) && !$showingAllRecords): ?>
    <p>Risultati della ricerca per: <strong><?= htmlspecialchars($search) ?></strong></p>
<?php elseif ($showingAllRecords && !empty($displaySearch)): ?>
    <p>Mostrando tutti i record. Ultima ricerca: <strong><?= htmlspecialchars($displaySearch) ?></strong></p>
<?php endif; ?>

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
        <?php if ($result && $result->num_rows > 0): ?>
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
                <td colspan="7">Nessuna persona trovata</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>