<?php
ini_set('display_errors', "On");

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWD', 'root');
define('DB_NAME', 'codecamp');
$thank_mes = '';
$error_message = array();
$bbs_data = array();
$link = connect_db();
// 接続チェック
if ($link) {
    mysqli_set_charset($link, 'utf8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // postされてきた値を受け取る
      $name = del_space($_POST['name']);
      $comment = del_space($_POST['comment']);
      if (chk_text_null($name)) {
        $error_message[] = '名前が未入力です';
      } elseif (chk_text_count($name, 20)) {
        $error_message[] = '名前は20文字以内で入力してください';
      }
      if (chk_text_null($comment)) {
        $error_message[] = 'コメントが未入力です';
      } elseif (chk_text_count($comment, 100)) {
        $error_message[] = 'コメントは100文字以内で入力してください';
      }
      // 正常処理
      if (empty($error_message)) {
          $insert_data = array(
            'name' => $name,
            'comment' => $comment,
            'date' => get_date()
          );
          if (insert_db($link, $insert_data, 'bbs', false) === true) {
            $thank_mes = '書き込みが完了しました';
          } else {
            $error_message[] = 'DB INSERT失敗';
          }
          // if (mysqli_query($link, $sql) === TRUE) {
          //   insert_db($link, $sql);
          // } else {
          //   print '接続失敗しました。';
          // }
      }
      // if (has_name_error($_POST['name'])) {
      //   $error_message[] = has_name_error($_POST['name']);
      // } elseif (has_name_error($_POST['comment'])) {
      //   $error_message[] = has_comment_error($_POST['comment']);
      // } elseif (count($error_message) === 0) {
    }
    // ひとこと情報の取得
    $sql = 'SELECT name, date, comment FROM bbs';
    $bbs_data = select_db($link, $sql);
    // DB接続切断
    close_db($link);
} else {
  $error_message[] = 'DB接続失敗';
}
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
  <?php if (!empty($thank_mes)) { ?>
    <p><?php echo $thank_mes; ?></p>
  <?php } ?>
  <?php
    foreach ($error_message as $message)  {
      print $message . "<br>";
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
      <?php foreach ($bbs_data as $value) { ?>
      <tr>
        <td><?php print entity_str($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print entity_str($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><?php print entity_str($value['date'], ENT_QUOTES, 'UTF-8'); ?></td>      
      </tr>
      <?php } ?>
  </table>
</body>
</html>
<?php
function get_date($format = 'Y-m-d H:i:s')
{
  return date($format);
}
function del_space($val)
{
  return trim(mb_convert_kana($val, 's', 'UTF-8'));
}
function chk_text_null($val)
{
  if (mb_strlen($val) === 0) {
    return TRUE;
  }
  return FALSE;
}
function chk_text_count($val, $count)
{
    if (mb_strlen($val) > $count) {
      return TRUE;
    }
    return FALSE;
}
// DB接続
function connect_db()
{
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
  // mysqli_set_charset($link, 'utf8');
  return $link;
}
function insert_db ($link, $ary, $table_name, $flg = false)
{
  foreach ($ary as $key => $value) {
    $cols[] = '`' . $key . '`';
    $values[] = '\'' . $value . '\'';
  }
  $sql = 'INSERT INTO ' . $table_name . '(' . implode(', ' , $cols) . ') VALUES (' . implode(', ', $values) . ')';
  if ($flg === true) {
    var_dump($ary);
    var_dump($sql);
    die('code end');
  }
  return mysqli_query($link, $sql);
  // $sql = "INSERT INTO bbs (`name`, `comment`, `date`) VALUES ('ムラタ', 'ひとこと', '2021-2-13 11:19:50')";
  // return mysqli_query($link, $sql);
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