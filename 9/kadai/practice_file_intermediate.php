<?php
    $filename = './zip_data_split_2.csv';
    $data = array();
    $value = '';

    if (is_readable($filename) === TRUE) {
        if(($fp = fopen($filename, 'r')) !== FALSE) {
            while (($tmp = fgetcsv($fp, 1000, ",")) !== FALSE) {
                $data[] = $tmp;
            }
        }
        fclose($fp);
    } else {
        $data[] = 'ファイル読み込めません';
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
</head>
<body>
    <p>以下にファイルから読み込んだ住所データを表示</p>
    <table border="1">
        <caption>住所データ</caption>
            <tr>
                <th>郵便番号</th>
                <th>都道府県</th>
                <th>市町村</th>
                <th>町域</th>
            </tr>
            <?php foreach($data as $value) { ?>
            <tr>
                <td><?php print $value[0]; ?></td>
                <td><?php print $value[4]; ?></td>
                <td><?php print $value[5]; ?></td>
                <td><?php print $value[6]; ?></td>
            </tr>
            <?php } ?>
    </table>
</body>
</html>