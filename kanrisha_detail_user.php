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
// funcs.phpで作った管理者か確認するコード
kanri();

// sessionに入れたpersonal idを取得
$personalid = $_SESSION["personalid"];

//２．データ登録SQL作成
$stmt   = $pdo->prepare("SELECT * FROM gs_personal WHERE id = :id "); //SQLをセット
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$status = $stmt->execute(); //SQLを実行→エラーの場合falseを$statusに代入

//３．データ表示
$view=""; //HTML文字列作り、入れる変数
if($status==false) {
  //SQLエラーの場合
  sql_error2($stmt);
}else{
  //SQL成功の場合
  $res = $stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>経歴・My Brain登録更新・削除</title>
  <link rel="stylesheet" href="css/style_new.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <div class="space"></div>
    <div class="obi">経歴・My Brain登録更新・削除
    </div>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="POST" action="kanrisha_update_user.php">
  <div>
   <fieldset>
    <legend>ユーザー情報更新</legend>
    <table>
     <tr><td class="blue"><label>氏名：</td><td><input type="text" name="name" value="<?=$res["name"]?>"></label></td></tr>
     <tr><td class="blue"><label>役職：</td><td><input type="text" name="title" value="<?=$res["title"]?>"></label></td></tr>
     <tr><td class="blue"><label>所属：</td><td><input type="text" name="department" value="<?=$res["department"]?>"></label></td></tr>
     <tr><td class="blue"><label>電話番号：</td><td><input type="text" name="tel" value="<?=$res["tel"]?>"></label></td></tr>
     <tr><td class="blue"><label>email：</td><td><input type="text" name="email" value="<?=$res["email"]?>"></label></td></tr>
     <tr><td class="blue"><label>ID：</td><td><input type="text" name="personalid" value="<?=$res["personalid"]?>"></label></td></tr>
     <tr><td class="blue"><label>パスワード：</td><td><input type="text" name="lpw" value="<?=$res["lpw"]?>"></label></td></tr>
     <tr><td class="blue"><label>アイコン</td><td><input type="text" name="icon" value="<?=$res["icon"]?>"></label></td></tr>
     <tr><td class="blue"><label>管理者権限付与(有1/無0)：</td><td><input type="text" id="kanri_flg_input" name="kanri_flg" value="<?=$res["kanri_flg"]?>" readonly>
        <button id="change_kanri_flg">切替</button></label></td></tr>
     <tr><td class="blue"><label>life flug管理(有1/無0)</td><td><input type="text" id="life_flg_input" name="life_flg" value="<?=$res["life_flg"]?>"  readonly>
     <button id="change_life_flg">切替</button></label></td></tr> 
    </table>
     <!-- idをhiddenで隠して送信 -->
     <input type="hidden" name="id" value="<?=$res["id"]?>">
     <!-- idを隠して送信 -->
  <div class="space"></div>

     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<ul>
<li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <li><a href="kanrisha_user_select.php">User一覧に戻る</a></li>
  </ul>
<!-- Main[End] -->  

<script>
  $(document).ready(function() {
        $('#change_kanri_flg').click(function(event) {
          // 普通クリックするとsendされてしまうので、デフォルトのクリック動作を無効にする
          event.preventDefault(); 

            let kanri_flg_input = $('#kanri_flg_input');
            // 現在の値を取得
            let current_value = parseInt(kanri_flg_input.val());
            // 値を切り替える
            let new_value = (current_value === 0) ? 1 : 0;
            // 新しい値を設定
            kanri_flg_input.val(new_value);
        });

        $('#change_life_flg').click(function(event) {
          // 普通クリックするとsendされてしまうので、デフォルトのクリック動作を無効にする
          event.preventDefault(); 

            let life_flg_input = $('#life_flg_input');
            // 現在の値を取得
            let current_value = parseInt(life_flg_input.val());
            // 値を切り替える
            let new_value = (current_value === 0) ? 1 : 0;
            // 新しい値を設定
            life_flg_input.val(new_value);
        });
    });
</script>
</body>
</html>




