<?php
//0. SESSION開始！！
session_start();


//1. POSTデータ取得

$threadid = $_POST['threadid'];
$threadname = $_POST['threadname'];
$qa = $_POST['qa'];
$comment = $_POST['comment'];
$responsername = $_POST['responsername'];
$responserid = $_POST['responserid'];
$personalid = $_POST['personalid'];
$name = $_POST['name'];


//funcs.phpを読み込む（関数群）
include("funcs.php");
$pdo = db_conn();
$pdochie = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
// $personalid = $_SESSION["personalid"];

//３．データ登録SQL作成
// databaseのSQLの時に学んだ書き方。バッククオート省略版、idは書かない
// VALUESはそれぞれの項目に:(コロン)をつける。
// まず型を作り、そこに後から内容を入れていくイメージ
$stmt = $pdo->prepare("INSERT INTO chiebukuro ( threadid, threadname, comment, personalid, name, responsername, responserid, qa, daytime)VALUES( :threadid, :threadname, :comment, :personalid, :name, :responsername, :responserid, :qa, sysdate())");
// :nameで型作ったところに後からbindValueで内容入れる。$nameはpostで飛ばしたname  
// .$nameとかしないでbindValueとすることで不正なIDを入れてすべての情報を抜き出すようなことを防ぐ
$stmt->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$stmt->bindValue(':threadname', $threadname, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
$stmt->bindValue(':personalid', $personalid, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':responsername', $responsername, PDO::PARAM_STR);
$stmt->bindValue(':responserid', $responserid, PDO::PARAM_STR);
$stmt->bindValue(':qa', $qa, PDO::PARAM_STR);


// stmt=statement
// statementを実行する指示
$status = $stmt->execute();
// 

//４．データ登録処理後
// うまくデータ取れなかったとき(statusがfalseの時)
if ($status == false) {
    sql_error1($stmt);
  } else {
    header("Location: chie_chat.php?threadid=$threadid");
    exit(); // リダイレクト後にスクリプトの実行を終了するために必要
  };