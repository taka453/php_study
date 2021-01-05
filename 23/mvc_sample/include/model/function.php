<?php

/**
 * insertを実行する
 * 
 * @param obj $link DBハンドル
 * @param str SQL文
 * @return bool
 */

function insert_db($link, $sql)
{
  // クエリを実行する
  if (mysqli_query($link, $sql) === TRUE) {
    if (mysqli_query($link, $sql) === TRUE) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
}

/**
 * 新規商品を追加する
 *
 * @param obj $link DBハンドル
 * @param str $goods_name 商品名
 * @param int $price 価格
 * @return bool
 */
function insert_goods_table($link, $goods_name, $price)
{
  // SQL生成
  $sql = 'INSERT INTO goods_table(goods_name, price) VALUES(\'' . $goods_name . '\', ' . $price . ')';
  // クエリ実行
  return insert_db($link, $sql);
}

/**
 * リクエストメソッド取得
 * @return str GET/POST/PUTなど
 */
function get_request_method()
{
  return $_SERVER['REQUEST_METHOD'];
}

/**
 * POSTデータを取得
 * @param str $key 配列キー
 * @return str POST値
 */
function get_post_data($key)
{
  $str = '';
  if (isset($_POST[$key]) === TRUE) {
    $str = $_POST[$key];
  }
  return $str;
}