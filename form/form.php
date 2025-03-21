<?php

$nome = $_GET["nome"];
$cognome = $_GET["cognome"];
$sesso = $_GET["sesso"];
$data = $_GET["data"];
$piatti = isset($_GET["piatti"]) ? $_GET["piatti"] : [];
$paese = $_GET["paese"];

echo "Ciao, sono $nome $cognome. Sono nato il $data. Sono $sesso.<br>";
echo "Vivo in $paese.<br>";

if (!empty($piatti)) {
    echo "I miei piatti preferiti sono: " . implode(", ", $piatti) . ".";
} else {
echo "Non ho selezionato piatti preferiti.";
}

echo "<br /><br /><a href='index.php'><button Indietro type='button'>Indietro</button></a>";