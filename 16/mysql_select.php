<pre>
    <?PHP
        $host = 'localhost';
        $username = 'root';
        $passwd = 'root';
        $dbname = 'codecamp';
        $link = mysqli_connect($host, $username, $passwd, $dbname);

        // 接続成功した場合
        if($link) {
            // 文字化け防止
            mysqli_set_charset($link, 'utf8');
            $query = 'SELECT goods_id, goods_name, price FROM goods_table';
            // クエリを実行します
            $result = mysqli_query($link, $query);
            // 1行ずつ結果を配列で取得します
            while($row = mysqli_fetch_array($result)){
                print $row['goods_id'];
                print $row['goods_name'];
                print $row['price'];
                print "\n";
            }
            // メモリの開放
            mysqli_free_result($result);
            // DB切断
            mysqli_close($link);
        } else {
            print 'DB接続失敗';
        }
    ?>
</pre>