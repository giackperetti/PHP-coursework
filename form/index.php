<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form</title>
  </head>
  <body>
    <form action="form.php" method="GET">
      <label for="nome">Nome:</label>
      <input type="text" id="nome" name="nome" required />
      <br />
      <label for="cognome">Cognome:</label>
      <input type="text" id="cognome" name="cognome" required />
      <br />

      <label>Sesso:</label>
      <br />
      <label for="maschio">M</label>
      <input type="radio" id="maschio" name="sesso" value="maschio" required />
      <label for="femmina">F</label>
      <input type="radio" id="femmina" name="sesso" value="femmina" required />
      <br />

      <label for="date">Data di Nascita:</label>
      <br />
      <input type="date" id="date" name="data" required />
      <br />

      <label>Piatti preferiti:</label>
      <br />
      <input type="checkbox" id="pizza" name="piatti[]" value="Pizza" />
      <label for="pizza">Pizza</label>
      <br />
      <input type="checkbox" id="pasta" name="piatti[]" value="Pasta" />
      <label for="pasta">Pasta</label>
      <br />
      <input type="checkbox" id="sushi" name="piatti[]" value="Sushi" />
      <label for="sushi">Sushi</label>
      <br />
      <input type="checkbox" id="gelato" name="piatti[]" value="Gelato" />
      <label for="gelato">Gelato</label>
      <br />

      <label for="country">Paese di Residenza:</label>
      <select id="country" name="paese" required>
        <option value="Italia">Italia</option>
        <option value="Francia">Francia</option>
        <option value="Germania">Germania</option>
        <option value="Spagna">Spagna</option>
      </select>
      <br />
      <br />

      <input type="submit" value="Invia" />
    </form>
  </body>
</html>
