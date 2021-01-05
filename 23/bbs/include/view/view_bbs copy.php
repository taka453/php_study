<?php
    require_once '../../htdocs/bbs_mvc.php';
?>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
</head>

<body>
    <h1>ひとこと掲示板</h1>
    <form method="post">
        名前 : <input type="text" name="name">
        ひとこと: <input type="text" name="comment">
        <input type="submit">
    </form>

    <?php if (count($errors) !== 0) : ?>
        <ul>
            <?php foreach ($errors as $value) : ?>
                <li>
                    <?php print $value; ?>
                </li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>

    <hr>

    <?php if ($data) : ?>
        <?php foreach ($data as $column) : ?>
            <?php $value = $column['name'] . ' : ' . $column['comment'] . ' - ' . $column['date']; ?>
            <?php print h($value); ?>
            <br>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>