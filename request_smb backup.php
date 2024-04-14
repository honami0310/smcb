<?php
//0. SESSION開始！！
session_start();

require_once('funcs.php');
//1. DB接続関数
$pdo = db_conn();
// funcs.phpで作ったログインしてないとselect.phpが開けないコード
sschk();

// user_select.phpで選んだidを取得、そのidの人の情報を表示したい
$colleguename = $_GET["name"];
$colleagueid = $_GET["personalid"];
$personalname = $_SESSION["name"];
$personalid = $_SESSION["personalid"];
$title= "";
$kanri_flg = $_SESSION["kanri_flg"];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Request SMCB</title>
  <style>div{padding: 10px;font-size:16px;}</style>
  <link rel="stylesheet" href="css/style_new.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

<!-- Head[Start] -->
<header>
    <div class="obi">Request SMCB</div>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<!-- insert.phpにpost方式で飛ばす -->
<!-- 質問：登録後にinsert_book.phpに飛べない、どうしたいいの？ -->
<form method="post" action="insert_smcb.php">
  <!-- このjumbotronが複雑、質問 -->
  <div>
    <!-- オフラインでも知恵袋でも必要な入力項目 -->
   <fieldset> 
    <legend>Request SMCB</legend>
    <table>
      <tr><td class="blue"><label>依頼者：</td><td><input type="text" name="requestname" value="<?php echo $personalname ?>" readonly></td><td><input type="text" name="requestid" value="<?php echo $personalid ?>" readonly></td></label></td></tr>
      <tr><td class="blue"><label>回答者：</td><td><input type="text" name="responsername" value="<?php echo $colleguename ?>" readonly></td><td><input type="text" name="responserid" value="<?php echo $colleagueid ?>" readonly></td></label></td></tr>
      <tr><td class="blue"><label>大項目：</td><td><select name="bigitem" >
      <option value="選択してください">選択してください</option>
            <option value="新規事業">新規事業</option>
            <option value="事業投資">事業投資</option>
<option value="貿易">貿易</option>
<option value="社内業務">社内業務</option>
<option value="その他">その他</option>
</select>
</label></td></tr>
      <tr><td class="blue"><label>中項目：</td><td><select name="miditem" >
      <option value="選択してください">選択してください</option>
            <option value="会計・税務">会計・税務</option>
            <option value="営業業務">営業業務</option>
<option value="社内庶務">社内庶務</option>
<option value="法務">法務</option>
<option value="内部統制・コンプラ・サステナ">内部統制・コンプラ・サステナ</option>
<option value="人事">人事</option>
<option value="その他">その他</option>
</select>
      </label></td></tr>
      <tr><td class="blue"><label>タイトル：</td><td><input type="text" name="title" id="title"></label></td></tr>
      <!-- 選択肢で知恵袋を選んだ場合は知恵袋のオプション入力が出る＆別のinsertにpostされるようidを設定、script参照 -->
      <tr><td class="blue"><label>オフライン/知恵袋：</td><td><select name="onoroff" id="selectOption">
        <option value="オフライン">オフライン</option>
        <option value="知恵袋">知恵袋</option>
      </select></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item1" ></label></td><td>#は検索タグとなります</td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item2" ></label></td></tr>
      <tr><td class="blue"><label>#(任意)</td><td><input type="text" name="item3" ></label></td></tr>
    </tr>
    </table>
     </fieldset>
  </div>
  <div>
   <fieldset id="chieTable" class="hidden"> 
    <legend>Request 知恵袋</legend>
    <table>
      <!-- 質問者情報(非表示) -->
      <tr style="display: none;"><label><td><input type="text" name="name" value="<?php echo $personalname ?>" readonly></td><td><input type="text" name="personalid" value="<?php echo $personalid ?>" readonly></td></label></td></tr>
      <!-- 回答者情報(非表示) -->
      <tr style="display: none;"><label><td><input type="text" name="responsername" value="<?php echo $colleguename ?>" readonly></td><td><input type="text" name="responserid" value="<?php echo $colleagueid ?>" readonly></td></label></td></tr>
      <tr><td><label>タイトル：</td><td><input type="text" name="threadname" id="threadname" value="<?php echo $title ?>" readonly></label></td></tr>
      <tr><td><label>問合わせ内容：</td><td><textarea name="comment"></textarea></label></td></tr>
      <!-- 質問or回答(非表示)ここでは必ず質問となる -->
      <tr style="display: none;"><label><td><input type="text" name="qa" value="q"></td></label></tr>
    </table>
    </fieldset>
    <div class="space"></div>
    <tr><input type="submit" value="送信">
  </div>
</form>

<div style='height: 10px'></div>
<ul>
<?php if ($_SESSION["kanri_flg"] != 1) : ?>
      <li><a href="mypage.php" >マイページに戻る</a></li>
    <?php else : ?>
      <li><a href="kanrisha_mypage.php" >管理者ページに移動する</a></li>
    <?php endif; ?>
	</ul>

<!-- Main[End] -->

<script>
  // 最初はchieTableを隠した状態にする
  $(document).ready(function(){
    $('#chieTable').hide();
  });
  // 知恵袋が選ばれたらchieTableが出るようにセット
  $('#selectOption').change(function(){
    let selectedVal = $(this).val();
    if(selectedVal === '知恵袋'){
      $('#chieTable').show();
    }else{
      $('#chieTable').hide();
    }
  })

  // Request SMCBのタイトルを入力すると、知恵袋のタイトルにその場で反映される仕組み
  $('#title').on('input',function(){
    let title = $(this).val();
    $('#threadname').val(title);
  });

if$(document).ready(function(){
      if("<?= $kanri_flg; ?>"=="1"){
        $('mypage').html(<a href="kanrisha_mypage.php">マイページに戻る</a>);
      };
    });
</script>
</body>
</html>
