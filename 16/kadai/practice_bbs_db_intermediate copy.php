<?php

// 配列を初期化
$error_message = array();
$bbs_data = array();

$host = 'localhost';
$username = 'root';
$passwd = 'root';
$dbname = 'codecamp';

$name = '';
$comment = '';

$link = mysqli_connect($host, $username, $passwd, $dbname);

if ($link) {
  mysqli_set_charset($link, 'utf8');
  // REQUEST_METHOD→ページにアクセスする際に使用されたリクエストのメソッド名
  // $_SERVER['REQUEST_METHOD']ページがリクエストされたときのリクエストメソッド名を返す
  // $_SERVER['REQUEST_METHOD']現在のページにアクセスする際に使用されたメソッド
  // POSTで何かが投稿された
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          // postで受け取ったものを変数に代入
          $name = $_POST['name'];
          $comment = $_POST['comment'];

          // カナ変換。全角スペースを半角スペースに変換
          // 先頭および末尾にあるスペースを削除するためtrim関数を使う
          $name = trim(mb_convert_kana($name, 's', 'UTF-8'));
          $comment = trim(mb_convert_kana($comment, 's' ,'UTF-8'));

          // 文字列の長さを取得
          $length_name = mb_strlen($name, 'UTF-8');
          $length_comment = mb_strlen($comment, 'UTF-8');

          // バリデーションを設定する
          // 名前のバリデーション
          if ($length_name === 0) {
            $error_message[] = 'お名前は必須です。';
          } elseif ($length_name > 20) {
            $error_message[] = 'お名前は20文字以下です。';
          }

          // コメントのバリデーション
          if ($length_comment === 0) {
            $error_message[] = 'コメントは必須です。';
          } elseif ($length_comment > 20) {
            $error_message[] = 'コメントは100文字以下です。';
          }

          // バリデーションでエラーがなければ、SQLを実行する。
          if (!count($error_message)) {
              // VALUESは関数を使うと簡易になる,implode関数（配列をした文字区切る）explode
              // スーパーグローバル変数をそのまま使うのは良くない
              // バリデーションしたものを変数に代入しないと駄目。生データがそのままになってしまっている。
              // 加工後のものをデータにいれる
              // dateの実行する場所、INSERTの直前で書く、役立てる場所で定義すること
              $date = date('Y-m-d H:i:s');
              // ダブルコーテーションに変更
              // implodeを使いたいが認識されず・・・
              $data = array(
                $name,
                $comment,
                $date
              );
              // こういう形で使う方法がある、クエリが増えると大変になる
              // $implode_array = '"'. implode('","', $data) . '"';
              $implode_array = implode('","', $data);
              // $query = 'INSERT INTO bbs(name, comment, date) VALUES(\'' . $name .'\',\'' . $comment .'\',\'' . $date .'\')';
              // $query = 'INSERT INTO bbs(name, comment, date) VALUES("' . $name .'","' . $comment .'","' . $date . '")';
              $query = 'INSERT INTO bbs(name, comment, date) VALUES("' .$implode_array . '")';
              $result = mysqli_query($link, $query);
              header('Location: ./practice_bbs_db_intermediate.php');
              exit;
          }
    }
    $query = 'SELECT id, name, date, comment FROM bbs ORDER BY id ASC';
    $result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)){
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
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>
    <?php
        if (count($error_message)) {
            foreach ($error_message as $message){
                print $message . "<br>";
            }
        }
    ?>
    <form action="" method="POST">
        <label>お名前:</label>
        <input type="text" name="name">
        <label>コメント:</label>
        <input type="text" name="comment">
        <input type="submit" value="登録">
    </form>
    <p>発言一覧</p>
    <table>
      <th>お名前</th>
      <th>コメント</th>
      <th>投稿日</th>
      <?php if (empty($bbs_data) === FALSE): ?>
        <?php foreach ($bbs_data as $value): ?>
          <tr>
            <td><?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['date'], ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td>何もヒットしませんでした。</td>
        </tr>
      <?php endif; ?>
    </table>
</body>
</html>