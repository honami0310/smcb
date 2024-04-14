<?php
//0. SESSION開始！！
session_start();

//1. POSTデータ取得
$id = $_POST['id'];
$responserid = $_POST['responserid'];
$responsername = $_POST['responsername'];
$onoroff = $_POST['onoroff'];
$smbdaytimestart = $_POST['smbdaytimestart'];
$smbdaytimeend = $_POST['smbdaytimeend'];
$reviewstar = $_POST['reviewstar'];
$spstar = $_POST['spstar'];
$reviewcomment = $_POST['reviewcomment'];
$item1 = $_POST['item1'];
$item2 = $_POST['item2'];
$item3 = $_POST['item3'];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];
// 管理者の場合1、でなければ0
$kanrisha = $_SESSION["kanri_flg"];

//３．データ登録SQL作成
// $sqlでUPDATE文を書く
$sql = "UPDATE gs_smcb_list SET responserid=:responserid, responsername=:responsername, onoroff=:onoroff, smbdaytimestart=:smbdaytimestart, smbdaytimeend=:smbdaytimeend, reviewstar=:reviewstar, spstar=:spstar, reviewcomment=:reviewcomment, item1=:item1, item2=:item2, item3=:item3 WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',  $id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':responserid',  $responserid,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':responsername',  $responsername,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':onoroff',  $onoroff,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':smbdaytimestart', $smbdaytimestart,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':smbdaytimeend', $smbdaytimeend,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':reviewstar', $reviewstar,    PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':spstar', $spstar,    PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':reviewcomment',  $reviewcomment,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item1',$item1, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item2',$item2, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item3',$item3, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
    sql_error2($stmt_keireki);
  }else{
    if($kanrisha==1){
    redirect("kanrisha_mypage.php");
  }else{
    redirect("mypage.php");
  }
  }
?>  
