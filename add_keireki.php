<?php

//0. SESSION開始！！
session_start();

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();



?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>経歴・My Brainを追加</title>
  <link rel="stylesheet" href="css/style_new.css">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <div class="space"></div>
<div class="obi">経歴新規追加</div>

</header>
<div class="space"></div>
<!-- Head[End] -->

<!-- Main[Start] -->
<!-- insert.phpにpost方式で飛ばす -->
<!-- 質問：登録後にinsert_book.phpに飛べない、どうしたいいの？ -->
<form method="post" action="insert_keireki.php">
  <!-- このjumbotronが複雑、質問 -->
  <div>
   <fieldset> 
    <legend>経歴・My Brainを追加</legend>
    <table> 
    <tr><td class="blue"><label>From：</td><td><input type="text" name="kaishi" ></label></td></tr>
      <tr><td class="blue"><label>To：</td><td><input type="text" name="owari" ></label></td></tr>
      <tr><td class="blue"><label>所属：</td><td><input type="text" name="belongs" ></label></td></tr>
      <tr><td class="blue"><label>経験：</td><td><input type="text" name="naiyou" ></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item1" ></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item2" ></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item3" ></label></td></tr>
    </table>
<div class="space"></div>
     <tr><input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<div style='height: 10px'></div>

<!-- 管理者かどうかで戻り先変える -->
<ul>
    <?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
  </ul>
<!-- Main[End] -->

</body>
</html>
