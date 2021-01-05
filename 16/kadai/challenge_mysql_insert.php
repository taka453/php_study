<?php
    $host = 'localhost';
    $username = 'root';
    $passwd = 'root';
    $dbname = 'DB_study';

    $goods_name = '';
    $goods_price = '';

    $err_msg = array();
    $goods_data = array();

    $txt = '追加したい処品の名前と価格を入力してください';

    $link = mysqli_connect($host, $username, $passwd, $dbname);

    if ($link) {
        mysqli_set_charset($link, 'utf8');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goods_name = $_POST['goods_name'];
            $goods_price = $_POST['goods_price'];

            $goods_name = trim(mb_convert_kana($goods_name, 's', 'UTF-8'));
            $goods_price = trim(mb_convert_kana($goods_price, 's', 'UTF-8'));

            $length_name = mb_strlen($goods_name);
            $length_price = mb_strlen($goods_price);

            if ($length_name === 0) {
                $err_msg[] = '商品名を入力してください。';
            } elseif ($length_name > 100) {
                $err_msg[] = '商品名は100文字以下です。';
            }

            if ($length_price === 0) {
                $err_msg[] = '価格を入力してください。';
            } elseif ($goods_price < 0 && $goods_price > 99999999999) {
                $err_msg[] = '価格は1以上の数字で入力してください。';
            }

            if (!count($err_msg)) {
                $query = 'INSERT INTO goods_table (goods_name, price) VALUES (\'' . $goods_name . '\', ' . $goods_price . ')';
                $result = mysqli_query($link, $query);

                if ($result) {
                    $txt = '追加成功';
                } else {
                    $txt = '追加失敗';
                }
            }
        }
        $query = 'SELECT goods_name, price FROM goods_table ORDER BY goods_id ASC';
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            $goods_data[] = $row;
        }

        mysqli_free_result($result);
        mysqli_close($link);

    } else {
        $err_msg[] = 'DB接続失敗';
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
    <style>
        table, td, th {
            border: solid 1px;
        }
        table {
            width: 300px;
        }
        td, th {
            text-align: center;
        }
    </style>
</head>
<body>
    <?php
        if (count($err_msg)) {
            foreach ($err_msg as $message) {
                print $message . "<br>";
            }
        }
    ?>
    <p><?php print $txt; ?></p>
    <form action="" method="POST">
        <label>商品名: <input type="text" name="goods_name"></label>
        <label>価格: <input type="text" name="goods_price"></label>
        <input type="submit" value="追加">
    </form>
    <table>
        <p>商品一覧</p>
        <tr>
            <th>商品名</th>
            <th>価格</th>
        </tr>
        <?php if(empty($g) === FALSE) { ?>
            <?php foreach ($goods_data as $value) { ?>
                <tr>
                    <td><?php print htmlspecialchars($value['goods_name'], ENT_QUOTES, 'UTF-8' ); ?></td>
                    <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8' ); ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>テーブルに何も格納されていません</td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>