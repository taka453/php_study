<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', 'root');
define('DB_NAME', 'codecamp');

$error_message = array();

function connect_db()
{
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
  mysqli_set_charset($link, 'utf8');
  return $link;
}

function insert_db($link, $sql)
{
  return mysqli_query($link, $sql);
}

function select_db($link, $sql)
{
  $data = array();
  $result = mysqli_query($link, $sql);
  while($row = mysqli_fetch_array($result)) {
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
  if (ctype_space($name)) {
    return '名前が空白文字のみのため、発信できません。';
  } elseif ($name === ''){
    return '名前を入力してください';
  } elseif (mb_strlen($name) > 20) {
    return '名前は20文字以内で入力してくだい';
  }
  return FALSE;
}

function has_comment_error($comment)
{
  if (ctype_space($comment)) {
    return 'コメントが空白文字のみのため、発信できません。';
  } elseif($comment === ''){
    return 'コメントを入力してください';
  } elseif(mb_strlen($comment) > 100) {
    return 'コメントは100文字以内で入力してくだい';
  }
  return FALSE;
}

function entity_str($str)
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$link = connect_db();

if ((isset($_POST['name']) === TRUE) && (isset($_POST['comment']) === TRUE)) {
  if (has_name_error($_POST['name'])) {
    $error_message[] = has_name_error($_POST['name']);
  }
  if (has_comment_error($_POST['comment'])) {
    $error_message[] = has_comment_error($_POST['comment']);
  }
  if (!count($error_message)) {
    $name = $_POST['name'];
    $comment =$_POST['comment'];
    $sql = "INSERT INTO bbs(name, date, comment) VALUES('$name',NOW(),'$comment')";
    insert_db($link, $sql);
  }
}

if ($link) {
  $sql = 'SELECT name, date, comment FROM bbs';
  $data = select_db($link, $sql);
}

close_db($link);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <h1>ひとこと掲示板</h1>
  <?php
    if (count($error_message)) {
      foreach($error_message as $message) {
        print $message . "<br>";
      }
    }
  ?>
  <form action="" method="post">
    <label>お名前:</label>
    <input type="text" name="name">
    <label>コメント:</label>
    <input type="text" name="comment">
    <input type="submit" value="登録">
  </form>
  <p>発言一覧</p>
  <table>
    <tr>
      <th>お名前</th>
      <th>コメント</th>
      <th>投稿日</th>
    </tr>
    <?php if (empty($data) === FALSE): ?>
      <?php foreach($data as $value): ?>
        <tr>
          <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php print htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>
</body>
</html>