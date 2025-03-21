<?php
require_once 'config.php';

checkAuth();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM persona WHERE IDPersona={$id}";
    if ($conn->query($query)) {
        $success_message = "Persona eliminata con successo";
        alertMessage($success_message);
    } else {
        alertMessage("Errore nell'operazione: " . $conn->error);
    }
}
