<?php

require_once '../../htdocs/bbs_mvc.php';

function connect_db()
{
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
    mysqli_set_charset($link, 'utf8');
    return $link;
}

function insert_db($link, $sql)
{
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

function close_db($link) {
    mysqli_close($link);
}

function has_name_error($name)
{
    if (ctype_space($name)) {
        return '名前が空白文字のみで構成されているため、コメントできません。';
    } elseif ($name === '') {
        return '名前を入力してください。';
    } elseif (mb_strlen($name) > 20) {
        return '名前は20文字以内で入力してくだい。';
    }
    return FALSE;
}

function has_comment_error($comment)
{
    if (ctype_space($comment)) {
        return 'コメントが空白文字のみで構成されているため、コメントできません。';
    } elseif ($comment === '') {
        return 'コメントを入力してください。';
    } elseif (mb_strlen($comment) > 100) {
        return 'コメントは100文字以内で入力してくだい。';
    }
    return FALSE;
}

function entity($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}