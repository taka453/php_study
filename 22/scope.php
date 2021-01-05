<?php
  //グローバル変数
  $str = 'スコープテスト';

  function test_scope() {
    global $str;
    print $str;
  }

  test_scope();
?>