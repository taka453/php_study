<?php

require_once '../../include/conf/const.php';
require_once '../../include/model/model_bbs.php';
include_once '../../include/view/view_bbs.php';

$errors = array();
$link = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (has_name_error($_POST['name'])) {
    $errors[] = has_name_error($_POST['name']);
  }

  if (has_comment_error($_POST['comment'])) {
    $errors[] = has_comment_error($_POST['comment']);
  }

  if ((count($errors) === 0) && $link) {
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    $sql = "INSERT INTO bbs (name, comment, date) VALUES ('$name', '$comment', NOW())";
    insert_db($link, $sql);
  }
}

if ($link) {
  $sql = 'SELECT name, comment, date FROM bbs';
  $data = select_db($link, $sql);
}

close_db($link);