<?php

$data = date('s');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <p>
        <?php
        if ($data === '00') {
            print 'ジャストタイム';
        } else if ($date % 11 === 0) {
            print 'ソロ目';
        } else {
            print '外れ';
        }
        ?>
    </p>
    <p><?php print 'アクセスした時間は' . $date . '秒でした。' ?></p>
</body>
</html>