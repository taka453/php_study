<?php

require_once '../../include/conf/const.php';
require_once '../../include/model/model_bbs.php';
include_once '../../include/view/view_bbs.php';

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
      }
    }
    // ひとこと情報の取得
    $sql = 'SELECT name, date, comment FROM bbs';
    $bbs_data = select_db($link, $sql);
    // DB接続切断
    close_db($link);
} else {
  $error_message[] = 'DB接続失敗';
}

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

function entity_str($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}