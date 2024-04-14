<?php
//0. SESSION開始！！
session_start();

ini_set('display_errors', 'On'); // エラーを表示させるようにしてください
error_reporting(E_ALL); // 全てのレベルのエラーを表示してください

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// user_select.phpで選んだidを取得、そのidのsmb情報を表示したい
$id = $_GET["id"];

//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_smcb_list WHERE id = :id "); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view=""; //HTML文字列作り、入れる変数
if($status==false) {
  //SQLエラーの場合
  sql_error1($stmt);
}else{
  //SQL成功の場合
  $res = $stmt->fetch();
};

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>SMCBを編集</title>
  <style>div{padding: 10px;font-size:16px;}</style>
  <link rel="stylesheet" href="css/style_new.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<!-- Head[Start] -->
<header>
<div class="space"></div>
    <div class="obi">SMCBを編集</div>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<!-- insert.phpにpost方式で飛ばす -->
<!-- 質問：登録後にinsert_book.phpに飛べない、どうしたいいの？ -->
<form method="post" action="kanrisha_update_smcb.php">
  <!-- このjumbotronが複雑、質問 -->
  
  <div>
    <div></div>
  </div>
  <div>
   <fieldset> 
    <legend>Evaluate SMCB</legend>
    <table>
      <tr><td class="blue"><label>依頼者：</td><td><input type="text" name="requestname" value="<?=$res["requestname"]?>" ></td><td><input type="text" name="requestid" value="<?=$res["requestid"]?>" ></td></label></td></tr>
      <tr><td class="blue"><label>回答者：</td><td><input type="text" name="responsername" id="responsername" value="<?=$res["responsername"]?>"></td><td><input type="text" name="responserid" id="responserid" value="<?=$res["responserid"]?>"></td></label></td></tr>
      <tr><td class="blue"><label>大項目：</td><td><input type="text" name="bigitem" value="<?=$res["bigitem"]?>" ></label></td></tr>
      <tr><td class="blue"><label>中項目：</td><td><input type="text" name="miditem" value="<?=$res["miditem"]?>" ></label></td></tr>
      <tr><td class="blue"><label>タイトル：</td><td><input type="text" name="title" value="<?=$res["title"]?>" ></label></td></tr>
      <tr><td class="blue"><label>オフライン/知恵袋：</td><td><select name="onoroff">
      <option value="オフライン" <?= $res["onoroff"] === "オフライン" ? "selected" : "" ?>>オフライン</option>
      <option value="知恵袋" <?= $res["onoroff"] === "知恵袋" ? "selected" : "" ?>>知恵袋</option>
    </select></label></td></tr>
      <tr><td class="blue"><label>依頼日時：</td><td><input type="datetime-local" name="requestdaytime" value="<?=$res["requestdaytime"]?>" ></label></td></tr>
      <tr><td class="blue"><label>SMCB実施日：</td><td> 開始 <input type="datetime-local" name="smbdaytimestart" value="<?=$res["smbdaytimestart"]?>" ></td><td> 終了 <input type="datetime-local" name="smbdaytimeend" value="<?=$res["smbdaytimeend"]?>" ></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item1" value="<?=$res["item1"]?>"></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item2" value="<?=$res["item2"]?>"></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item3" value="<?=$res["item3"]?>"></label></td></tr>
      <tr><td class="blue"><label>評価(1-5(5:大満足,3:普通,1:改善の余地あり))</td><td><select type="text" name="reviewstar" value="<?=$res["reviewstar"]?>">
        <option value="5" <?= $res["reviewstar"] === "5" ? "selected" : "" ?>>5</option>
        <option value="4" <?= $res["reviewstar"] === "4" ? "selected" : "" ?>>4</option>
        <option value="3" <?= $res["reviewstar"] === "3" ? "selected" : "" ?>>3</option>
        <option value="2" <?= $res["reviewstar"] === "2" ? "selected" : "" ?>>2</option>
        <option value="1" <?= $res["reviewstar"] === "1" ? "selected" : "" ?>>1</option>
      </select></label></td></tr>
      <tr><td class="blue"><label>コメント：</td><td><input type="text" name="reviewcomment" value="<?=$res["reviewcomment"]?>"></label></td></tr>
    </table>
    <input type="hidden" name="id" value="<?=$res["id"]?>">
<div class="space"></div>
     <tr><input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<div style='height: 10px'></div>
<ul>
<li><a href="kanrisha_smcb_list.php">SMCB一覧に戻る</a></li>
		<li><a href="kanrisha_mypage.php">マイページに戻る</a></li>
	</ul>
<!-- Main[End] -->

<script>
    $(document).ready(function(){
    // もし$chie_responseridに内容が入っていたら、
    if("<?php echo $chie_responserid; ?>" !== ""){
      // #responseridを$chie_responseridに上書きする
      $('#responserid').val("<?= $chie_responserid ?>");
    }else{
      // $chie_responsernameに内容がが入っていない場合は$originalResponsernameにする
      $('#responserid').val("<?= $originalResponserid ?>");
    };
  });

  $(document).ready(function(){
    // もし$chie_responsernameに内容が入っていたら、
    if("<?php echo $chie_responsername; ?>" !== ""){
      // #responsernameを$chie_responsernameに上書きする
      $('#responsername').val("<?= $chie_responsername ?>");
    }else{
      // $chie_responsernameに内容がが入っていない場合は$originalResponsernameにする
      $('#responsername').val("<?= $originalResponsername ?>");
    };
  });
</script>

</body>
</html>
