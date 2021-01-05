<?php

$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'codecamp';
$drink_list = array();
$err_msg = array();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['money'])){
        $money = $_POST['money'];
    }
    if(isset($_POST['drink_id'])) {
        $drink_id = $_POST['drink_id'];
    } else {
        $err_msg[] = '商品を選択してください';
    }
    if(count($err_msg) === 0) {
        if(preg_match('/^[0-9]+$/', $money) !== 1) {
            $err_msg[] = '所持金は半角数字で入力してください';
        } else {
            $money = (int)$money;
        }
    }
}
if(count($err_msg) === 0){
    if($link = mysqli_connect($host, $user, $password, $dbname)){
        mysqli_set_charset($link, 'UTF-8');

        $sql = 'SELECT drink_information_table.drink_name,
                    drink_information_table.drink_price,
                    drink_information_table.status,
                    drink_information_table.drink_picture,
                    drink_stock_table.drink_stock
                    FROM drink_information_table JOIN drink_stock_table
                    ON drink_information_table.drink_id = drink_stock_table.drink_id
                    WHERE drink_information_table.drink_id =' . $drink_id;

        if($result = mysqli_query($link, $sql)) {
            $row = mysqli_fetch_assoc($result);
            $drink_list['drink_name'] = $row['drink_name'];
            $drink_list['drink_price'] =(int)$row['drink_price'];
            $drink_list['status'] = (int)$row['status'];
            $drink_list['drink_picture'] = $row['drink_picture'];
            $drink_list['drink_stock'] = (int)$row['drink_stock'];
        }
        if($drink_list['status'] === 0) {
            $err_msg[] = 'ドリンクの購入に失敗しました';
        }
        if($drink_list['drink_stock'] === 0) {
            $err_msg[] = 'ドリンクの購入に失敗しました';
        }
        if(count($err_msg) === 0){
            if($money < $drink_list['drink_price']){
                $err_msg[] = 'お金が足りません';
            }
        }
        if(count($err_msg) === 0) {
            $change = $money-$drink_list['drink_price'];
            $update_stock = $drink_list['drink_stock']-1;
            mysqli_autocommit($link, false);

            $purchase_date = date('Y-m-d H:i:s');
            $data = array(
                    $drink_id,$purchase_date
                );
            $sql = 'INSERT INTO drink_purchase_table(drink_id, purchase_date) VALUES (\'' . implode('\', \'', $data) . '\')';
            if(mysqli_query($link, $sql) !== TRUE) {
                $err_msg[] = 'drink_purchase_table: insertエラー:' . $sql;
            }
            $sql = 'UPDATE drink_stock_table SET drink_stock='. $update_stock .' WHERE drink_id = '. $drink_id;
            if(mysqli_query($link, $sql) !== TRUE) {
                $err_msg[] = 'drink_stock_table: updateエラー:' . $sql;
            }
            if(count($err_msg) === 0) {
                mysqli_commit($link);
            } else {
                mysqli_rollback($link);
                $err_msg[] = '購入失敗';
            }
        }
        mysqli_free_result($result);
        mysqli_close($link);
    } else {
        $err_msg[] = 'error:' . mysqli_connect_error();
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h1>自動販売機結果</h1>
    <?php foreach($err_msg as $message) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
    <?php
        if(count($err_msg) === 0) {
    ?>
    <img src="<?php echo 'img/' . $drink_list['drink_picture']; ?>">
    <p>
        <?php
            if(count($err_msg) === 0) {
            echo 'がしゃん! 【' . $drink_list['drink_name']. '】が買えました！';
        ?>
    </p>
    <p>
        <?php
            echo 'お釣りは【'. $change .' 円です】';
            }
        }
        ?>
    </p>
    <a href="index.php">戻る</a>
</body>
</html>
