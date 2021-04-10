<?php

require_once '../../htdocs/bbs_mvc.php';

function connect_db()
{
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
  return $link;
}

function insert_db ($link, $ary, $table_name, $flg = false)
{
  foreach ($ary as $key => $value) {
    $cols[] = '`' . $key . '`';
    $values[] = '\'' . $value . '\'';
  }
  $sql = 'INSERT INTO ' . $table_name . '(' . implode(', ' , $cols) . ') VALUES (' . implode(', ', $values) . ')';
  if ($flg === true) {
    var_dump($ary);
    var_dump($sql);
    die('code end');
  }
  return mysqli_query($link, $sql);
}

function select_db($link, $sql)
{
  $data = array();
  $result = mysqli_query($link, $sql);
  while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
  }
  mysqli_free_result($result);
  return $data;
}

function close_db($link)
{
  mysqli_close($link);
}