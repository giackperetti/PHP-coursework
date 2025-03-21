<?php
define("N_LIBRI", 4);

function calcolaNumeroPagineMedio($libri)
{
  $somma = 0;

  foreach ($libri as $datiLibro) {
    $somma += $datiLibro['numero_pagine'];
  }

  return $somma / count($libri);
}

function trovaLibroPiuLungo($libri)
{
  $libroPiuLungo = $libri[0];

  foreach ($libri as $datiLibro) {
    if ($datiLibro['numero_pagine'] > $libroPiuLungo['numero_pagine']) {
      $libroPiuLungo = $datiLibro;
    }
  }

  return $libroPiuLungo;
}

function trovaLibroPiuCorto($libri)
{
  $libroPiuCorto = $libri[0];

  foreach ($libri as $datiLibro) {
    if ($datiLibro['numero_pagine'] < $libroPiuCorto['numero_pagine']) {
      $libroPiuCorto = $datiLibro;
    }
  }

  return $libroPiuCorto;
}

function influenzaTotalePagine($libri)
{
  $totalePagine = 0;

  foreach ($libri as $datiLibro) {
    $totalePagine += $datiLibro['numero_pagine'];
  }

  for ($i = 0; $i <= N_LIBRI; $i++) {
    $libri[$i]['percentuale'] = round(($libri[$i]['numero_pagine'] / $totalePagine) * 100);
  }

  return $libri;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST)) {
  $libri = [];

  for ($i = 0; $i <= N_LIBRI; $i++) {
    $libri[$i]['titolo'] = $_REQUEST['titolo' . $i];
    $libri[$i]['autore'] = $_REQUEST['autore' . $i];
    $libri[$i]['numero_pagine'] = $_REQUEST['numero_pagine' . $i];
  }

  $mediaNumeroPagine = calcolaNumeroPagineMedio($libri);
  $libroPiuLungo = trovaLibroPiuLungo($libri);
  $libroPiuCorto = trovaLibroPiuCorto($libri);
  $libriConPercentuali = influenzaTotalePagine($libri);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peretti Giacomo - Verifica 23/10/2024 - Fila C</title>
  <style>
    * {
      background-color: #1e1e2e;
      color: #cdd6f4;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      color: #f9e2af;
    }

    input {
      border: 2px solid #f38ba8;
      padding: 5px 10px;
    }

    input:hover {
      border-color: #fab387
    }

    .barra-percentuale {
      background-color: #74c7ec;
      height: 15px;
    }
  </style>
</head>

<body>
  <h1>Dati Iniziali Libri</h1>
  <form action="" method="POST">
    <?php for ($i = 0; $i <= N_LIBRI; $i++): ?>
      <div>
        <h2>Libro <?= ($i + 1) ?>:</h2>
        <label for="<?= "titolo" . $i ?>"><b>Titolo:</b></label>
        <br />
        <input type="text" name="<?= "titolo" . $i ?>" required>
        <br />
        <label for="<?= "autore" . $i ?>"><b>Autore:</b></label>
        <br />
        <input type="text" name="<?= "autore" . $i ?>" required>
        <br />
        <label for="<?= "numero_pagine" . $i ?>" required><b>Numero di Pagine:</b></label>
        <br />
        <input type="number" name="<?= "numero_pagine" . $i ?>" min="0" required>
        <br />
      </div>
    <?php endfor; ?>
    <br />
    <br />
    <input type="submit" value="Invia">

    <h1>Dati Elaborati Libri</h1>
    <?php if (isset($mediaNumeroPagine)): ?>
      <h4>Media Numero di Pagine: </h4>
      <?= $mediaNumeroPagine ?>

      <h4>Libro Più Lungo: </h4>
      <?=
      "<b>" . $libroPiuLungo['titolo'] .
        "</b> scritto da " . "<em>" . $libroPiuLungo['autore'] .
        "</em> con <em>" . $libroPiuLungo['numero_pagine'] .
        "</em> pagine"
      ?>

      <h4>Libro Più Corto</h4>
      <?=
      "<b>" . $libroPiuCorto['titolo'] .
        "</b> scritto da " . "<em>" . $libroPiuCorto['autore'] .
        "</em> con <em>" . $libroPiuCorto['numero_pagine'] .
        "</em> pagine"
      ?>

      <h4>Percentuale Pagine sul Totale:</h4>
      <div>
        <?php foreach ($libriConPercentuali as $libro): ?>
          <?= "<b>" . $libro['titolo'] . "</b> - " . $libro['numero_pagine'] . "pg. - " . $libro['percentuale'] . "%" ?>
          <div class="barra-percentuale" style="<?= "width: " . $libro['percentuale'] . "%" ?>"></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </form>
</body>

</html>