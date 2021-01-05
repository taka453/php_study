<?php
    // csvファイルを代入
    $filename='./zip_data_split_1.csv';
    // 配列初期化
    $data=[];
    // notice対策
    $value='';
    // 指定したファイルが存在し、読み込み可能かどうかを確認
    if(is_readable($filename)===TRUE){
        // fopenでファイルを開く
        if(($fp=fopen($filename,'r')) !== FALSE){
            while(($tmp=fgetcsv($fp,1000,",")) !== FALSE){
                $data[]=$tmp;
                }
            }
        fclose($fp);
    }else{
        $data[]='ファイルが読み込めません';
    }
?>
<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>課題2</title>
	</head>
	<body>
		<p>以下にファイルから読み込んだ住所データを表示</p>
		<table border="1">
			<caption>住所データ</caption>
				<tr>
					<th>郵便番号</th>
					<th>都道府県</th>
					<th>市町村</th>
					<th>町域</th>
				</tr>
                <tr>
                <?php foreach($data as $value){ ?>
                    <td><?php print htmlspecialchars($value[0],ENT_QUOTES,'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($value[4],ENT_QUOTES,'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($value[5],ENT_QUOTES,'UTF-8'); ?></td>
                    <td><?php print htmlspecialchars($value[6],ENT_QUOTES,'UTF-8'); ?></td>
                </tr>
                <?php } ?>
		</table>
	</body>
</html>