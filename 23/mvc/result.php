<?php
require_once('../../include/conf/const.php');
require_once('../../include/model/model_drink.php');
$err_msg = [];
$correct_commit_message = [];

$link = get_db_link();

if (get_request_method() === 'POST') {
    if (!is_post_data_exist('money')) {
        $err_msg[] = '投入金額を入力してください。';
    } elseif (ctype_digit($_POST['money']) === FALSE) {
        $err_msg[] = '投入金額には、0以上の整数を入力してください。';
    }
    if (!is_post_data_exist('drink_id')) {
        $err_msg[] = '購入するドリンクを指定してください。';
    }

    if (count($err_msg) === 0) {
        $money = $_POST['money'];
        $drink_id = $_POST['drink_id'];

        // transaction開始
        mysqli_autocommit($link, FALSE);

        // price, publis state, stock, 画像、商品名、を取得
        $sql = "SELECT drink_price, drink_name, drink_stock, image_path, public_status
                FROM drink_table JOIN drink_stock_table ON drink_table.drink_id = drink_stock_table.drink_id
                WHERE drink_table.drink_id =" .  $drink_id;
        $drink_array = select_db($link, $sql);
        $drink_array = $drink_array[0]; // 要素が一つしかないので、二重配列を単なる連想配列にする

        // 各値でエラー処理
        if ($money < $drink_array['drink_price']) {
            $err_msg[] = 'ドリンクの値段より大きい金額を投入してください。';
        }
        if ($drink_array['drink_stock'] === '0') {
            $err_msg[] = 'ドリンクの在庫がないため、購入できません。';
        }
        if ($drink_array['public_status'] === '0') {
            $err_msg[] = 'ドリンクのステータスが非公開のため、購入できません。';
        }

        // 全部通れば、購入処理
        // つまり、在庫数を減らして、購入履歴を挿入
        if (count($err_msg) === 0) {
            $updated = date('Y-m-d H:i:s');
            $sql = "UPDATE  drink_stock_table SET drink_stock = " . ($drink_array['drink_stock'] - 1) . ", updated = '" .  $updated . "' WHERE drink_id = " . $drink_id;

            if (update_db($link, $sql)) {
                $sql = "INSERT  drink_order_table (drink_id, order_datetime) VALUES ( " . $drink_id . ", '" . $updated . "')";

                if (!insert_db($link, $sql)) {
                    $err_msg[] = 'drink_order_table: insertエラー: ' . $sql;
                }
            } else {
                $err_msg[] = 'drink_stock_table: updateエラー: ' . $sql;
            }

            // どちらもとおれば、画像、商品名、おつりの情報を表示する
            // transaction の終了処理
            if (count($err_msg) === 0) {
                mysqli_commit($link);
                $left_money = $money - $drink_array['drink_price'];
                $correct_commit_message[] = $drink_array['drink_name'] . 'を購入しました!';
                $correct_commit_message[] = 'おつりは、' . $left_money . '円です。';
            } else {
                mysqli_rollback($link);
            }
        }
    }
}

close_db_link($link);

include_once('../../../../include/view/view_result.php');
