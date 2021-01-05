<?php

$host = 'localhost';
$user = 'root';
$passwd = 'root';
$dbname = 'codecamp';
$customer_id = 1;
$payment = 'クレジット';
$quantity = 1;
$goods_list = [];
$err_msg = [];

if($link = mysqli_connect($host, $user, $passwd, $dbname)) {
    mysqli_set_charset($link, 'utf8');
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = date('Y-m-d H:i:s');
        $goods_id = (int)$_POST['goods_id'];
        mysqli_autocommit($link, false);
        $data =
        [
            'customer_id' => $customer_id,
            'order_date' => $date,
            'payment' => $payment
        ];
        // var_dump($data);
        // exit;
        $sql = 'INSERT INTO order_table (customer_id, order_date, payment) VALUES(\'' . implode('\', \'', $data) . '\')';
        if(mysqli_query($link, $sql) === TRUE) {
            $order_id = mysqli_insert_id($link);
            $data = [
                'order_id' => $order_id,
                'goods_id' => $goods_id,
                'quantity' => $quantity
            ];
            $sql = 'INSERT INTO order_detail_table (order_id, goods_id, quantity) VALUES(\'' . implode('\', \'', $data) . '\')';
            if(mysqli_query($link, $sql) !== TRUE) {
                $err_msg[] = 'order_table: insertエラー:' . $sql;
            }
        }
        if(count($error_msg) === 0) {
            mysqli_commit($link);
        } else {
            mysqli_rollback($link);
        }
    }

    $sql = 'SELECT goods_id, goods_name, price FROM goods_table';
    if($result = mysqli_query($link, $sql)) {
        $i = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $goods_list[$i]['goods_id'] = htmlspecialchars($row['goods_id'], ENT_QUOTES, 'UTF-8');
            $goods_list[$i]['goods_name'] = htmlspecialchars($row['goods_name'], ENT_QUOTES, 'UTF-8');
            $goods_list[$i]['price'] = htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8');
            $i++;
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    mysqli_free_result($result);
    mysqli_close($link);
} else {
    $err_msg[] = 'error:' . mysqli_connect_error();
}
var_dump($goods_list);
var_dump($err_msg);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>トランザクションサンプル</title>
</head>
<body>
    <section>
        <h1>商品購入</h1>
        <ul>
            <?php foreach($goods_list as $goods) { ?>
                <li>
                    <span><?php print $goods['goods_name']; ?></span>
                    <span><?php print $goods['price']; ?>円</span>
                    <form method="post">
                        <input type="hidden" name="goods_id" value="<?php print $goods['goods_id']; ?>">
                        <input type="submit" value="購入する">
                    </form>
                </li>
            <?php } ?>
        </ul>
        <p>※サンプルのため購入は1商品＆1個に固定</p>
    </section>
</body>
</html>