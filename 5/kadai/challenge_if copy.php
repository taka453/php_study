<?php

$rand = mt_rand(1, 6);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>サイコロ</title>
</head>
<body>
    <h1>サイコロ</h1>
    <p>出た数字は<?php print $rand; ?>です。</p>
    <?php if($rand % 2 === 0): ?>
        <p>偶数</p>
    <?php else: ?>
        <p>奇数</p>
    <?php endif; ?>
</body>
</html>