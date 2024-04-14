<?php
//0. SESSION開始！！
session_start();

//1. GETデータ取得
$id   = $_GET["id"];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();
// funcs.phpで作った管理者か確認するコード
kanri();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//３．データ登録SQL作成
$sql = "DELETE FROM gs_smcb_list WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',$id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT STRは文章)
$status = $stmt->execute(); //実行


//４．データ登録処理後
if($status==false){
    sql_error1($stmt);
  }else{
    redirect("kanrisha_smcb_list.php");
  }
?>
