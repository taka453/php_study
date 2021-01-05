<?php
    $my_name = '';
    $gender = '';
    $mail = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['my_name']) === TRUE && isset($_POST['gender']) === TRUE && isset($_POST['mail']) === TRUE){
            $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
            $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
            $mail= htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
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
    <?php print 'ここに入力したお名前を表示:' . $my_name; ?><br>
    <?php print 'ここに選択した性別を表示:' . $gender; ?><br>
    <?php print 'ここにメールを受け取るかを表示:' . $mail; ?>

    <h1>課題</h1>
    <form method="post">
        <label>お名前: </label>
        <input id="my_name" type="text" name="my_name">
        <br>
        <label>性別: </label>
        <input type="radio" name="gender" value="男">男
        <input type="radio" name="gender" value="女">女
        <br>
        <input type="checkbox" name="mail" value="OK">お知らせメールを受け取る
        <br>
        <input type="submit">
    </form>
</body>
</html>