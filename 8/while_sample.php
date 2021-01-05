<?php

$i = 1900;
$date = date('Y');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ループの使用例</title>
</head>
<body>
    <form action="#">
        生まれた西暦を選択してください
        <select name="born_year">
            <?php
                while($i <= $date) {
            ?>
            <option value="<?php print $i; ?>"><?php print $i; ?></option>
            <?php
                $i++;
                }
            ?>
        </select>
    </form>
</body>
</html>