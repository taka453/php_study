<?php

$host = 'localhost';
$user = 'root';
$password = 'root';
$dbname = 'codecamp';
$drink_list = array();

if($link = mysqli_connect($host, $user, $password, $dbname)) {
    mysqli_set_charset($link, 'UTF-8');
    $sql = 'SELECT drink_information_table.drink_id,
                    drink_information_table.drink_name,
                    drink_information_table.drink_price,
                    drink_information_table.status,
                    drink_information_table.drink_picture,
                    drink_stock_table.drink_stock
                    FROM drink_information_table JOIN drink_stock_table
                    ON drink_information_table.drink_id = drink_stock_table.drink_id';
    if($result = mysqli_query($link, $sql)) {
        $i = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $status = htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8');
            if($status === '1') {
                $drink_list[$i]['drink_id'] = htmlspecialchars($row['drink_id'], ENT_QUOTES, 'UTF-8');
                $drink_list[$i]['drink_picture'] = htmlspecialchars($row['drink_picture'], ENT_QUOTES, 'UTF-8');
                $drink_list[$i]['drink_name'] = htmlspecialchars($row['drink_name'], ENT_QUOTES, 'UTF-8');
                $drink_list[$i]['drink_price'] = htmlspecialchars($row['drink_price'], ENT_QUOTES, 'UTF-8');
                $drink_list[$i]['drink_stock'] = htmlspecialchars($row['drink_stock'], ENT_QUOTES, 'UTF-8');
                $drink_list[$i]['status'] = htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8');
            }
            $i++;
        }
    } else {
        $err_msg[] = '商品一覧情報取得失敗:' . $sql;
    }
    mysqli_free_result($result);
    mysqli_close($link);
} else {
    $err_msg[] = 'error: ' . mysqli_connect_error();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自動販売機</title>
</head>
<body>
    <h1>自動販売機</h1>
    <section>
        <form method="post" action="result.php">
            <p>金額<input type="text" name="money"></p>
            <table>
                <?php
                    $i = 0;
                    foreach($drink_list as $value) {
                        $i = $i++;
                        if($i % 4 === 1) {
                ?>
                <tr>
                <?php } ?>
                </tr>
                <td>
                    <div id="<?php echo $i; ?>">
                        <img src="<?php echo 'img/' . $value['drink_picture']; ?>">
                        <div class="name"><?php echo $value['drink_name'];?></div>
                        <div class="money"><?php echo $value['drink_price'];?></div>
                        <?php if($value['drink_stock'] >= 1) { ?>
                        <div class="stock">
                            <input type="radio" name="drink_id" value="<?php echo $value['drink_id']; ?>">
                        </div>
                        <?php } ?>
                        <?php if($value['drink_stock'] === '0') { ?>
                            <div class="no_stock">
                                <?php echo '売り切れ'; ?>
                            </div>
                        <?php } ?>
                    </div>
                </td>
                <?php
                    if($i % 4 === 1) {
                ?>
                </tr>
                <?php  }
                ?>
                <?php } ?>
            </table>
            <input type="submit" name="submit" value="購入">
        </form>
    </section>
</body>
</html>


