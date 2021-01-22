<?php

$host = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'codecamp';

$post_number = '';
$pref = '';
$city = '';

$data = array();
$error_message = array();
$pattern = '/^[0-9]{7}$/';

$page = 1;

$link = mysqli_connect($host, $username, $password, $dbname);

if ($link) {
  mysqli_set_charset($link, 'utf8');
  if (isset($_GET['post_number']) === TRUE) {
    $post_number = trim($_GET['post_number']);
    if ($post_number === '') {
      $error_message[] = '郵便番号を入力してください';
    } elseif (preg_match($pattern, $post_number) === 0) {
      $error_message[] = '郵便番号は7桁の数字を入力してください';
    }

    if (empty($error_message)) {
      $query = "SELECT `COL 1`, `COL 5`, `COL 6`, `COL 7` FROM post_number_table WHERE `COL 1` = '$post_number' ORDER BY `COL 1` ASC";
    }
  }

  if ((isset($_GET['pref']) === TRUE) && (isset($_GET['city']) === TRUE)) {
    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    }
    $pref = $_GET['pref'];
    $city = $_GET['city'];

    $pref = trim(mb_convert_kana($pref, 's', 'UTF-8'));
    $city = trim(mb_convert_kana($city, 's', 'UTF-8'));

    if ($pref === '') {
      $error_message[] = '都道府県を入力してください。';
    }

    if ($city === '') {
      $error_message[] = '市区町村を入力してください。';
    }

    $limit = ($page - 1) * 10;
    if (empty($error_message)) {
      $max_count = "SELECT COUNT(`COL 1`) AS max_count FROM post_number_table WHERE `COL 5` = '$pref' AND `COL 6` = '$city'";
      $result = mysqli_query($link, $max_count);
      $row = mysqli_fetch_array($result);
      $max_count = $row['max_count'];
      $query = "SELECT `COL 1`, `COL 5`, `COL 6`, `COL 7` FROM post_number_table WHERE `COL 5` = '$pref' AND `COL 6` = '$city' ORDER BY `COL 1` ASC LIMIT $limit, 10";
    }
  }
  $result = mysqli_query($link, $query);
  while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
  }

  mysqli_free_result($result);
  mysqli_close($link);
} else {
  $error_message[] = '接続不可';
}

$page_link = './practice_post_code_advanced.php?pref='.$pref.'&city='.$city.'&search_method=1&page=';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>郵便番号検索</title>
</head>
<body>
  <h1>郵便番号検索</h1>
  <p>郵便番号から検索</p>
  <form action="" method="get">
    <input type="text" name="post_number">
    <input type="hidden" name="page" value="1">
    <input type="submit" value="検索">
  </form>
  <p>地名から検索</p>
  <form action="" method="get">
    都道府県を選択<input type="text" name="pref">
    市区町村<input type="text" name="city">
    <input type="hidden" name="search_method" value="1">
    <input type="submit" value="検索">
  </form>
  <hr>
  <?php if (count($data) === 0) { ?>
    ここに検索結果が表示されます。
  <?php } ?>

  <?php if (count($error_message) >= 1) { ?>
    <ul>
      <?php foreach($error_message as $message) { ?>
        <li><?php print $message; ?></li>
      <?php } ?>
    </ul>
  <?php } ?>

  <?php if ($max_count >= 1) { ?>
    検索結果 <?php print $max_count; ?>件(<?php print $page; ?>ページ)
    <br><br>
    郵便番号検索結果

    <table border="1">
      <tr>
        <th>郵便番号</th>
        <th>都道府県</th>
        <th>市区町村</th>
        <th>町域</th>
      </tr>

      <?php foreach ($data as $value) { ?>
        <tr>
          <td><?php print $value[0]; ?></td>
          <td><?php print $value[1]; ?></td>
          <td><?php print $value[2]; ?></td>
          <td><?php print $value[3]; ?></td>
        </tr>
      <?php } ?>
    </table>

    <?php if ($page >= 2) { ?>
      <a href="<?php print $page_link ?><?php print $page - 1?>">前へ</a>
    <?php } ?>
    <?php if ($max_count > $page * 10) { ?>
      <a href="<?php print $page_link ?><?php print $page + 1?>">次へ</a>
    <?php } ?>
  <?php } ?>
</body>
</html>