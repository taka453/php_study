<?php
    $me = '';
    $enemy = '';
    $result = '';

    $janken = array('グー', 'チョキ', 'パー');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $me = $_POST['janken'];
        $enemy = $janken[array_rand($janken)];
        if ($me === $enemy) {
            $result = 'あいこ';
        } elseif ($me === 'グー' && $enemy === 'チョキ' || $me === 'チョキ' && $enemy === 'パー' || $me === 'パー' && $enemy === 'グー') {
            $result = '勝ち';
        } else {
            $result = '負け';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>じゃんけんゲーム</title>
</head>
<body>
    <h1>じゃんけんゲーム</h1>
    <p>自分: <?php print $me; ?></p>
    <p>相手: <?php print $enemy; ?></p>
    <p>結果: <?php print $result; ?></p>
    <form action="" method="POST">
        <input type="radio" name="janken" value="グー">グー
        <input type="radio" name="janken" value="チョキ">チョキ
        <input type="radio" name="janken" value="パー">パー
        <br>
        <input type="submit" value="勝負!!">
    </form>
</body>
</html>