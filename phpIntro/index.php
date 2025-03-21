<body>
  <h1>Intro PHP</h1>
</body>

<?php
print("Salve mondo!<br>");
$num = 23;

if ($num % 2 == 0) {
    $tipo = "pari";
} else {
    $tipo = "dispari";
}

print("il numero $num e' $tipo<br><br>");


$indexed_cars = array("Volvo", "Fiat", "Cadillac");
echo "<b>Indexed Cars: </b><br>";
foreach ($indexed_cars as $car) {
    echo "$car<br>";
}
echo "<br>";


$associative_cars = array(
    "Volvo" => "V40",
    "Fiat" => "Punto",
    "Cadillac" => "CT4"
);
echo "<b>Associatie Cars: </b><br>";
foreach ($associative_cars as $brand => $model) {
    echo "$brand -> $model<br>";
}
echo "<br>";

?>
