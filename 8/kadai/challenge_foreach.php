<?php

$class = ['ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', '女神' => '杉内'];

foreach($class as $key => $value) {
    print $value . 'さんのアダ名は' . $key . 'です。';
    print '<br>';
}

?>