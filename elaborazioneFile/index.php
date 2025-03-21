<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elaborazione File</title>
    <style>
        body {
            font-family: "JetBrains Mono";
            background-color: #282828;
            color: #ebdbb2;
        }

        h2 {
            color: #bdae93;
        }
    </style>
</head>

<body>
    <form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
        <label>File dei nomi dei personaggi:</label>
        <br />
        <input name="userfile" type="file" />
        <br /><br />
        <input type="submit" value="Send File" />
    </form>

    <?php
    function validaData($data)
    {
        $separatore = (strpos($data, "/") !== false) ? "/" : "-";
        $parti_data = explode($separatore, trim($data));

        if (count($parti_data) !== 3) {
            return false;
        }

        return checkdate((int)$parti_data[1], (int)$parti_data[0], (int)$parti_data[2]);
    }

    function processLine($riga, $num_righe, $file_log)
    {
        $linea = explode(";", trim($riga));

        if (count($linea) < 3 || empty(trim($linea[0])) || empty(trim($linea[1])) || empty(trim($linea[2]))) {
            fwrite($file_log, "ERR_CAMPI: Riga $num_righe non valida! Campi mancanti o vuoti.\n");
            return false;
        }

        if (!validaData($linea[1])) {
            fwrite($file_log, "ERR_DATA: " . $linea[0] . "- Data di nascita non valida: " . $linea[1] . "\n");
            return false;
        }

        if (!validaData($linea[2])) {
            fwrite($file_log, "ERR_DATA: " . $linea[0] . "- Data di morte non valida: " . $linea[2] . "\n");
            return false;
        }

        return $linea;
    }

    function convertToDate($data)
    {
        $separatore = (strpos($data, "/") !== false) ? "/" : "-";
        $parti_data = explode($separatore, trim($data));

        return DateTime::createFromFormat('d/m/Y', implode("/", $parti_data));
    }

    function verificaContemporanei($persona1, $persona2)
    {
        $nascita1 = convertToDate($persona1[1]);
        $morte1 = convertToDate($persona1[2]);
        $nascita2 = convertToDate($persona2[1]);
        $morte2 = convertToDate($persona2[2]);

        $data_inizio = max($nascita1, $nascita2);
        $data_fine = min($morte1, $morte2);

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

    function stampaListaPersonaggi($dati_corretti, $file_path)
    {
        echo "<h2>Personaggi validi:</h2><ul>";
        foreach ($dati_corretti as $personaggio) {
            echo "<li>" . htmlspecialchars($personaggio[0]) . " - " . htmlspecialchars($personaggio[1]) . " - " . htmlspecialchars($personaggio[2]) . "</li>";
        }
        echo "</ul>";

        if (file_exists($file_path)) {
            echo "<h2>Personaggi contemporanei:</h2><ul>";
            $contemporanei = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($contemporanei as $line) {
                echo "<li>" . htmlspecialchars($line) . "</li>";
            }
            echo "</ul>";
        }
    }

    function processFile($fileTmpPath)
    {
        $file = fopen($fileTmpPath, "r");
        $file_log = fopen("errors.txt", "w");
        $file_contemporanei = fopen("contemporanei.txt", "w");
        $dati_corretti = [];
        $num_righe = 0;

        if ($file) {
            while (($riga = fgets($file)) !== false) {
                $num_righe++;
                $linea = processLine($riga, $num_righe, $file_log);
                if ($linea) {
                    $dati_corretti[] = $linea;
                }
            }

            fclose($file);
        } else {
            echo "Impossibile aprire il file.";
        }

        fclose($file_log);

        if (count($dati_corretti) > 1) {
            $persona_riferimento = $dati_corretti[0];

            for ($i = 1; $i < count($dati_corretti); $i++) {
                $persona_confronto = $dati_corretti[$i];
                $risultato = verificaContemporanei($persona_riferimento, $persona_confronto);

                if ($risultato['conviventi']) {
                    $output = $persona_riferimento[0] . "; " . $persona_confronto[0] . "\t[convissuto " .
                        $risultato['anni'] . "a, " . $risultato['mesi'] . "m, " . $risultato['giorni'] . "g]\n";
                    fwrite($file_contemporanei, $output);
                }
            }
        }

        fclose($file_contemporanei);
        stampaListaPersonaggi($dati_corretti, "contemporanei.txt");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        processFile($_FILES['userfile']['tmp_name']);
    }
    ?>

</body>

</html>
