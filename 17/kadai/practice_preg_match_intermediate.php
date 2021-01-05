<?php

$regex_str = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';
$regex_pass = '/\A(?=.*?[a-z])(?=.*?\d)[!-~]{5,19}+\z/i';
$error_message = array();
$matches = array();
$data = array();
$mail = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    $mail = trim(mb_convert_kana($mail, 's', 'UTF-8'));
    $password = trim(mb_convert_kana($password, 's', 'UTF-8'));

    if ($mail === '') {
        $error_message[] = 'メールアドレスを入力してください';
    } elseif (preg_match($regex_str, $mail, $matches) === 0) {
        $error_message[] = 'メールアドレスの形式が正しくありません';
    }

    if ($password === '') {
        $error_message[] = 'パスワードを入力してください。';
    } elseif (preg_match($regex_pass, $password, $matches) === 0) {
        $error_message[] = 'パスワードは半角英数字記号6文字以上18文字以下で入力してください';
    }

    if (empty($error_message)) {
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
    <form action="" method="POST">
        <label>メールアドレス <input type="text" name="mail"></label>
        <label>パスワード <input type="password" name="password"></label>
        <input type="submit" value="登録">
    </form>
    <?php foreach($data as $value) { ?>
        <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
    <?php foreach($error_message as $message) { ?>
        <p><?php print htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>
</body>
</html>