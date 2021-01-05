<pre>
    <?php
        $host = 'localhost';
        $username = 'root';
        $passwd = 'root';
        $dbname = 'codecamp';
        $link = mysqli_connect($host, $username, $passwd, $dbname);

        if($link) {
            mysqli_set_charset($link, 'utf8');
            $query = 'INSERT INTO goods_table (goods_name, price) VALUES (\'ボールペン\', 80)';
            if(mysqli_query($link, $query) === TRUE) {
                print '成功';
            } else {
                print '失敗';
            }
            mysqli_close($link);
        } else {
            print 'DB接続失敗';
        }
    ?>
</pre>