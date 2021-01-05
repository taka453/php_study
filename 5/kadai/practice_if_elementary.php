<?php

$rand1 = mt_rand(0, 2);
$rabd2 = mt_rand(0, 2);

print 'rand1: ' . $rand1 . "\n";
print 'rand2: ' . $rand1 . "\n";

if ($rand1 > $rand2) {
    print 'rand1にほうが大きい値です';
} elseif ($rand1 < $rand2) {
    print 'rand2のほうが大きい値です';
} else {
    print '2つは同じ値です';
}

