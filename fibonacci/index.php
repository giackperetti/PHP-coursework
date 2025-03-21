<?php

$fib_nums = [0, 1];

for ($i = 2; $i < 20; $i++) {
    $fib_num = $fib_nums[$i - 1] + $fib_nums[$i - 2];
    array_push($fib_nums, $fib_num);
}

foreach ($fib_nums as $fib_num) {
    echo "$fib_num ";
}
