<?php

$filename = './challenge_file.txt';

// 今のページがPOST or GETによってオープンしたかどうか。
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // date_default_timezoneでタイムゾーンを設定
    date_default_timezone_set('Asia/Tokyo');
    // dateで現在日時を取得、formからcommentを取得
    $comment = date('Y年m月d日 H:i:s') . '' . $_POST['comment'] . "\n";

    // fopenでファイルを開く、書き込みだけを行うのでモードは「a」と指定。
    if(($fp = fopen($filename, 'a')) !== FALSE) {
        // 書き出しが失敗するとFALSEが返ってくる
        if(fwrite($fp, $comment) === FALSE) {
            print 'ファイル書き込み失敗: ' . $filename;
        }
        // ファイルを閉じる
        fclose($fp);
    }
}

// 配列を初期化
$data = array();

// $filenameが読み込み可能かどうかを調べる
if(is_readable($filename) === TRUE) {
    if(($fp = fopen($filename, 'r')) !== FALSE) {
        // fgets関数は最初、オープンしたファイルから1行を読み込む、ファイルポインタを進める。
        // 読み込んだ文字列を$tmpに代入
        // fgetsがFALSEでないうちは4のwhile文の処理内容を実行
        while(($tmp = fgets($fp)) !== FALSE) {
            // 読み込んだ文字列を配列$dataに格納
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
    <title>Document</title>
</head>
<body>
    <h1>課題</h1>
    <form action="#" method="POST">
        <label>発言</label>
        <input type="text" name="comment">
        <input type="submit">
    </form>
    <p>発言一覧</p>
    <?php foreach($data as $read) { ?>
        <p><?php print $read; ?></p>
    <?php } ?>
</body>
</html>