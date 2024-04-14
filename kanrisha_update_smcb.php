<?php
//0. SESSION開始！！
session_start();

//1. POSTデータ取得
$id = $_POST['id'];
$requestname = $_POST['requestname'];
$requestid = $_POST['requestid'];
$responsername = $_POST['responsername'];
$responserid = $_POST['responserid'];
$bigitem = $_POST['bigitem'];
$miditem = $_POST['miditem'];
$title = $_POST['title'];
$onoroff = $_POST['onoroff'];
$requestdaytime = $_POST['requestdaytime'];
$smbdaytimestart = $_POST['smbdaytimestart'];
$smbdaytimeend = $_POST['smbdaytimeend'];
$item1 = $_POST['item1'];
$item2 = $_POST['item2'];
$item3 = $_POST['item3'];
$reviewstar = $_POST['reviewstar'];
$reviewcomment = $_POST['reviewcomment'];


//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//３．データ登録SQL作成
// $sqlでUPDATE文を書く
$sql = "UPDATE gs_smcb_list SET requestname=:requestname, requestid=:requestid, responsername=:responsername, responserid=:responserid, bigitem=:bigitem, miditem=:miditem, title=:title, onoroff=:onoroff, requestdaytime=:requestdaytime, smbdaytimestart=:smbdaytimestart, smbdaytimeend=:smbdaytimeend, item1=:item1, item2=:item2, item3=:item3, reviewstar=:reviewstar, reviewcomment=:reviewcomment WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',  $id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':requestname',  $requestname,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':requestid', $requestid,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':responsername', $responsername,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':responserid',  $responserid,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':bigitem',  $bigitem,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':miditem',$miditem, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':title',$title, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':onoroff',$onoroff, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':requestdaytime',$requestdaytime, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':smbdaytimestart',$smbdaytimestart,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':smbdaytimeend',$smbdaytimeend,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item1',$item1,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item2',$item2,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item3',$item3,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':reviewstar',$reviewstar,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':reviewcomment',$reviewcomment,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
    sql_error2($stmt);
  }else{
    redirect("kanrisha_smcb_list.php");
  }
?>  
