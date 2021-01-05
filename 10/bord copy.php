<?php

// 配列を初期化
$error_message = array();

// logテキストを代入
$filename = './log.txt';

// REQUEST_METHOD→ページにアクセスする際に使用されたリクエストのメソッド名
// $_SERVER['REQUEST_METHOD']ページがリクエストされたときのリクエストメソッド名を返す
// $_SERVER['REQUEST_METHOD']現在のページにアクセスする際に使用されたメソッド
// 何かが投稿された
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 文字列の長さを取得
    $length_name = mb_strlen($_POST['name'], 'UTF-8');
    $length_comment = mb_strlen($_POST['comment'],'UTF-8');

    // 全角スペースを半角スペースに変換
    $check_name = mb_convert_kana($_POST['name'], 'UTF-8');
    $check_comment = mb_convert_kana($_POST['comment'],'s','UTF-8');

    // バリデーションを設定する
    if ($length_name === 0) {
        $error_message[] = "お名前は必須です。";
    }
    if ($length_name > 20) {
        $error_message[] = "お名前は20文字以下です。";
    }
    if ($length_comment > 100) {
        $error_message[] = "お名前は100文字以下です。";
    }
    if (ctype_space($check_name)) {
        $error_message[] = "お名前がスペースです。";
    }
    if (ctype_space($check_comment)) {
        $error_message[] = "お名前がスペースです。";
    }
    //配列に要素が入っているかを確認し、入っていればtxtにname、commentを追加する。
    if (!count($error_message)) {
        // 日本のタイムゾーンを設定
        date_default_timezone_set('Asia/Tokyo');

        // formから送信されたname,commentを取得し、代入する。
        $comment = 'お名前: ' . $_POST['name'] . 'コメント: ' . $_POST['comment']  . '発信日時: '  . date('Y年m月d日 H:i:s') . "\n";

        // ファイルを書き込みモードでオープンする。
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            // ファイルを開き、文字列を書き込み
            if (fwrite($fp, $comment) === FALSE) {
                print 'ファイル書き込み失敗' . $filename;
            }
            fclose($fp);
        }
    }
}

// 配列を初期化
$data = array();

// 読み込み可能かどうかを確認する
if (is_readable($filename) === TRUE) {
    // ファイルを読み込みモードで開く
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        // ファイルをFALSEになるまで1行づつ取得する
        while(($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
    <?php
        if (count($error_message)) {
            foreach ($error_message as $message){
                print $message . "<br>";
            }
        }
    ?>
    <form action="" method="POST">
        <label>お名前:</label>
        <input type="text" name="name">
        <label>コメント:</label>
        <input name="comment">
        <input type="submit" value="登録">
    </form>
    <?php foreach($data as $read) { ?>
        <p><?php print $read; ?></p>
    <?php } ?>
</body>
</html>