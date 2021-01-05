<?php

$date = date('s');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>
        <?php
        if($date === '00') {
            print 'ジャストタイム';
        // } else if($date === '11' || $date === '22' || $date === '33' || $date === '44' || $date === '55') {
        } else if($date % 11 === 0) {
            print 'ゾロ目';
        } else {
            print '外れ';
        }
        ?>
    </p>
    <p><?php print 'アクセスした時間は' . $date . '秒でした。'; ?></p>
</body>
</html>