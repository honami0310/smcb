<?php
//最初にSESSIONを開始！！ココ大事！！
session_start();

//POST値を取得する
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];


//1.  DB接続します
include("funcs.php");
$pdo = db_conn();

//2. データ登録SQL作成
//* PasswordがHash化→条件はlidのみ！！ハッシュ化する前はAND lpw = :lpwとしてた
$stmt = $pdo->prepare("SELECT * FROM gs_personal WHERE `personalid` = :lid"); 
// 後付けで内容を紐づける
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
// $stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

//3. SQL実行時にエラーがある場合STOP
if($status==false){
    sql_error1($stmt);
}

//4. 抽出データ数を取得
$val = $stmt->fetch();         //1レコードだけ取得する方法
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()



//5.該当１レコードがあればSESSIONに値を代入
//入力したPasswordと暗号化されたPasswordを比較！[戻り値：true,false]
// ハッシュ化したら以下を有効にして、if($pw)とする
$pw = password_verify($lpw, $val["hashedlpw"]);

// fetchで取得した1つのidが空じゃなかったら～のif
// PWハッシュ化の前はif($val["id"] != "" ){となっていた
// ハッシュ化したので$pwがtrueなら～になってる
if($pw){ 
  //Login成功時
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["name"]      = $val['name'];
  $_SESSION["personalid"] = $val['personalid'];
  $_SESSION["kanri_flg"] = $val['kanri_flg'];
  $_SESSION["life_flg"] = $val['life_flg'];

  //Login成功時（リダイレクト）
  if($_SESSION["kanri_flg"] != 1){
    // 管理者以外の場合
    redirect("mypage.php");
}
// 管理者の場合
  redirect("kanrisha_mypage.php");

}
else{
//Login失敗時(Logoutを経由：リダイレクト)
redirect("login.php");  
};

