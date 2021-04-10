<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', 'root');
define('DB_NAME', 'codecamp');

$error_message = array();
if ($link) {

} else {
  $error_message[] = '接続市っぱおい'
}

function connect_db()
{
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
  // mysqli_set_charset($link, 'utf8');
  return $link;
}

function insert_db ($link, $sql)
{
  return mysqli_query($link, $sql);
}

function select_db($link, $sql)
{
  $data = array();
  $result = mysqli_query($link, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
  }
  mysqli_free_result($result);
  return $data;
}

function close_db($link)
{
  mysqli_close($link);
}

function has_name_error($name)
{
  if ($name === '') {
    return '名前が空白文字のため、発信できません。';
  } elseif (mb_strlen($name) > 20) {
    return '名前は20文字以内で入力してください';
  }
  return FALSE;
}

function has_commnent_error($comment)
{
  if ($comment === '') {
    return 'コメントが空白文字のため、発信できません。';
  } elseif (mb_strlen($comment) > 100) {
    return 'コメントは100文字以内で入力してください';
  }
  return FALSE;
}

function entity_str($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$link = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (has_name_error($_POST['name'])) {
    $error_message[] = has_name_error($_POST['name']);
  } elseif (has_name_error($_POST['comment'])) {
    $error_message[] = has_comment_error($_POST['comment']);
  } elseif (count($error_message) === 0) {
    $name = $_POST['name'];
    $name = mb_convert_kana($name, 's', 'UTF-8');
    $comment = $_POST['comment'];
    $comment = mb_convert_kana($comment, 's', 'UTF-8');
    $date = date('Y-m-d H:i:s');
    $data = array(
      $name,
      $comment,
      $date
    );
    $implode_array = implode('","', $data);
    $sql = "INSERT INTO bbs(name, comment, date) VALUES('. $implode_array .')";
    insert_db($link, $sql);
    if (mysqli_query($link, $sql) === TRUE) {
      insert_db($link, $sql);
    } else {
      print '接続失敗しました。';
    }
  }
}

if ($link) {
  $sql = 'SELECT name, date, comment, FROM bbs';
  $data = select_db($link, $sql);
}

close_db($link);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <?php
    if (count($error_message)) {
      foreach ($error_message as $message)  {
        print $message . "<br>";
      }
    }
  ?>
  <form action="" method="post">
    <label>お名前: <input type="text" name="name"></label>
    <label>コメント: <input type="text" name="comment"></label>
    <input type="submit" value="登録">
  </form>
  <p>発言一覧</p>
  <table>
    <tr>
      <th>お名前</th>
      <th>コメント</th>
      <th>投稿日</th>
    </tr>
    <?php if (empty($data) === FALSE) { ?>
      <?php foreach ($data as $value) { ?>
        <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8'); ?></td>
      <?php } ?>
    <?php } ?>
  </table>
</body>
</html>