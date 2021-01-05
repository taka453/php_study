<?php
define('MAX_FILE_SIZE', 30000);
require_once('../../include/conf/const.php');
require_once('../../include/model/model_drink.php');

$name = '';
$price = 0;
$quantity = 0;
$image_path = '';
$public_status = '';
$errors = [];
$result_msg = [];

$link = get_db_link();
///////////////////////////////////////
// 新規商品追加
///////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['change_status'] === 'create') {
    // エラー処理
    if (isset($_POST['name']) === FALSE || trim($_POST['name']) === "") {
        $errors[] = '名前を入力してください。';
    }
    if (isset($_POST['price']) === FALSE || $_POST['price'] === "") {
        $errors[] = '値段を入力してください。';
        // ctype_digit : stringがすべて数字なら、小数点もマイナスもないので、必然的に整数
    } elseif (ctype_digit($_POST['price']) === FALSE) {
        $errors[] = '値段は0以上の整数を入力してください。';
    }

    if (isset($_POST['stock']) === FALSE || $_POST['stock'] === "") {
        $errors[] = '個数を入力してください。';
    } elseif (ctype_digit($_POST['stock']) === FALSE) {
        $errors[] = '個数は0以上の整数を入力してください。';
    }

    // error = 0 のみ成功
    // https://www.php.net/manual/ja/features.file-upload.errors.php
    if (isset($_FILES['image']) === FALSE || $_FILES['image']['error'] !== 0) {
        $errors[] = '商品画像を選択してください。';
    } elseif ($_FILES['image']['type'] !== 'image/jpeg' && $_FILES['image']['type'] !== 'image/png') {
        $errors[] = '商品画像のファイル形式は、JPEGかPNG にしてください。';
    }

    if (isset($_POST['public_status']) === FALSE || isset($_POST['public_status']) === '') {
        $errors[] = '公開/非公開を設定してください。';
    } elseif ($_POST['public_status'] !== '0' && $_POST['public_status'] !== '1') {
        $errors[] = '公開ステータスは、公開/非公開のどちらかを指定してください。';
    }

    if (count($errors) === 0) {
        // 画像はimg/に移し、pathを保存
        //ファイル名は、元のファイル名_created にする
        $image_name_array = explode('.', basename($_FILES['image']['name']));
        $image_file_offset = date('Y-m-d_H-i-s');
        $image_name = $image_name_array[0] . '_' . $image_file_offset . '.' . $image_name_array[1];
        $uploaddir = './img/';
        $uploadfile = $uploaddir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
            $image_path = $uploadfile;
        } else {
            $errors[] = '画像のコピーに失敗しました。';
        }
    }

    // 画像のコピーも含めて、エラーしてないかチェック
    // drink tableと、drink stock tableのinsert
    if (count($errors) === 0) {
        // 各種値の更新
        $name = trim($_POST['name']);
        $price = (int) $_POST['price'];
        $stock = (int) $_POST['stock'];
        $public_status = (int) $_POST["public_status"];
        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');

        // transaction 開始
        mysqli_autocommit($link, FALSE);

        $sql = "INSERT INTO drink_information_table(drink_name, drink_price, date, update_date, status, drink_picture) VALUES ('"
            . $name . "'," . $price . ", '" . $created . "', '" . $updated . "', " . $public_status . ", '" . $image_path . "')";

        if (insert_db($link, $sql)) {
            $id = mysqli_insert_id($link);
            $sql = "INSERT INTO drink_stock_table(drink_id, drink_stock, date, stock_update_date) VALUES ("
                . $id . ", " .  $stock . ", '" . $created . "', '" . $updated . "')";

            if (!insert_db($link, $sql)) {
                $errors[] = 'drink_stock_table : INSERT エラー : ' . $sql;
            }
        } else {
            $errors[] = 'drink_table : INSERT エラー : ' . $sql;
        }

        // transaction 処理に失敗しているかどうか
        if (count($errors) === 0) {
            mysqli_commit($link);
            $result_msg[] = '商品の追加に成功しました。';
        } else {
            mysqli_rollback($link);
        }
    }
}

///////////////////////////////////////
// stock の更新
///////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['change_status'] === 'update' && isset($_POST['update_stock']) === TRUE) {
    // エラー処理
    if ($_POST['update_stock'] === "") {
        $errors[] = '個数を入力してください。';
    } elseif (ctype_digit($_POST['update_stock']) === FALSE) {
        $errors[] = '個数は0以上の整数を入力してください。';
    }

    if (count($errors) === 0) {
        $stock = (int) $_POST['update_stock'];
        $drink_id = (int) $_POST['drink_id'];

        $updated = date('Y-m-d H:i:s');
        $sql = "UPDATE drink_stock_table SET drink_stock = "
            . $stock . ", updated = '" . $updated . "' WHERE drink_id = " . $drink_id;

        if (update_db($link, $sql)) {
            $result_msg[] = '在庫の更新に成功しました。';
        } else {
            $errors[] = 'drink_stock_table : UPDATE エラー : ' . $sql;
        }
    }
}

///////////////////////////////////////
// public_status の更新
///////////////////////////////////////
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['change_status'] === 'update' && isset($_POST['update_public_status']) === TRUE) {
    // エラー処理
    if ($_POST['update_public_status'] === '') {
        $errors[] = '公開/非公開を設定してください。';
    } elseif ($_POST['update_public_status'] !== '0' && $_POST['update_public_status'] !== '1') {
        $errors[] = '公開ステータスは、公開/非公開のどちらかを指定してください。';
    }

    if (count($errors) === 0) {
        $public_status = (int) $_POST["update_public_status"];
        $drink_id = (int) $_POST['drink_id'];
        $updated = date('Y-m-d H:i:s');
        $sql = "UPDATE drink_information_table SET updated = '"
            . $updated . "', public_status = " . $public_status . " WHERE drink_id =" . $drink_id;

        if (update_db($link, $sql)) {
            $result_msg[] = '公開/非公開の更新に成功しました。';
        } else {
            $errors[] = 'drink_table : UPDATE エラー : ' . $sql;
        }
    }
}

///////////////////////////////////////
// 商品一覧取得
//////////////////////////////////////
$sql = 'SELECT drink_information_table.drink_id, drink_information_table.drink_name, drink_information_table.drink_price, drink_information_table.status, drink_information_table.drink_picture
            FROM drink_information_table JOIN drink_stock_table
            ON drink_information_table.drink_id = drink_stock_table.drink_id';
$data = select_db($link, $sql);

close_db_link($link);
include_once('../../include/view/view_tool.php');
