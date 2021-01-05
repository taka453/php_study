<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
            border: 1px dotted coral;
        }
        th, td {
            width: 50px;
            border: 1px dotted coral;
        }
        th {
            background: lightblue;
        }
        td {
            text-align: center;
        }
    </style>
</head>
<body>
<h1>九九表</h1>
<table>
    <tr>
        <th>&nbsp;</th>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
    </tr>
    <?php

    for($i = 1; $i <= 9; $i++) {
        print '<tr>';
        print '<th>' . $i . '</th>';
        for($j = 1; $j <= 9; $j++) {
            print '<td>' . $j . '*' . $i . '=' . $j * $i . '</td>';
        }
        print '</tr>' . "\n";
    }

    ?>
</table>
</body>
</html>

