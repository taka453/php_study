<?php
    //DB接続定義は冒頭
    $host = 'localhost';
    $username = 'root';
    $passwd = 'root';
    $dbname = 'codecamp';

    $emp_data = array();

    $job = '';
    $job = $_GET['job'];

    $link = mysqli_connect($host, $username, $passwd, $dbname);
    if($link) {
        mysqli_set_charset($link, 'utf8');
        $query = 'SELECT * FROM emp_table';
        // $job の中身がからじゃないということはなんらかの職種が選択されている
        if (!empty($job)) {
            //　結合
            // $query .= と同じ意味になる
            // スペース
            // 変数名注意（予約語だと混乱）
            $query = $query . ' WHERE job = \''. $job .'\' ORDER BY emp_id ASC';
        }
        // $query = 'SELECT emp_id, emp_name, job, age FROM emp_table WHERE job = \''. $job .'\' job BY emp_id ASC';
        $result = mysqli_query($link, $query);

        while($row = mysqli_fetch_array($result)) {
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
    <form>
        <select name="job">
            // 初期値は全員表示するようにする '*' にて全員表示される。また、セレクトを選択しても全員表示される。
            <option value="">全員</option>
            <option value="manager" <?php if($job === 'manager') { print 'selected'; }?>>マネージャー</option>
            <option value="analyst" <?php if($job === 'analyst') { print 'selected'; }?>>アナリスト</option>
            <option value="clerk" <?php if($job === 'clerk') { print 'selected'; }?>>一般職</option>
            <option value="parttime" <?php if($job === 'parttime') { print 'selected'; }?>>アルバイト</option>
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
            <?php if(empty($emp_data) === FALSE): ?>
                <?php foreach($emp_data as $value): ?>
                    <tr>
                        <td><?php print htmlspecialchars($value['emp_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['emp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['job'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php print htmlspecialchars($value['age'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">何も見つかりませんでした。</td>
                </tr>
            <?php endif; ?>
    </table>
</body>
</html>