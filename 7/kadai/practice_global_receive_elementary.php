<?php
    $my_name = '';
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['my_name'])) {
            $my_name = htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
</head>
<body>
    <?php
        if($my_name !== '') {
            print 'ようこそ' . $my_name . 'さん';
        } else {
            print '名前を入力してくだい';
        }
    ?>
</body>
</html>