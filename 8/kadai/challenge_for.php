<?php

$sum = 0;
for ($i = 1; $i <= 100; $i++) {
    if ($i % 3 === 0) {
        $sum +=3;
    }
}
print $sum;

?>