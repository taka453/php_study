<?php

if(isset($_GET['query']) === TRUE) {
    $query = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スーパーグローバル変数使用例</title>
</head>
<body>
    <h1>検索しよう</h1>
    <?php
        if(isset($query) === TRUE) {
    ?>
        <a href="https://www.google.co.jp/search?q=<?php print $query; ?>" target="_blank">「<?php print $query; ?>」をGoogleで検索する</a><br>
        <a href="http://www.bing.com/search?q=<?php print $query; ?>" target="_blank">「<?php print $query; ?>」をbingで検索する</a><br>
        <a href="http://search.yahoo.co.jp/search?p=<?php print $query; ?>" target="_blank">「<?php print $query; ?>」をyahooで検索する</a><br>
    <?php
        }
    ?>

    <form method="get">
        <input type="text" name="query" value="<?php if(isset($query) === TRUE){ print $query; } ?>">
        <input type="submit" value="送信">
    </form>
</body>
</html>