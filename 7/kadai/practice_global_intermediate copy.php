<?php
    // notice対策
    $me = '';
    $enemy = '';
    $result = '';
    // 配列化
    $janken = array('グー','チョキ','パー');
    // postが投稿された
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // フォームにてチェックされたデータを取得
        $me = $_POST['janken'];
        // 敵は配列をランダムでじゃんけんデータを取得
        $enemy = $janken[array_rand($janken)];
        if($me === $enemy) {
            $result = 'あいこ';
        } else if($me === 'グー' && $enemy === 'チョキ' || $me === 'チョキ' && $me === 'パー' || $me === 'パー' && $me === 'グー' ) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけんゲーム</title>
</head>
<body>
    <h1>じゃんけん勝負</h1>
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