<?php

// notice対策
$my_name = '';
// $_SERVER['REQUEST_METHOD']ページがリクエストされたときのリクエストメソッド名を返す
// $_SERVER['REQUEST_METHOD']現在のページにアクセスする際に使用されたメソッド
// postに何かが投稿された
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['my_name']) === TRUE) {
        $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>課題</title>
</head>
<body>
    <?php
        // postで送られた値が入っているかどうかをチェック、空じゃないかを確認
        if(isset($_POST['my_name']) === TRUE && $my_name !== '') {
            print 'ようこそ' . $my_name . 'さん';
        } else {
            print '名前を入力してください';
        }
    ?>
</body>
</html>

