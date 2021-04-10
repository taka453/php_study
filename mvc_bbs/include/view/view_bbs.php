<?php

require_once '../../htdocs/bbs_mvc.php';

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