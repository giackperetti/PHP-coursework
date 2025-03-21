<?php
require_once 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$existing = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM persona WHERE IDPersona = $id";
    $result = $conn->query($query);
    $existing = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fields = ['nome', 'cognome', 'telefono', 'email', 'citta', 'provincia', "current_password", "new_password"];
    $data = array_map(fn($field) => $conn->real_escape_string($_POST[$field]), $fields);

    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($id) {
        $currently_saved_password = $conn->query("SELECT Password FROM persona WHERE IDPersona = $id")->fetch_assoc()["Password"];
    }

    if(isset($_POST["new_password"])) {
        if (password_verify($data[6], $currently_saved_password)) {
            $password = password_hash($data[7], PASSWORD_DEFAULT);
        } else {
            alertMessage("Non corrisponde la password");
        }
    } else {
        if (isset($data[6]) && !empty($data[6])) {
            $password = password_hash($data[6], PASSWORD_DEFAULT);
        } else {
            $password = password_hash($data[6], PASSWORD_DEFAULT);
        }
    }


    if ($id) {
        $query = "SELECT IDPersona FROM persona WHERE IDPersona = $id";
        $check_result = $conn->query($query);

        if ($check_result->num_rows > 0) {
            $query = "UPDATE persona SET
                Nome = '$data[0]',
                Cognome = '$data[1]',
                Telefono = '$data[2]',
                Email = '$data[3]',
                Citta = '$data[4]',
                Prov = '$data[5]',
                Password = '$password'
                WHERE IDPersona = $id";
            $success_message = "Persona aggiornata con successo";
        } else {
            $id = null;
        }
    }

    if (!$id) {
        $query = "INSERT INTO persona (Nome, Cognome, Telefono, Email, Citta, Prov, Password)
                  VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$password')";
        $success_message = "Nuova persona inserita con successo";
    }

    if ($conn->query($query)) {
        alertMessage($success_message);
    } else {
        alertMessage("Errore nell'operazione: " . $conn->error);
    }
}
?>

<?php include 'header.php'; ?>
<h1><?= $existing ? 'Modifica Persona' : 'Inserisci Nuova Persona' ?></h1>
<form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . (isset($_GET['id']) ? '?id=' . $_GET['id'] : '')); ?>">
    <?php if ($existing): ?>
        <input type="hidden" name="id" value="<?= $existing['IDPersona'] ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome"
            value="<?= $existing ? htmlspecialchars($existing['Nome']) : '' ?>"
            required>
    </div>
    <div class="form-group">
        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome"
            value="<?= $existing ? htmlspecialchars($existing['Cognome']) : '' ?>"
            required>
    </div>
    <div class="form-group">
        <label for="telefono">Telefono:</label>
        <input type="tel" id="telefono" name="telefono"
            value="<?= $existing ? htmlspecialchars($existing['Telefono']) : '' ?>"
            required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"
            value="<?= $existing ? htmlspecialchars($existing['Email']) : '' ?>"
            required>
    </div>
    <div class="form-group">
        <label for="citta">Città:</label>
        <input type="text" id="citta" name="citta"
            value="<?= $existing ? htmlspecialchars($existing['Citta']) : '' ?>"
            required>
    </div>
    <div class="form-group">
        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="provincia"
            value="<?= $existing ? htmlspecialchars($existing['Prov']) : '' ?>"
            required>
    </div>
    <?php if ($existing): ?>
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
    <?php else: ?>
        <div class="form-group">
            <label for="current_password">Password:</label>
            <input type="password" id="current_password" name="current_password"
                value=""
                required>
        </div>
    <?php endif; ?>
    <button type="submit" class="btn"><?= $existing ? 'Aggiorna' : 'Salva' ?></button>
</form>
</div>

<body />