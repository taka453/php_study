<?php

$regex_str = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
$regex_pass = '/\A(?=.*?[a-z])(?=.*?\d)[!-~]{5,20}+\z/i';
$messages = array();
$matches = array();
$data = array();
$mail = '';
$password = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 変数に一旦代入して、余計なスペースを取り除くこと。
    // ユーザビリティを意識すること。
    // まず最初に未入力の判定いれること
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    $mail = trim(mb_convert_kana($mail, 's', 'UTF-8'));
    $password = trim(mb_convert_kana($password, 's' ,'UTF-8'));

    if ($mail === '') {
        $messages[] = 'メールアドレスを入力してください。';
    } elseif (preg_match($regex_str, $mail, $matches) === 0){
        $messages[] = 'メールアドレスの形式が正しくありません';
    }

    if ($password === '') {
        $messages[] = 'パスワードを入力してください。';
    } elseif (preg_match($regex_pass, $password, $matches) === 0) {
        $messages[] = 'パスワードは半角英数字記号6文字以上18文字以下で入力してください。';
    }

    if (empty($messages)) {
        $data[] = '登録完了';
    }
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <label for="mail">メールアドレス</label>
        <input type="text" class="block" id="mail" name="mail" value="">
        <label for="password">パスワード</label>
        <input type="password" class="block" id="password" name="password" value="">
        <input type="submit" value="登録">
    </form>
    <?php foreach($data as $values): ?>
        <p><?php print $values; ?></p>
    <?php endforeach; ?>
    <?php foreach($messages as $message): ?>
        <p><?php print htmlspecialchars($message, ENT_QUOTES, 'UTF-8');?></p>
    <?php endforeach; ?>
</body>
</html>
