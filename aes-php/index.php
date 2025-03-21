<head>
    <meta charset="UTF-8">
    <title>AES Cifratura e Decifratura</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #666;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 2rem;
        }

        input {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .result-section {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .result-section h2 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .matrix-display {
            font-family: monospace;
            white-space: pre-wrap;
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AES Cifratura e Decifratura</h1>
            <p>Dimostrazione di un round di cifratura e decifratura AES</p>
        </div>

        <form method="POST">
            <input
                type="text"
                name="inputText"
                placeholder="Inserisci 16 caratteri"
                maxlength="16"
                minlength="16"
                required
            >
            <button type="submit">Cifra e Decifra</button>
        </form>

        <?php

        /* SBox e Inx-SBox
        * Utilizzate per la sostituzione non lineare dei byte
        * durante le fasi di cifratura e decifratura
        **/
        $sBox = [
            ["63", "7c", "77", "7b", "f2", "6b", "6f", "c5", "30", "01", "67", "2b", "fe", "d7", "ab", "76"],
            ["ca", "82", "c9", "7d", "fa", "59", "47", "f0", "ad", "d4", "a2", "af", "9c", "a4", "72", "c0"],
            ["b7", "fd", "93", "26", "36", "3f", "f7", "cc", "34", "a5", "e5", "f1", "71", "d8", "31", "15"],
            ["04", "c7", "23", "c3", "18", "96", "05", "9a", "07", "12", "80", "e2", "eb", "27", "b2", "75"],
            ["09", "83", "2c", "1a", "1b", "6e", "5a", "a0", "52", "3b", "d6", "b3", "29", "e3", "2f", "84"],
            ["53", "d1", "00", "ed", "20", "fc", "b1", "5b", "6a", "cb", "be", "39", "4a", "4c", "58", "cf"],
            ["d0", "ef", "aa", "fb", "43", "4d", "33", "85", "45", "f9", "02", "7f", "50", "3c", "9f", "a8"],
            ["51", "a3", "40", "8f", "92", "9d", "38", "f5", "bc", "b6", "da", "21", "10", "ff", "f3", "d2"],
            ["cd", "0c", "13", "ec", "5f", "97", "44", "17", "c4", "a7", "7e", "3d", "64", "5d", "19", "73"],
            ["60", "81", "4f", "dc", "22", "2a", "90", "88", "46", "ee", "b8", "14", "de", "5e", "0b", "db"],
            ["e0", "32", "3a", "0a", "49", "06", "24", "5c", "c2", "d3", "ac", "62", "91", "95", "e4", "79"],
            ["e7", "c8", "37", "6d", "8d", "d5", "4e", "a9", "6c", "56", "f4", "ea", "65", "7a", "ae", "08"],
            ["ba", "78", "25", "2e", "1c", "a6", "b4", "c6", "e8", "dd", "74", "1f", "4b", "bd", "8b", "8a"],
            ["70", "3e", "b5", "66", "48", "03", "f6", "0e", "61", "35", "57", "b9", "86", "c1", "1d", "9e"],
            ["e1", "f8", "98", "11", "69", "d9", "8e", "94", "9b", "1e", "87", "e9", "ce", "55", "28", "df"],
            ["8c", "a1", "89", "0d", "bf", "e6", "42", "68", "41", "99", "2d", "0f", "b0", "54", "bb", "16"]
        ];

        $invSBox = [
            ["52", "09", "6A", "D5", "30", "36", "A5", "38", "BF", "40", "A3", "9E", "81", "F3", "D7", "FB"],
            ["7C", "E3", "39", "82", "9B", "2F", "FF", "87", "34", "8E", "43", "44", "C4", "DE", "E9", "CB"],
            ["54", "7B", "94", "32", "A6", "C2", "23", "3D", "EE", "4C", "95", "0B", "42", "FA", "C3", "4E"],
            ["08", "2E", "A1", "66", "28", "D9", "24", "B2", "76", "5B", "A2", "49", "6D", "8B", "D1", "25"],
            ["72", "F8", "F6", "64", "86", "68", "98", "16", "D4", "A4", "5C", "CC", "5D", "65", "B6", "92"],
            ["6C", "70", "48", "50", "FD", "ED", "B9", "DA", "5E", "15", "46", "57", "A7", "8D", "9D", "84"],
            ["90", "D8", "AB", "00", "8C", "BC", "D3", "0A", "F7", "E4", "58", "05", "B8", "B3", "45", "06"],
            ["D0", "2C", "1E", "8F", "CA", "3F", "0F", "02", "C1", "AF", "BD", "03", "01", "13", "8A", "6B"],
            ["3A", "91", "11", "41", "4F", "67", "DC", "EA", "97", "F2", "CF", "CE", "F0", "B4", "E6", "73"],
            ["96", "AC", "74", "22", "E7", "AD", "35", "85", "E2", "F9", "37", "E8", "1C", "75", "DF", "6E"],
            ["47", "F1", "1A", "71", "1D", "29", "C5", "89", "6F", "B7", "62", "0E", "AA", "18", "BE", "1B"],
            ["FC", "56", "3E", "4B", "C6", "D2", "79", "20", "9A", "DB", "C0", "FE", "78", "CD", "5A", "F4"],
            ["1F", "DD", "A8", "33", "88", "07", "C7", "31", "B1", "12", "10", "59", "27", "80", "EC", "5F"],
            ["60", "51", "7F", "A9", "19", "B5", "4A", "0D", "2D", "E5", "7A", "9F", "93", "C9", "9C", "EF"],
            ["A0", "E0", "3B", "4D", "AE", "2A", "F5", "B0", "C8", "EB", "BB", "3C", "83", "53", "99", "61"],
            ["17", "2B", "04", "7E", "BA", "77", "D6", "26", "E1", "69", "14", "63", "55", "21", "0C", "7D"]
        ];

        /* Funzione di generazione della matrice di stato
        * Converte il testo in input in una matrice di stato 4x4
        * Trasforma i caratteri in valori esadecimali
        **/
        function generaMatrice($input)
        {
            /* Divide l'input in singoli caratteri */
            $bytes = str_split($input);
            $matrice = [];

            /* Trasforma ogni carattere in esadecimale
            * Nota l'ordine di trasformazione per colonne
            * Garantisce il corretto posizionamento dei byte nella matrice
            **/
            for ($i = 0; $i < 4; $i++) {
                for ($j = 0; $j < 4; $j++) {
                    /* Converti carattere in esadecimale, aggiungi zero se necessario, maiuscolo */
                    $matrice[$j][$i] = strtoupper(str_pad(dechex(ord($bytes[$i * 4 + $j])), 2, '0', STR_PAD_LEFT));
                }
            }
            return $matrice;
        }

        /* Funzione di Sostituzione dei Byte (SubBytes)
        * Sostituisce ogni byte nella matrice di stato
        * utilizzando una S-Box predefinita
        * Fornisce una sostituzione non lineare dei byte
        **/
        function subBytes($state, $box)
        {
            $result = [];
            foreach ($state as $r => $row) {
                $result[$r] = [];
                foreach ($row as $c => $cell) {
                    /* Assicura che la cella sia di due cifre */
                    $cell = str_pad($cell, 2, '0', STR_PAD_LEFT);

                    /* Dividi il valore esadecimale in indici di riga e colonna */
                    $row_idx = hexdec(substr($cell, 0, 1));
                    $col_idx = hexdec(substr($cell, 1, 1));

                    /* Cerca il valore nella S-Box usando gli indici calcolati */
                    $result[$r][$c] = $box[$row_idx][$col_idx];
                }
            }
            return $result;
        }

        /* Funzione Shift Rows
        * Sposta ciclicamente le righe della matrice di stato
        * Ogni riga viene spostata di un offset diverso(in base all'indice della riga)
        **/
        function shiftRows($state)
        {
            return [
                $state[0],
                [$state[1][1], $state[1][2], $state[1][3], $state[1][0]],
                [$state[2][2], $state[2][3], $state[2][0], $state[2][1]],
                [$state[3][3], $state[3][0], $state[3][1], $state[3][2]]
            ];
        }

        /* Funzione Inverse Shift Rows
        * Inverte l'operazione di spostamento delle righe eseguita durante la cifratura
        * Riporta le righe nelle loro posizioni originali nella matrice di stato
        **/
        function invShiftRows($state)
        {
            return [
                $state[0],
                [$state[1][3], $state[1][0], $state[1][1], $state[1][2]],
                [$state[2][2], $state[2][3], $state[2][0], $state[2][1]],
                [$state[3][1], $state[3][2], $state[3][3], $state[3][0]],
            ];
        }

        /* Moltiplicazione nel Campo di Galois
        * Esegue la moltiplicazione nel Campo di Galois (GF(2^8))
        * Utilizzata nelle operazioni di MixColumns e InvMixColumns
        **/
        function prodottoGalois($hex1, $hex2)
        {
            /* Converti gli input esadecimali in decimale per operazioni bit a bit */
            $a = hexdec($hex1);
            $b = hexdec($hex2);

            $p = 0;
            for ($i = 0; $i < 8; $i++) {
                /* Se il bit meno significativo di b è 1, esegui XOR con a */
                if (($b & 1) != 0) {
                    $p ^= $a;
                }

                /* Controlla se il bit più significativo di a è impostato (causerebbe overflow) */
                $highBit = $a & 0x80;

                /* Sposta a a sinistra (moltiplica per 2 nel Campo di Galois) */
                $a <<= 1;

                /* Se il bit più significativo era impostato, esegui XOR con il polinomio irriducibile */
                if ($highBit != 0) {
                    /* 0x1b è il polinomio irriducibile di Rijndael nel Campo di Galois */
                    $a ^= 0x1b;
                }

                /* Sposta b a destra (divide per 2) */
                $b >>= 1;
            }

            /* Converti il risultato in esadecimale a 2 cifre, aggiungi zero se necessario */
            return strtoupper(str_pad(dechex($p & 0xff), 2, '0', STR_PAD_LEFT));
        }

        /* Calcolo XOR di Colonna
        * Esegue l'operazione XOR su tutti gli elementi di una colonna
        * Utilizzato nella moltiplicazione di matrice e nelle operazioni chiave
        **/
        function xorColonna($colonna)
        {
            /* Inizializza il risultato a 0 */
            $result = 0;

            /* Esegue XOR su tutti gli elementi */
            foreach ($colonna as $item) {
                $result ^= hexdec($item);
            }

            /* Converti in esadecimale a 2 cifre maiuscolo */
            return strtoupper(str_pad(dechex($result), 2, '0', STR_PAD_LEFT));
        }

        /* Moltiplicazione di Matrice nel Campo di Galois
        * Esegue la moltiplicazione di matrice nel Campo di Galois
        * Utilizzata nelle operazioni di MixColumns e InvMixColumns
        **/
        function galoisMatrixMultiply($matrix, $mixMatrix)
        {
            $newMatrix = [];
            for ($row = 0; $row < 4; $row++) {
                for ($col = 0; $col < 4; $col++) {
                    /* Per ogni cella, calcola i prodotti nel Campo di Galois delle righe e colonne corrispondenti */
                    $products = [];
                    for ($k = 0; $k < 4; $k++) {
                        /* Moltiplica gli elementi corrispondenti e memorizza */
                        $products[] = prodottoGalois($mixMatrix[$row][$k], $matrix[$k][$col]);
                    }

                    /* Esegue XOR sui prodotti per ottenere il valore finale della cella */
                    $newMatrix[$row][$col] = xorColonna($products);
                }
            }
            return $newMatrix;
        }

        /* Mix Columns
        * Esegue la trasformazione di confusione delle colonne
        * Applica una trasformazione lineare a ciascuna colonna della matrice di stato
        **/
        function mixColumns($matrix)
        {
            $mixMatrix = [
                ["02", "03", "01", "01"],
                ["01", "02", "03", "01"],
                ["01", "01", "02", "03"],
                ["03", "01", "01", "02"]
            ];
            return galoisMatrixMultiply($matrix, $mixMatrix);
        }

        /* Inverse Mix Columns
        * Inverte l'operazione di confusione delle colonne durante la decifratura
        * Utilizza una matrice diversa per la trasformazione lineare inversa
        **/
        function invMixColumns($matrix)
        {
            $invMixMatrix = [
                ["0E", "0B", "0D", "09"],
                ["09", "0E", "0B", "0D"],
                ["0D", "09", "0E", "0B"],
                ["0B", "0D", "09", "0E"]
            ];
            return galoisMatrixMultiply($matrix, $invMixMatrix);
        }


        /* Operazione AddRoundKey
        * Esegue XOR tra la matrice di stato e la chiave
        **/
        function addRoundKey($matrix, $roundKey)
        {
            $result = [];
            for ($row = 0; $row < 4; $row++) {
                for ($col = 0; $col < 4; $col++) {
                    /* Converti i valori della matrice e della chiave in decimale */
                    $matrix_val = hexdec($matrix[$row][$col]);
                    $key_val = hexdec($roundKey[$row][$col]);

                    /* Esegue l'operazione XOR */
                    $xor_result = $matrix_val ^ $key_val;

                    /* Converti di nuovo in esadecimale maiuscolo, aggiungi zero se necessario */
                    $result[$row][$col] = strtoupper(str_pad(dechex($xor_result), 2, '0', STR_PAD_LEFT));
                }
            }
            return $result;
        }

        /* Conversione Matrice in Stringa
        * Converte la matrice di stato di nuovo in una stringa
        * Inverte il processo di generazione della matrice
        **/
        function matrixToString($matrix)
        {
            $result = '';

            /* Scorre la matrice in ordine per colonne */
            for ($col = 0; $col < 4; $col++) {
                for ($row = 0; $row < 4; $row++) {
                    /* Converti il valore esadecimale di nuovo in carattere */
                    $result .= chr(hexdec($matrix[$row][$col]));
                }
            }
            return $result;
        }

        /* Funzione di stampa matrice
        * Visualizza una matrice nella pagina HTML risultante
        **/
        function stampaMatrice($matrice, $titolo)
        {
            echo "<div class='result-section'>";
            echo "<h2>" . htmlspecialchars($titolo) . "</h2>";
            echo "<div class='matrix-display'>";
            foreach ($matrice as $riga) {
                echo implode(" ", array_map(function ($val) {
                    return str_pad($val, 2, '0', STR_PAD_LEFT);
                }, $riga)) . "\n";
            }
            echo "</div>";
            echo "</div>";
        }

        /* Processo principale di Cifratura e Decifratura
        * Gestisce l'invio del modulo e esegue cifratura/decifratura AES
        * Dimostra un round di cifratura simile ad AES
        **/
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Convalida dell'input
            $input = $_POST['inputText'];
            if (strlen($input) != 16) {
                die("La stringa deve essere lunga esattamente 16 caratteri.");
            }

            /* Chiave di Round Costante
            * Per questo esempio, viene utilizzata una chiave di round fissa
            * In un AES reale, vengono generate più chiavi di round dalla chiave principale
            **/
            $roundKey = [
                ["cd", "92", "e1", "8f"],
                ["55", "29", "66", "20"],
                ["91", "83", "df", "76"],
                ["ef", "f1", "0a", "54"]
            ];

            // Processo di Cifratura
            $state = generaMatrice($input);
            stampaMatrice($state, "Stato iniziale (testo in chiaro)");

            // Passaggi del Round di Cifratura
            $state = subBytes($state, $sBox);
            stampaMatrice($state, "Dopo SubBytes");

            $state = shiftRows($state);
            stampaMatrice($state, "Dopo ShiftRows");

            $state = mixColumns($state);
            stampaMatrice($state, "Dopo MixColumns");

            $state = addRoundKey($state, $roundKey);
            stampaMatrice($state, "Dopo AddRoundKey (cifratura completata)");

            // Passaggi del Round di Decifratura (Operazioni Inverse)
            $state = addRoundKey($state, $roundKey);
            stampaMatrice($state, "Dopo Inverse AddRoundKey");

            $state = invMixColumns($state);
            stampaMatrice($state, "Dopo Inverse MixColumns");

            $state = invShiftRows($state);
            stampaMatrice($state, "Dopo Inverse ShiftRows");

            $state = subBytes($state, $invSBox);
            stampaMatrice($state, "Dopo Inverse SubBytes");

            // Converti la matrice di stato di nuovo in testo originale
            $decrypted = matrixToString($state);
            echo "<h1>Testo decifrato: </h1><div class='result-section'><div class='matrix-display'>" . htmlspecialchars($decrypted) . "</div></div>";
        }
        ?>
    </div>
</body>