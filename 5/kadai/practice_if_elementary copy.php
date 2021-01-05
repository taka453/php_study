<pre>

<?php
$rand1 = mt_rand(0, 2);
$rand2 = mt_rand(0, 2);

print 'rand1: ' . $rand1 . "\n";
print 'rand2: ' . $rand2 . "\n";

if($rand1 > $rand2) {
    print "rand1のほうが大きい値です";
} else if($rand1 < $rand2) {
    print "rand2のほうが大きい値です";
} else {
    print '2つは同じ値です';
}

?>

</pre>