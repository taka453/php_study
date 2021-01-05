<?php

$i = 0;
$sum = 0;
while($sum < 1000) {
    $sum += $i;
    $i++;
}
print $i - 1;
print '<br>';
print $sum;

?>