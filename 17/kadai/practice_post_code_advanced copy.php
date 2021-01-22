<?php

// phpmyadminを使ってSQLでvardumpでデバックした文を実行してみる。
// http://localhost:8888/codecamp/17/kadai/practice_post_code_advanced.php?pref=%E5%8C%97%E6%B5%B7%E9%81%93&city=%E6%9C%AD%E5%B9%8C%E5%B8%82%E4%B8%AD%E5%A4%AE%E5%8C%BA&search_method=1
// getで今のページを渡す
// 前後のページを取得する

// ---詰まっている所---
// 北海道札幌市中央区を検索した場合、総件数89件を取得する必要があるが10件しか表示されない。次へいくと30件目で何も表示されなくなる。
// 改善策としては総件数を取得し、LIMIT句で指定すればうまくいくはずだが現時点ではうまくいかず。

// ---最終ページの次へ表示させないための記述の案---
// 最大何ページあるのかを求める SELECT COUNT(*) as cnt FROM post_number_table
// 1ページ目に10件表示される。最後ページにて少数を切り上げるための式 ページ数($max_page) = ceil($count['cnt] / 10)
// htmlの次へのところで条件分岐する if ($page < $max_page) { <a href=""></a> }

// ---改修案---
// 部分一致とプルダウン

$host = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'codecamp';

$post_number = '';
$pref = '';
$city = '';

$query = '';
$data = array();
$error_message = array();
$pattern = '/^[0-9]{7}$/';

// 今開いているページ
$page = 1;

$link = mysqli_connect($host, $username, $password, $dbname);

if($link) {
  mysqli_set_charset($link, 'utf8');
    // 郵便番号からの検索でのバリデーション
    if (isset($_GET['post_number']) === TRUE) {
    // 空白を取り除くためtrim関数を使う
    $post_number = trim($_GET['post_number']);
    if ($post_number === '') {
      $error_message[] = '郵便番号を入力してください';
    } elseif (preg_match($pattern, $post_number) === 0) {
      $error_message[] = '郵便番号は7桁の数字を入力してださい。';
    }

    if (empty($error_message)) {
      $query = "SELECT `COL 1`, `COL 5`, `COL 6`, `COL 7` FROM post_number_table WHERE `COL 1` = '$post_number' ORDER BY `COL 1` ASC";
    }
  }

  if ((isset($_GET['pref']) === TRUE) && (isset($_GET['city']) === TRUE)) {
    // pageをgetで受け取る、issetでpageが渡っているかを確認し、変数に代入
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
      // post_number_tableの総件数を取得
      $query_count = "SELECT COUNT(`COL 1`) AS max_count FROM post_number_table WHERE `COL 5` = '$pref' AND `COL 6` = '$city'";
      $result = mysqli_query($link, $query_count);
      $row = mysqli_fetch_array($result);
      $max_count = $row['max_count'];
      var_dump($max_count);
      // var_dump($max_count);
      // 10件を取得
      // 総件数89件を取得する必要があるが10件しか表示されない。次へいくと30件目で何も表示されなくなる
      // LIMITはスタート位置と件数を「,」で区切って指定できる。0,10の場合は0番目から10件になる。
      $query = "SELECT `COL 1`, `COL 5`, `COL 6`, `COL 7` FROM post_number_table WHERE `COL 5` = '$pref' AND `COL 6` = '$city' ORDER BY `COL 1` ASC LIMIT $limit, 10";
      var_dump($query);
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

// 相対パス、絶対パスでどちらでも良い
// inputテキストより入力されたものを$page_linkに代入。prefとcityを文字連結、それぞれに値がURLに表示される
$page_link = './practice_post_code_advanced.php?pref='.$pref.'&city='.$city.'&search_method=1&page=';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>郵便番号検索</title>
</head>
<body>
  <h1>郵便番号検索</h1>
  <h3>郵便番号から検索</h3>
  <form method="get">
    <input type="text" name="post_number">
    <!-- ブラウザ上に表示されない非表示データを送信することができます。 -->
    <input type="hidden" name="page" value="1">
    <input type="submit" value="検索">
  </form>
  <h3>地名から検索</h3>
    <form method="get">
      都道府県を選択<input type="text" name="pref">
      市町村<input type="text" name="city">
      <input type="hidden" name="search_method" value="1">
      <input type="submit" value="検索">
    </form>
    <hr>
    <?php if (count($data) === 0) : ?>
      ここに検索結果が表示されます。
    <?php endif; ?>

    <?php if (count($error_message) >= 1) : ?>
      <ul>
        <?php foreach($error_message as $value): ?>
          <li><?php print $value; ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if ($max_count >= 1) { ?>
      検索結果 <?php print $max_count; ?>件(<?php print $page; ?> ページ)
      <br><br>
      郵便番号検索結果

      <table border="1">
        <tr>
          <th>郵便番号</th>
          <th>都道府県</th>
          <th>市区町村</th>
          <th>町域</th>
        </tr>

        <?php foreach($data as $value) { ?>
          <tr>
              <td><?php print $value[0]; ?></td>
              <td><?php print $value[1]; ?></td>
              <td><?php print $value[2]; ?></td>
              <td><?php print $value[3]; ?></td>
          </tr>
        <?php } ?>
      </table>

      <!-- ページが2ページ目以上の場合に前へのリンクを表示させる -->
      <!-- スタート位置 = (ページ数-1)で表示させる -->
      <!-- printがないと表示されないので注意 -->
      <?php if($page >= 2) { ?>
        <a href="<?php print $page_link ?><?php print $page - 1 ?>">前へ</a>
      <?php } ?>
      <?php if ($max_count > $page * 10) { ?>
        <a href="<?php print $page_link ?><?php print $page + 1 ?>">次へ</a>
      <?php } ?>
    <?php } ?>
</body>
</html>

