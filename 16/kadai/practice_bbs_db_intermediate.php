<?php

$host = 'localhost';
$username = 'root';
$passwd = 'root';
$dbname = 'codecamp';

$name = '';
$comment = '';

$error_message = array();
$bbs_data = array();

$link = mysqli_connect($host, $username, $passwd, $dbname);

if ($link) {
  mysqli_set_charset($link, 'utf8');
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $comment = $_POST['comment'];

    $name = trim(mb_convert_kana($_POST['name'], 's', 'UTF-8'));
    $comment = trim(mb_convert_kana($_POST['comment'], 's', 'UTF-8'));

    $length_name = mb_strlen($name, 'UTF-8');
    $length_comment = mb_strlen($comment, 'UTF-8');

    if ($length_name === 0) {
      $error_message[] = '名前は必須です。';
    } elseif ($length_name > 20) {
      $error_message[] = 'お名前は20文字以下です。';
    }

    if ($length_comment === 0) {
      $error_message[] = 'コメントは必須です。';
    } elseif ($length_comment > 100) {
      $error_message[] = 'コメントは100文字以下です。';
    }

    if (!count($error_message)) {
      $date = date('Y-m-d H:i:s');
      $data = array(
        $name,
        $comment,
        $date
      );
      $implode_array = implode('","', $data);
      $query = 'INSERT INTO bbs (name, comment, date) VALUES ("' .$implode_array . '")';
      $result = mysqli_query($link, $query);
      header('Location: ./practice_bbs_db_intermediate.php');
      exit;
    }
  }
  $query = 'SELECT name, date, comment FROM bbs ORDER BY id ASC';
  $result = mysqli_query($link, $query);

  while ($row = mysqli_fetch_array($result)) {
    $bbs_data[] = $row;
  }
  mysqli_free_result($result);
  mysqli_close($link);
} else {
  $error_message[] = 'DB接続失敗';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ひとこと掲示板</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <?php
    if (count($error_message)) {
      foreach ($error_message as $message) {
        print $message . "<br>";
      }
    }
  ?>
  <form action="" method="POST">
    <label>お名前: <input type="text" name="name"></label>
    <label>コメント: <input type="text" name="comment"></label>
    <input type="submit" value="追加">
  </form>
  <p>発言一覧</p>
  <table>
    <th>お名前</th>
    <th>コメント</th>
    <th>投稿日</th>
    <?php if (empty($bbs_data) === FALSE) { ?>
      <?php foreach($bbs_data as $value) { ?>
        <tr>
          <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php print htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
      <?php } ?>
    <?php } else { ?>
      <tr>
        <td>何もヒットしませんでした。</td>
      </tr>
    <?php } ?>
  </table>
</body>
</html>