<?php
require_once('../../../../include/conf/const.php');
require_once('../../../../include/model/model_drink.php');

// get_db_linkで、!$linkなら、dieするので、以降は必ず$linkがある
// 致命的な処理については、dieするようにして、関数にすれば、ネストが減らせる
$link = get_db_link();

$sql = 'SELECT drink_table.drink_id, drink_name, drink_price, image_path, drink_stock
            FROM drink_table JOIN drink_stock_table ON drink_table.drink_id = drink_stock_table.drink_id
            WHERE public_status = 1';
$data = select_db($link, $sql);

close_db_link($link);

include_once('../../../../include/view/view_index.php');