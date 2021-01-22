<?php
    $host = 'localhost';
    $user = 'root';
    $password = 'root';
    $dbname = 'codecamp';
    $drink_name = '';
    $drink_price = '';
    $drink_stock = '';
    $drink_picture = '';
    $drink_list = array();
    $err_msg = array();
    $img_dir = './img/';

    if($link = mysqli_connect($host, $user, $password, $dbname)) {
        mysqli_set_charset($link, 'UTF-8');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];

            if($type === 'add') {
                $drink_name = $_POST['name'];
                $drink_price = $_POST['price'];
                $date = date('Y-m-d H:i:s');
                $update_date= date('Y-m-d H:i:s');
                $status = $_POST['status'];
                $drink_stock= $_POST['stock'];
                $stock_update_date = date('Y-m-d H:i:s');

                if($drink_name === '') {
                    $err_msg[] = 'ドリンク名を追加してください。';
                }
                if($drink_price === '') {
                    $err_msg[] = 'ドリンクの値段を追加してください。';
                } else if(preg_match('/^[0-9]+$/', $drink_price) !== 1) {
                    $err_msg[] = 'ドリンクの値段は0円以上にしてください。';
                }
                if($drink_stock === '') {
                    $err_msg[] = 'ドリンクの個数を追加してください。';
                } else if(preg_match('/^[0-9]+$/', $drink_price) !== 1){
                    $err_msg[] = 'ドリンクのこ個数は0個以上にしてください。';
                }

                if($status !== '0' && $status !== '1') {
                    $err_msg[] = 'ドリンクのステータスを追加してください。';
                }
                if(is_uploaded_file($_FILES['picture']['tmp_name'])===TRUE){
                    $drink_picture = $_FILES['picture']['name'];
                    $extension = pathinfo($drink_picture, PATHINFO_EXTENSION);
                    if($extension === 'jpg' || $extension === 'jpeg' || $extension === 'png'){
                        $drink_picture = md5(uniqid(mt_rand(), true)) . '.' . $extension;
                        if(is_file($img_dir . $drink_picture) !== TRUE){
                            if(move_uploaded_file($_FILES['picture']['tmp_name'], $img_dir . $drink_picture) !== TRUE){
                                $err_msg[] = 'ファイルアップロードに失敗しました。';
                            }
                        } else {
                            $err_msg[] = 'ファイルに失敗しました。再度お試しください。';
                        }
                    } else {
                        $err_msg[] = 'ファイルの形式が異なります。';
                    }
                } else {
                    $err_msg[] = 'ファイルを選択してください。';
                }
                if(count($err_msg) === 0) {
                    mysqli_autocommit($link, false);

                    $data = array(
                                $drink_name, $drink_price, $date, $update_date, $status, $drink_picture
                            );
                    $sql = 'INSERT INTO drink_information_table(drink_name, drink_price, date, update_date, status, drink_picture) VALUES(\''. implode('\',\'', $data) . '\')';
                    if(mysqli_query($link, $sql) !== TRUE){
                        $err_msg[] = 'drink_master: insertエラー: .' . $sql;
                    }
                    $drink_id = mysqli_insert_id($link);
                    $data = array(
                        $drink_id, $drink_stock, $date, $stock_update_date
                    );
                    $sql = 'INSERT INTO drink_stock_table(drink_id, drink_stock, date, stock_update_date) VALUES(\'' . implode('\',\'', $data) . '\')';
                    if(mysqli_query($link, $sql) !== TRUE) {
                        $err_msg[] = 'drink_stock_table: insertエラー: .' . $sql;
                    }
                    if(count($err_msg) === 0) {
                        mysqli_commit($link);
                        echo '追加成功';
                    } else {
                        mysqli_rollback($link);
                        $err_msg[] = '追加失敗';
                    }
                }
            } else if($type === 'stock') {
                $update_drink_id = $_POST['drink_id'];
                $update_stock = $_POST['update_stock'];
                $stock_update_date = date('Y-m-d H:i:s');

                if(preg_match('/^[0-9]+$/', $update_stock) === 1){
                    $sql = 'UPDATE drink_stock_table SET drink_stock = ' . $update_stock . ', stock_update_date=' . "'" . $stock_update_date . "'" . 'WHERE drink_id=' . $update_drink_id;
                    if(mysqli_query($link, $sql) !== TRUE) {
                        $err_msg[] = 'drink_stock: updateエラー:' . $sql;
                    }
                    if(count($err_msg) === 0){
                        echo '在庫更新成功';
                    }
                } else {
                    $err_msg[] = '不正なパラメータが送信されました。';
                }
            } else if($type === 'status') {
                $update_drink_id = $_POST['drink_id'];
                $update_status = $_POST['update_status'];
                $status_update_date = date('Y-m-d H:i:s');
                if($update_status === '非公開->公開') {
                    $update_status = 1;
                } else if($update_status === '公開->非公開') {
                    $update_status = 0;
                } else {
                    $err_msg[] = '不正なパラメータが送信されました。';
                }
                if(count($err_msg) === 0) {
                    $sql = 'UPDATE drink_information_table SET status = ' . $update_status . ',update_date = ' . "'" . $status_update_date . "'" . 'WHERE drink_id=' . $update_drink_id;
                    if(mysqli_query($link, $sql) !== TRUE) {
                        $err_msg[] = 'drink_information_table: updateエラー:' . $sql;
                    }
                    if(count($err_msg) === 0) {
                        echo 'ステータス更新成功';
                    }
                }
            }
        }

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
                $drink_list[$i]['drink_id'] = $row['drink_id'];
                $drink_list[$i]['drink_picture'] = $row['drink_picture'];
                $drink_list[$i]['drink_name'] = $row['drink_name'];
                $drink_list[$i]['drink_price'] = $row['drink_price'];
                $drink_list[$i]['drink_stock'] = $row['drink_stock'];
                $drink_list[$i]['status'] = (int)$row['status'];
                $i++;
            }
        } else {
            $err_msg[] = '商品一覧情報取得失敗:' . $sql;
        }
        mysqli_free_result($result);
        mysqli_close($link);
    } else {
        $err_msg[] = 'error:' . mysqli_connect_error();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>自動販売機</title>
</head>
<body>
    <?php foreach($err_msg as $value): ?>
        <p><?php echo $value; ?></p>
    <?php endforeach; ?>
    <h1>自動販売機管理ツール</h1>
    <section>
        <h2>新規商品追加</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="add">
            <p>名前:<input type="text" name="name"></p>
            <p>値段:<input type="text" name="price"></p>
            <p>個数:<input type="text" name="stock"></p>
            <p><input type="file" name="picture" value="ファイルを選択" accept="image/jpeg", "image/png"></p>
            <select name="status">
                <option value="0">非公開</option>
                <option value="1">公開</option>
            </select>
            <p><input type="submit" name="submit" value="商品追加"></p>
        </form>
    </section>
    <hr>
    <section>
        <h2>商品情報変更</h2>
        <p>商品一覧</p>
            <table>
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>ステータス</th>
                </tr>
                <?php foreach($drink_list as $value): ?>
                    <tr>
                        <td><img src="<?php echo 'img/' . htmlspecialchars($value['drink_picture'], ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <td><?php echo  htmlspecialchars($value['drink_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo  htmlspecialchars($value['drink_price'], ENT_QUOTES, 'UTF-8'); ?>円</td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="type" value="stock">
                                <input type="hidden" name="drink_id" value="<?php echo $value['drink_id']; ?>">
                                <input name="update_stock" value="<?php echo $value['drink_stock']; ?>">個
                                <input type="submit" name="submit" value="変更">
                            </form>
                        </td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="type" value="status">
                                <input type="hidden" name="drink_id" value="<?php echo $value['drink_id']; ?>">
                                <input type="submit" name="update_status" value="<?php if($value['status'] === 1) {
                                    echo '公開->非公開';
                                } else {
                                    echo '非公開->公開';
                                }?>">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
    </section>
</body>
</html>