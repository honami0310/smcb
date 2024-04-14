<?php
//0. SESSION開始！！
session_start();

//1-1. SMBC用POSTデータ取得
$requestname = $_POST['requestname'];
$requestid = $_POST['requestid'];
$bigitem = $_POST['bigitem'];
$miditem = $_POST['miditem'];
$title = $_POST['title'];
$onoroff = $_POST['onoroff'];
$item1 = $_POST['item1'];
$item2 = $_POST['item2'];
$item3 = $_POST['item3'];

//1-2. SMBC用POSTデータ取得
$threadname = $_POST['title'];
$comment = $_POST['comment'];
$personalid = $_POST['requestid'];
$name = $_POST['requestname'];
$qa = $_POST['qa'];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

//３．SMCBデータ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_smcb_list ( requestname, requestid, bigitem, miditem, title, onoroff, item1, item2, item3, requestdaytime)VALUES( :requestname, :requestid, :bigitem, :miditem, :title, :onoroff, :item1, :item2, :item3, sysdate())");
// :nameで型作ったところに後からbindValueで内容入れる。$nameはpostで飛ばしたname
// .$nameとかしないでbindValueとすることで不正なIDを入れてすべての情報を抜き出すようなことを防ぐ
$stmt->bindValue(':requestname', $requestname, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)parameter streemの略。strは文字
$stmt->bindValue(':requestid', $requestid, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':bigitem', $bigitem, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':miditem', $miditem, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':title', $title, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':onoroff', $onoroff, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item1', $item1, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item2', $item2, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item3', $item3, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

// 4. 知恵袋に使うthreadidをsmcbリストのidから取得する
$stmtthreadid = $pdo->prepare("SELECT * FROM gs_smcb_list ORDER BY id DESC LIMIT 1");

// 4-2 実行
$statusthreadid = $stmtthreadid->execute();
// 4-3 threadidを取得
$res = $stmtthreadid->fetch();
$threadid = $res["id"];

//5．知恵袋データ登録SQL作成
$stmtchie = $pdo->prepare("INSERT INTO chiebukuro (threadid, threadname, comment, personalid, name, qa, daytime)VALUES(:threadid, :threadname, :comment, :personalid, :name, :qa, sysdate())");
// :nameで型作ったところに後からbindValueで内容入れる。$nameはpostで飛ばしたname  
// .$nameとかしないでbindValueとすることで不正なIDを入れてすべての情報を抜き出すようなことを防ぐ
// threadidは4で取得したもの、それ以外はpostされて$をつけたもの(dattimeはinsert intoの段階で取得)
$stmtchie->bindValue(':threadid', $threadid, PDO::PARAM_STR);
$stmtchie->bindValue(':threadname', $threadname, PDO::PARAM_STR);  
$stmtchie->bindValue(':comment', $comment, PDO::PARAM_STR);  
$stmtchie->bindValue(':personalid', $personalid, PDO::PARAM_STR); 
$stmtchie->bindValue(':name', $name, PDO::PARAM_STR);  
$stmtchie->bindValue(':qa', $qa, PDO::PARAM_STR);  

// 5-2実行
$statuschie = $stmtchie->execute();

//6．データ登録処理後
if($status==false or $statuschie==false){
    sql_error1($stmt);
  }else{
    header("Location: chie_chat.php?threadid=$threadid");
    exit(); // リダイレクト後にスクリプトの実行を終了するために必要
  };
?>  