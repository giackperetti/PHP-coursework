<?php
class Studente
{
  private $nome;
  private $voto;

  public function __construct($nome, $voto)
  {
    $this->nome = $nome;
    $this->voto = $voto;
  }

  public function getNome()
  {
    return $this->nome;
  }

  public function getVoto()
  {
    return $this->voto;
  }
}

class Classe
{
  private $studenti = [];

  public function aggiungiStudente(Studente $studente)
  {
    $this->studenti[] = $studente;
  }

  public function calcolaMedia()
  {
    $totale = 0;
    foreach ($this->studenti as $studente) {
      $totale += $studente->getVoto();
    }
    return $totale / count($this->studenti);
  }

  public function getStudenteMigliore()
  {
    $migliore = $this->studenti[0];
    foreach ($this->studenti as $studente) {
      if ($studente->getVoto() > $migliore->getVoto()) {
        $migliore = $studente;
      }
    }
    return $migliore;
  }

  public function getStudentePeggiore()
  {
    $peggiore = $this->studenti[0];
    foreach ($this->studenti as $studente) {
      if ($studente->getVoto() < $peggiore->getVoto()) {
        $peggiore = $studente;
      }
    }
    return $peggiore;
  }

  public function getStudentiSufficienti()
  {
    $sufficienti = [];
    foreach ($this->studenti as $studente) {
      if ($studente->getVoto() >= 6) {
        $sufficienti[] = $studente;
      }
    }
    usort($sufficienti, function ($a, $b) {
      return strcmp($a->getNome(), $b->getNome());
    });
    return $sufficienti;
  }

  public function getStudentiInsufficienti()
  {
    $insufficienti = [];
    foreach ($this->studenti as $studente) {
      if ($studente->getVoto() < 6) {
        $insufficienti[] = $studente;
      }
    }
    usort($insufficienti, function ($a, $b) {
      return strcmp($a->getNome(), $b->getNome());
    });
    return $insufficienti;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['studenti'])) {
  $classe = new Classe();

  foreach ($_POST['studenti'] as $datiStudente) {
    $nome = $datiStudente['nome'];
    $voto = $datiStudente['voto'];

    if (!empty($nome) && isset($voto)) {
      $studente = new Studente($nome, $voto);
      $classe->aggiungiStudente($studente);
    }
  }

  $media = $classe->calcolaMedia();
  $migliore = $classe->getStudenteMigliore();
  $peggiore = $classe->getStudentePeggiore();
  $sufficienti = $classe->getStudentiSufficienti();
  $insufficienti = $classe->getStudentiInsufficienti();
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistiche Voti Classe</title>
  <script>
    function generaForm() {
      let form = document.getElementById("form-studenti");
      for (let i = 1; i <= 25; i++) {
        let divStudente = document.createElement("div");

        let labelNome = document.createElement("label");
        labelNome.innerHTML = "Studente " + i + " Nome: ";
        let inputNome = document.createElement("input");
        inputNome.type = "text";
        inputNome.className = "campo-testo";
        inputNome.name = "studenti[" + i + "][nome]";
        inputNome.required = true;

        let labelVoto = document.createElement("label");
        labelVoto.innerHTML = " Voto: ";
        let inputVoto = document.createElement("input");
        inputVoto.type = "number";
        inputVoto.className = "campo-testo";
        inputVoto.name = "studenti[" + i + "][voto]";
        inputVoto.min = "2";
        inputVoto.max = "10";
        inputVoto.step = "0.25";
        inputVoto.required = true;

        divStudente.appendChild(labelNome);
        divStudente.appendChild(inputNome);
        divStudente.appendChild(labelVoto);
        divStudente.appendChild(inputVoto);

        form.appendChild(divStudente);
      }

      let pulsanteInvio = document.createElement("input");
      pulsanteInvio.type = "submit";
      pulsanteInvio.value = "Invia Voti";
      pulsanteInvio.className = "invia";
      form.appendChild(pulsanteInvio);
    }
  </script>
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

    .invia,
    .campo-testo {
      color: #cdd6f4;
      background-color: #45475a;
      border: 3px solid #7f849c;
      padding: 5px 10px;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
    }

    .invia:hover,
    .campo-testo:hover {
      border-color: #cba6f7;
    }

    .invia:active,
    .campo-testo:active {
      border-color: #eba0ac;
    }
  </style>
</head>

<body onload="generaForm()">
  <h2>Inserisci Nomi e Voti degli Studenti</h2>
  <form id="form-studenti" action="" method="POST">
  </form>

  <?php if (isset($media)): ?>
    <h2>Statistiche della Classe</h2>
    <p><b>Media Voti:</b> <?= $media; ?></p>
    <p><b>Studente Migliore:</b> <?= $migliore->getNome() . " con voto <em>" . $migliore->getVoto() . "</em>"; ?></p>
    <p><b>Studente Peggiore:</b> <?= $peggiore->getNome() . " con voto <em>" . $peggiore->getVoto() . "</em>"; ?></p>

    <h3>Studenti Sufficienti</h3>
    <ul>
      <?php foreach ($sufficienti as $studente): ?>
        <li><?= "<b>" . $studente->getNome() . "</b> - Voto: <em>" . $studente->getVoto() . "</em>"; ?></li>
      <?php endforeach; ?>
    </ul>

    <h3>Studenti Insufficienti</h3>
    <ul>
      <?php foreach ($insufficienti as $studente): ?>
        <li><?= "<b>" . $studente->getNome() . "</b> - Voto: <em>" . $studente->getVoto() . "</em>"; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>

</html>