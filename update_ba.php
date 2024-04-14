<?php
//0. SESSION開始！！
session_start();

//1. POSTデータ取得
$newbaid = $_GET['chooseBa'];
$resetid = $_GET['resetId'];
$threadid = $_GET['threadid'];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//３．データ登録SQL作成
// 3-1 新しいbaを登録
$sql = "UPDATE chiebukuro SET best=1 WHERE id=:newbaid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':newbaid',$newbaid,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

// 3-1 古いbaを削除
$sql = "UPDATE chiebukuro SET best=NULL WHERE id=:resetid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':resetid',$resetid,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
    sql_error2($stmt_keireki);
  }else{
    header("Location: chie_chat.php?threadid=$threadid");
    exit(); // リダイレクト後にスクリプトの実行を終了するために必要
  }
?>  
