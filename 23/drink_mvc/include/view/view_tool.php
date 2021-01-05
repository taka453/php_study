<?php
  require_once '../../mvc/tool.php';
?>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>自動販売機管理ツール</title>
</head>

<body>
    <h1>自動販売機管理ツール</h1>

    <?php if (count($errors) >= 0) : ?>
        <ul>
            <?php foreach ($errors as  $value) : ?>
                <li><?php print $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (count($result_msg) >= 0) : ?>
        <ul>
            <?php foreach ($result_msg as  $value) : ?>
                <li><?php print $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>

    <h3>新規商品追加</h3>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="change_status" value="create">
        名前: <input type="text" name="name"><br>
        値段 : <input type="number" min="0" name="price"><br>
        個数 : <input type="number" min="0" name="stock"><br>
        <input type="hidden" name="MAX_FILE_SIZE" value=<?php print MAX_FILE_SIZE; ?>>
        <input type="file" name="image"><br>
        <select name="public_status">
            <option value="0">非公開</option>
            <option value="1"> 公開</option>
        </select><br>
        <input type="submit" value="商品追加">
    </form>

    <hr>

    <h3>商品情報変更</h3>

    商品一覧
    <table border="1">
        <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫数</th>
            <th>公開ステータス</th>
        </tr>

        <?php foreach ($data as $value) : ?>
            <tr>
                <td><img src="<?php print $value['drink_picture'] ?>"></td>
                <td><?php print $value['drink_name']; ?></td>
                <td><?php print $value['drink_price']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="change_status" value="update">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                        <input type="number" min="0" name="update_stock" value="<?php print $value['drink_stock']; ?>">
                        <input type="submit" value="更新">
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="change_status" value="update">
                        <input type="hidden" name="drink_id" value="<?php print $value['drink_id']; ?>">
                        <select name="update_public_status">
                            <option <?php if ($value['status'] === '0') {
                                        print 'selected';
                                    } ?> value="0">非公開</option>
                            <option <?php if ($value['status'] === '1') {
                                        print 'selected';
                                    } ?> value="1"> 公開</option>
                        </select>
                        <input type="submit" value="更新">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>