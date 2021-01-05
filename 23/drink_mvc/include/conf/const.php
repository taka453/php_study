<?php

define('TAX', 1.05);  // 消費税
define('MAX_FILE_SIZE', 30000); // アップロードするファイルサイズ

define('DB_HOST',   'localhost'); // データベースのホスト名又はIPアドレス
define('DB_USER',   'root');  // MySQLのユーザ名
define('DB_PASSWD', 'root');    // MySQLのパスワード
define('DB_NAME',   'codecamp');    // データベース名

define('HTML_CHARACTER_SET', 'UTF-8');  // HTML文字エンコーディング
define('DB_CHARACTER_SET',   'UTF8');   // DB文字エンコーディング

define(
    'ERR_MSGS',
    [   // 0 = 成功
        'エラーはありません',

        // 1 : name
        '名前を入力してください。',

        // 2-3 : price
        '値段を入力してください。',
        '値段は0以上の整数を入力してください。',

        // 4-5 : stock
        '個数を入力してください。',
        '個数は0以上の整数を入力してください。',

        // 6- 7 image
        '商品画像を選択してください。',
        '商品画像のファイル形式は、JPEGかPNG にしてください。',

        // 8-9 : status
        '公開/非公開を設定してください。',
        '公開ステータスは、公開/非公開のどちらかを指定してください。',

        // 10 image copy error
        '画像のコピーに失敗しました。',

        // 11 insert error
        'INSERT エラー',

        // 12 update error
        'UPDATE エラー',

        // 13 delete error
        'DELETE エラー',

        // 14 password
        'パスワードを入力してください。',

        //15 ログインエラー
        'メールアドレスかパスワードが間違っているため、ログインできません。',

        //16-17 user name
        'ユーザ名は、半角英数字で6文字以上入力してください。',
        'ユーザ名がすでに登録されているため、このユーザ名は使用できません。',

        //18 password
        'パスワードは、半角英数字で6文字以上入力してください。',

        //19-20 amount
        '数量を入力してください。',
        '数量は、1以上の整数を入力してください。',

    ]
);
