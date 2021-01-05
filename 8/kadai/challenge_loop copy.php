<?php

for($i = 1; $i <= 100; $i++) {
    if($i % 3 === 0 && $i % 5 === 0) {
        print 'FIZZBUZZ';
        print '<br>';
    } else if($i % 3 === 0) {
        print 'FIZZ';
        print '<br>';
    } else if($i % 5 === 0) {
        print 'BUZZ';
        print '<br>';
    } else {
        print $i;
        print '<br>';
    }
}

?>