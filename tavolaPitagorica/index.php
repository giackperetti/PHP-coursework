<style>
  table,
  td,
  tr,
  th {
    border-collapse: collapse;
    border: 1px #282828 solid;
  }

  th {
    background-color: #83a598;
  }

  .red {
    background-color: lightcoral;
  }

  .blue {
    background-color: lightblue;
  }
</style>

<?php

define("N", 10);
echo "<table>";

echo "<tr><th>x</th>";
for ($j = 1; $j <= N; $j++) {
  echo "<th>" . $j . "</th>";
}
echo "</tr>";

for ($i = 1; $i <= N; $i++) {
  echo "<tr>";
  echo "<th>" . $i . "</th>";

  for ($j = 1; $j <= N; $j++) {
    $num = ($i * $j);
    $color = ($num % 2 == 0) ? $color = "red" : $color = "blue";
    echo "<td class={$color}>" . $num . "</td>";
  }

  echo "</tr>";
}

echo "</table>";
