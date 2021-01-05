<?php
    $host = 'localhost';
    $username = 'root';
    $passwd = 'root';
    $dbname = 'DB_study';

    $emp_data = array();

    $job = '';
    $job = $_GET['job'];

    $link = mysqli_connect($host, $username, $passwd, $dbname);

    if ($link) {
        mysqli_set_charset($link, 'utf8');
        $query = 'SELECT * FROM emp_table';
        if (!empty($job)) {
            $query .= ' WHERE job = \'' . $job . '\' ORDER BY emp_id ASC';
        }
        $result = mysqli_query($link, $query);

        while ($row = mysqli_fetch_array($result)) {
            $emp_data[] = $row;
        }

        mysqli_free_result($result);
        mysqli_close($result);
    } else {
        print 'DB接続失敗';
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
    <style type="text/css">
        table, td, th {
            border: solid 1px;
        }
    </style>
</head>
<body>
    <p>表示する職種を選択してください</p>
    <form action="">
        <select name="job">
            <option value="">全員</option>
            <option value="manager"><?php if ($job === 'manager') { print 'selected'; }?>マネージャー</option>
            <option value="analyst"><?php if ($job === 'analyst') { print 'selected'; }?>アナリスト</option>
            <option value="clerk"><?php if ($job === 'clerk') { print 'selected'; }?>一般職</option>
            <option value="partime"><?php if ($job === 'partime') { print 'selected'; }?>アルバイト</option>
        </select>
        <input type="submit" value="表示">
    </form>
    <table>
        <caption>社員一覧</caption>
            <tr>
                <th>社員番号</th>
                <th>名前</th>
                <th>職種</th>
                <th>年齢</th>
            </tr>
            <?php if(empty($emp_data) === FALSE) { ?>
                <?php foreach($emp_data as $value) { ?>
                    <tr>
                        <td><?php print htmlspecialchars($value['emp_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['emp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['job'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['age'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">何も見つかりませんでした。</td>
                </tr>
            <?php } ?>
    </table>
</body>
</html>