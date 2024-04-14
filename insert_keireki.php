<?php
//0. SESSION開始！！
session_start();
// 管理者かどうかでredirect先を変える
$kanrisha = $_SESSION["kanri_flg"];


//1. POSTデータ取得
$kaishi = $_POST['kaishi'];
$owari = $_POST['owari'];
$belongs = $_POST['belongs'];
$naiyou = $_POST['naiyou'];
$item1 = $_POST['item1'];
$item2 = $_POST['item2'];
$item3 = $_POST['item3'];

//funcs.phpを読み込む（関数群）
include("funcs.php");
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idと名前を取得
$personalid = $_SESSION["personalid"];
$name = $_SESSION["name"];


//３．データ登録SQL作成
// databaseのSQLの時に学んだ書き方。バッククオート省略版、idは書かない
// VALUESはそれぞれの項目に:(コロン)をつける。
// まず型を作り、そこに後から内容を入れていくイメージ
$stmt = $pdo->prepare("INSERT INTO gs_keireki ( personalid, name, kaishi, owari, belongs, naiyou, item1, item2, item3)VALUES( :personalid, :name, :kaishi, :owari, :naiyou, :belongs, :item1, :item2, :item3)");
// :nameで型作ったところに後からbindValueで内容入れる。$nameはpostで飛ばしたname
// .$nameとかしないでbindValueとすることで不正なIDを入れてすべての情報を抜き出すようなことを防ぐ
$stmt->bindValue(':personalid', $personalid, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':name', $name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':kaishi', $kaishi, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)parameter streemの略。strは文字
$stmt->bindValue(':owari', $owari, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':belongs', $belongs, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':naiyou', $naiyou, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item1', $item1, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item2', $item2, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':item3', $item3, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)

// stmt=statement
// statementを実行する指示
$status = $stmt->execute();
// 

//４．データ登録処理後
// うまくデータ取れなかったとき(statusがfalseの時)
if($status==false){
  sql_error1($stmt);
}else{
  if($kanrisha==1){
    redirect("kanrisha_mypage.php");
  }else{
    redirect("mypage.php");
  };
}
?>
