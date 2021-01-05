<?php

// 消費税
define('TAX', 1.05);

// データベースのホスト名又はIPアドレス
define('DB_HOST', 'localhost');
// MySQLのユーザ名
define('DB_USER', 'root');
// MySQLのパスワード
define('DB_PASSWD', 'root');
// データベース名
define('DB_NAME', 'codecamp');

define('HTML_CHARACTER_SET', 'UTF-8');
define('DB_CHARACTER_SET', 'UTF8');

$goods_data = array();

// DB接続
$link = get_db_connect();

// 商品のいちｒなんを取得
$goods_data = get_goods_table_list($link);

// DB切断
close_db_connect($link);

// 商品の値段を税込みに変換
$goods_data = price_before_tax_assoc_array($goods_data);

// 特殊文字をHTMLエンティティに変換
$goods_data = entity_assoc_array($goods_data);

/**
 * 税込み価格へ変換する（端数は切り上げ）
 * @param int $price 税抜く価格
 * @return int 税込み価格
 */
function price_before_tax($price) {
  return ceil($price * TAX);
}

/**
 * 商品の値段を税込みに変換する（配列）
 * @param $assoc_array 税抜き商品一覧配列データ
 * @return array 税込み商品一覧配列データ
 */
function price_before_tax_assoc_array($assoc_array) {
  foreach($assoc_array as $key => $value) {
    $assoc_array[$key]['price'] = price_before_tax($assoc_array[$key]['price']);
  }

  return $assoc_array;
}

/**
 * 特殊文字をHTMLエンティティに変換
 * @param str $str 変換前文字
 * @return str 変換後文字
 */
function entity_str($str) {
  return htmlspecialchars($str, ENT_QUOTES,  HTML_CHARACTER_SET);
}

/**
 * 特殊文字をHTMLエンティティに変換する（2次元配列の値）
 * @param array $assoc_array 変換前配列
 * @return array 変換後配列
 */

function entity_assoc_array($assoc_array) {
  foreach($assoc_array as $key => $value) {
    foreach($value as $keys => $values) {
      // 特殊文字をHTMLエンティティに変換
      $assoc_array[$key][$keys] = entity_str($values);
    }
  }

  return $assoc_array;
}

/**
 * DBハンドルを取得
 * @return obj $link DBハンドル
 */
function get_db_connect() {
  // コネクション取得
  if(!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
    die('error: ' . mysqli_connect_error());
  }

  // 文字コードセット
  mysqli_set_charset($link, DB_CHARACTER_SET);

  return $link;
}

/**
 * DBとのコネクション切断
 * @param obj DBハンドル
 */
function close_db_connect($link) {
  mysqli_close($link);
}

/**
 * クエリを実行しその結果を配列で取得する
 * @param obj $link DBハンドル
 * @param str $sql SQL文
 * @return array 結果配列データ$
 */

function get_as_array($link, $str) {
  //返却用配列
  $data = array();
  // クエリを実行する
  if($result = mysqli_query($link, $sql)) {
    if(mysqli_num_rows($result) > 0) {
      //1件ずつ取り出す
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
    }
    // 結果セットを開放
    mysqli_free_result($result);
  }
  return $data;
}

/**
 * 商品の一覧を取得する
 * @param obj $link DBハンドル
 * @return array 商品一覧配列データ
 */
function get_goods_table_list($link) {
  $sql = 'SELECT goods_name, price FROM goods_table';
  return get_as_array($link, $sql);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ユーザー定義関数</title>
  <style type="text/css">
    table, td, th {
      border: solid black 1px;
    }
    table {
      width: 200px;
    }
    caption {
      text-align: left;
    }
  </style>
</head>
<body>
  <table>
    <caption>商品一覧（税込み）</caption>
      <tr>
        <th>商品名</th>
        <th>価格</th>
      </tr>
      <?php foreach($goods_data as $value) { ?>
        <tr>
          <td><?php print $value['goods_name']; ?></td>
          <td><?php print $value['price']; ?></td>
        </tr>
      <?php } ?>
  </table>
</body>
</html>