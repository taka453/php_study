<?php

$error_message = array();
$filename = './log.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    // 名前のバリデーション
    $length_name = mb_strlen($name, 'UTF-8');
    $name = trim(mb_convert_kana($name, 's', 'UTF-8'));
    if ($length_name === 0) {
        $error_message[] = 'お名前の入力は必須です。';
    } elseif ($length_name > 20) {
        $error_message[] = 'お名前は20文字以下です。';
    }
    // コメントのバリデーション
    $length_comment = mb_strlen($comment, 'UTF-8');
    $comment = trim(mb_convert_kana($comment, 's', 'UTF-8'));
    if ($length_comment === 0) {
        $error_message[] = 'コメントの入力は必須です。';
    } elseif ($length_comment > 100) {
        $error_message[] = 'コメントは100文字以下です。';
    }

    if (!count($error_message)) {
        date_default_timezone_set('Asia/Tokyo');

        $bord = 'お名前:' . $name . 'コメント:' . $comment . '発信日時:' . date('Y年m月d日 H:i:s') . "\n";
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $bord) === FALSE) {
                print 'ファイル書き込み失敗' . $filename;
            }
            fclose($fp);
        }
    }
}

$data = array();

if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF_8');
        }
        fclose($fp);
    } else {
        $data[] = 'ファイルがありません';
    }
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
            foreach ($error_message as $message) {
                print $message . '<br>';
            }
        }
    ?>
    <form action="" method="POST">
        <label>お名前: <input type="text" name="name"></label>
        <label>コメント: <input type="text" name="comment"></label>
        <input type="submit" value="登録">
    </form>
    <?php foreach ($data as $value) { ?>
        <p><?php print $value; ?></p>
    <?php } ?>
</body>
</html>