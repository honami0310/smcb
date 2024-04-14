<?php
//0. SESSION開始！！
session_start();

//1. POSTデータ取得
$id = $_POST['id'];
$kaishi = $_POST['kaishi'];
$owari = $_POST['owari'];
$belongs = $_POST['belongs'];
$naiyou = $_POST['naiyou'];
$item1 = $_POST['item1'];
$item2 = $_POST['item2'];
$item3 = $_POST['item3'];

//2. DB接続します
include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// 管理者の場合1、でなければ0
$kanrisha = $_SESSION["kanri_flg"];

//３．データ登録SQL作成
// $sqlでUPDATE文を書く
$sql = "UPDATE gs_keireki SET kaishi=:kaishi, owari=:owari, belongs=:belongs, naiyou=:naiyou, item1=:item1, item2=:item2, item3=:item3 WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':kaishi',  $kaishi,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':owari', $owari,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':belongs', $belongs,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':naiyou', $naiyou,    PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item1',$item1, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item2',$item2, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item3',$item3, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':id',$id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
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
