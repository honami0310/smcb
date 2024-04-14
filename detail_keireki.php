<!-- エラーを検知するphp -->
<?php
//0. SESSION開始！！
session_start();

ini_set('display_errors', 'On'); // エラーを表示させるようにしてください
error_reporting(E_ALL); // 全てのレベルのエラーを表示してください
?>

<?php
$id = $_GET["id"];

include("funcs.php");  //funcs.phpを読み込む（関数群）
$pdo = db_conn();      //DB接続関数
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//２．データ登録SQL作成
$stmt_keireki   = $pdo->prepare("SELECT * FROM gs_keireki WHERE id = :id "); //SQLをセット
$stmt_keireki->bindValue(":id", $id, PDO::PARAM_INT);
$status_keireki = $stmt_keireki->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view=""; //HTML文字列作り、入れる変数
if($status_keireki==false) {
  //SQLエラーの場合
  sql_error2($stmt_keireki);
}else{
  //SQL成功の場合
  $reskeireki = $stmt_keireki->fetch();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>経歴・My Brain登録更新・削除</title>
  <style>div{padding: 10px;font-size:16px;}</style>
  <link rel="stylesheet" href="css/style_new.css">
</head>
<body>

<!-- Head[Start] -->
<header>
    <div class="obi">経歴・My Brain登録更新・削除
    </div>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="update_keireki.php">
  <div>
   <fieldset>
    <legend>経歴・My Brain登録更新</legend>
    <table>
     <tr><td class="blue"><label>From：</td><td><input type="text" name="kaishi" value="<?=$reskeireki["kaishi"]?>"></label></td></tr>
     <tr><td class="blue"><label>To：</td><td><input type="text" name="owari" value="<?=$reskeireki["owari"]?>"></label></td></tr>
     <tr><td class="blue"><label>所属：</td><td><input type="text" name="belongs" value="<?=$reskeireki["belongs"]?>"></label></td></tr>
     <tr><td class="blue"><label>経験：</td><td><input type="text" name="naiyou" value="<?=$reskeireki["naiyou"]?>"></label></td></tr>
     <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item1" value="<?=$reskeireki["item1"]?>"></label></td></tr>
     <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item2" value="<?=$reskeireki["item2"]?>"></label></td></tr>
     <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item3" value="<?=$reskeireki["item3"]?>"></label></td></tr>
     </table>
     <!-- idをhiddenで隠して送信 -->
     <input type="hidden" name="id" value="<?=$reskeireki["id"]?>">
     <div class="space"></div>
     <!-- idを隠して送信 -->
     <input type="submit" value="送信">
    </fieldset>
    <div class="space"></div>
  </div>
</form>
<!-- Main[End] -->
<ul>
    <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
  </ul>
</body>
</html>




