<style>
table {
    border-collapse: collapse;
    table-layout: fixed;
    width: 600px;
    height: 200px;
}

tr, td {
    border: 1px #282828 solid;
    padding: 0;
    text-align: center;
    width: 15px;
    height: 15px;
}

table tr:nth-child(odd) td:nth-child(odd),
table tr:nth-child(even) td:nth-child(even) {
    background-color: #fb4934;
}

table tr:nth-child(odd) td:nth-child(even),
table tr:nth-child(even) td:nth-child(odd) {
    background-color: #ebdbb2;
}
</style>

<?php
$tombola = [
    [],
    [],
    [],
];
$used_numbers = [];

$rows = 3;
$columns = 9;
$lower_bound = 1;
$upper_bound = 9;

for ($i = 0; $i < $rows; $i++) {
    for ($j = 0; $j < $columns; $j++) {
        do {
            $num = rand($lower_bound, $upper_bound);
        } while (in_array($num, $used_numbers));

        $used_numbers[] = $num;
        $tombola[$i][] = $num;

        $lower_bound += 10;
        $upper_bound += 10;
    }
    $lower_bound = 1;
    $upper_bound = 9;

    $remove_indexes = array_rand($tombola[$i], 4);

    foreach ($remove_indexes as $index) {
        $tombola[$i][$index] = " ";
    }
}

echo "<table>";
for ($i = 0; $i < $rows; $i++) {
    echo "<tr>";
    for ($j = 0; $j < $columns; $j++) {
        echo "<td>" . $tombola[$i][$j] . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
